<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = $this->buildAdminOrdersQuery(request())
            ->with(['items', 'user'])
            ->latest()
            ->paginate(20);

        return response()->json($orders);
    }

    public function export(Request $request): Response
    {
        $orders = $this->buildAdminOrdersQuery($request)
            ->with(['items'])
            ->latest()
            ->get();

        $header = [
            'tracking_code',
            'created_at',
            'customer_name',
            'customer_phone',
            'status',
            'payment_method',
            'payment_status',
            'payment_reference',
            'total_amount',
        ];

        $rows = [implode(',', $header)];

        foreach ($orders as $order) {
            $row = [
                $order->tracking_code,
                optional($order->created_at)->toDateTimeString(),
                $this->csvSafe($order->customer_name),
                $this->csvSafe($order->customer_phone),
                $order->status,
                $order->payment_method,
                $order->payment_status,
                $this->csvSafe($order->payment_reference ?: ''),
                number_format((float) $order->total_amount, 2, '.', ''),
            ];
            $rows[] = implode(',', $row);
        }

        $content = implode(PHP_EOL, $rows).PHP_EOL;

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pedidos-admin.csv"',
        ]);
    }

    public function myOrders(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['items', 'statusHistory'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->role !== 'admin' && $order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $order->load(['items', 'statusHistory']);

        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'delivery_type' => ['required', Rule::in(['pickup', 'delivery'])],
            'payment_method' => ['required', Rule::in(['yape', 'plin', 'transfer', 'cod'])],
            'payment_reference' => ['nullable', 'string', 'max:120'],
            'salad_type' => ['nullable', Rule::in(['dulce', 'salada'])],
            'drink_note' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($data['delivery_type'] === 'delivery' && empty($data['address'])) {
            return response()->json(['message' => 'Direccion requerida para delivery.'], 422);
        }

        if ($data['payment_method'] !== 'cod' && empty($data['payment_reference'])) {
            return response()->json(['message' => 'Ingresa codigo/operacion del pago para validar.'], 422);
        }

        $productIds = collect($data['items'])->pluck('product_id')->all();
        $hasChickenProduct = Product::query()
            ->whereIn('id', $productIds)
            ->where('category', 'pollos')
            ->exists();

        if ($hasChickenProduct && empty($data['salad_type'])) {
            return response()->json(['message' => 'Selecciona ensalada dulce o salada para pedidos de pollo.'], 422);
        }

        $order = DB::transaction(function () use ($data, $request): Order {
            $trackingCode = strtoupper('ED-'.substr(uniqid(), -8));

            $order = Order::create([
                'user_id' => $request->user()->id,
                'tracking_code' => $trackingCode,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'delivery_type' => $data['delivery_type'],
                'status' => Order::STATUS_PENDING,
                'total_amount' => 0,
                'payment_method' => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'payment_proof_path' => null,
                'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'reported',
                'payment_reported_at' => $data['payment_method'] === 'cod' ? null : now(),
                'payment_verified_at' => null,
                'salad_type' => $data['salad_type'] ?? null,
                'drink_note' => $data['drink_note'] ?? null,
                'address' => $data['address'] ?? null,
                'reference' => $data['reference'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::query()->where('is_available', true)->findOrFail($item['product_id']);
                $lineTotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $order->update(['total_amount' => $total]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => Order::STATUS_PENDING,
                'note' => 'Pedido creado',
                'changed_by' => $request->user()->id,
            ]);

            return $order->load(['items', 'statusHistory']);
        });

        return response()->json($order, 201);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PREPARING,
                Order::STATUS_ON_THE_WAY,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ])],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'changed_by' => $request->user()->id,
        ]);

        return response()->json($order->load('statusHistory'));
    }

    public function updatePaymentStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'payment_status' => ['required', Rule::in(['pending', 'reported', 'verified', 'rejected'])],
            'payment_reference' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update([
            'payment_status' => $data['payment_status'],
            'payment_reference' => $data['payment_reference'] ?? $order->payment_reference,
            'payment_verified_at' => $data['payment_status'] === 'verified' ? now() : null,
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => $data['note'] ?? ('Pago actualizado: '.$data['payment_status']),
            'changed_by' => $request->user()->id,
        ]);

        return response()->json($order->fresh(['items', 'statusHistory']));
    }

    public function destroy(Request $request, Order $order): JsonResponse
    {
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => 'Pedido eliminado por admin',
            'changed_by' => $request->user()->id,
        ]);

        $order->delete();

        return response()->json(['message' => 'Pedido eliminado']);
    }

    public function uploadPaymentProof(Request $request, Order $order): JsonResponse
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'payment_reference' => ['nullable', 'string', 'max:120'],
        ]);

        $path = $request->file('proof')->store('payment-proofs', 'public');
        $publicPath = '/storage/'.$path;

        $order->update([
            'payment_proof_path' => $publicPath,
            'payment_reference' => $data['payment_reference'] ?? $order->payment_reference,
            'payment_status' => $order->payment_method === 'cod' ? $order->payment_status : 'reported',
            'payment_reported_at' => now(),
        ]);

        return response()->json([
            'message' => 'Comprobante subido correctamente',
            'order' => $order->fresh(['items', 'statusHistory']),
        ]);
    }

    public function downloadReceipt(Request $request, Order $order): Response
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response('No autorizado', 403);
        }

        $content = $this->buildReceiptHtml($order);
        $filename = 'boleta-'.$order->tracking_code.'.html';

        return response($content, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function receiptView(Request $request, Order $order): Response
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response('No autorizado', 403);
        }

        return response($this->buildReceiptHtml($order, true), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    public function track(string $trackingCode): JsonResponse
    {
        $order = Order::query()
            ->with(['items', 'statusHistory'])
            ->where('tracking_code', strtoupper($trackingCode))
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json([
            'tracking_code' => $order->tracking_code,
            'status' => $order->status,
            'delivery_type' => $order->delivery_type,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_reference' => $order->payment_reference,
            'payment_proof_path' => $order->payment_proof_path,
            'payment_reported_at' => optional($order->payment_reported_at)?->toDateTimeString(),
            'payment_verified_at' => optional($order->payment_verified_at)?->toDateTimeString(),
            'salad_type' => $order->salad_type,
            'drink_note' => $order->drink_note,
            'address' => $order->address,
            'reference' => $order->reference,
            'latitude' => $order->latitude,
            'longitude' => $order->longitude,
            'items' => $order->items,
            'status_history' => $order->statusHistory,
        ]);
    }

    private function buildAdminOrdersQuery(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->string('payment_status')->toString());
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->string('date_from')->toString());
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->string('date_to')->toString());
        }

        return $query;
    }

    private function csvSafe(string $value): string
    {
        $escaped = str_replace('"', '""', $value);

        return '"'.$escaped.'"';
    }

    private function buildReceiptHtml(Order $order, bool $includeActions = false): string
    {
        $order->loadMissing('items');

        $createdAt = optional($order->created_at)->format('d/m/Y h:i a');
        $delivery = $order->delivery_type === 'delivery' ? 'Delivery' : 'Recojo en local';
        $paymentMethod = match ($order->payment_method) {
            'yape' => 'Yape',
            'plin' => 'Plin',
            'transfer' => 'Transferencia',
            'cod' => 'Contraentrega',
            default => $order->payment_method,
        };
        $paymentStatus = match ($order->payment_status) {
            'pending' => 'Pendiente',
            'reported' => 'Reportado',
            'verified' => 'Verificado',
            'rejected' => 'Rechazado',
            default => $order->payment_status,
        };
        $status = match ($order->status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'preparing' => 'Preparando',
            'on_the_way' => 'En camino',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            default => $order->status,
        };

        $itemsHtml = '';
        foreach ($order->items as $item) {
            $itemsHtml .= '<tr>'.
                '<td>'.e($item->product_name).'</td>'.
                '<td style="text-align:center;">'.(int) $item->quantity.'</td>'.
                '<td style="text-align:right;">S/ '.number_format((float) $item->unit_price, 2).'</td>'.
                '<td style="text-align:right;">S/ '.number_format((float) $item->line_total, 2).'</td>'.
            '</tr>';
        }

        $actionsHtml = $includeActions
            ? '<div class="actions"><button onclick="window.print()">Imprimir boleta</button></div>'
            : '';

        return '<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Boleta '.$order->tracking_code.'</title>
<style>
body{font-family:Segoe UI,Tahoma,Verdana,sans-serif;background:#fff8f2;color:#2c170d;margin:0;padding:24px}
.sheet{max-width:760px;margin:0 auto;background:#fff;border:1px solid #ffd7bd;border-radius:18px;padding:24px;box-shadow:0 18px 34px rgba(70,25,0,.08)}
.head{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;border-bottom:2px dashed #ffd7bd;padding-bottom:16px;margin-bottom:16px}
.brand{font-weight:900;color:#ff6f1f;font-size:24px}
.muted{color:#6b4226;font-size:13px}
.pill{display:inline-block;padding:6px 10px;border-radius:999px;background:#fff1e5;border:1px solid #ffc99f;color:#8d4304;font-size:12px;font-weight:700}
.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:16px}
.box{background:#fffaf6;border:1px solid #ffe1cb;border-radius:14px;padding:12px}
table{width:100%;border-collapse:collapse;margin-top:8px}
th,td{padding:10px 8px;border-bottom:1px solid #f5dfcf;font-size:14px}
th{text-align:left;color:#7a3c11}
.total{margin-top:16px;display:flex;justify-content:flex-end;font-size:20px;font-weight:900;color:#cf5600}
.actions{margin-bottom:14px;text-align:right}
.actions button{background:#ff7a1a;color:#fff;border:0;border-radius:10px;padding:10px 14px;font-weight:700;cursor:pointer}
@media print {.actions{display:none} body{background:#fff;padding:0} .sheet{box-shadow:none;border:0}}
</style>
</head>
<body>
'.$actionsHtml.'
<div class="sheet">
  <div class="head">
    <div>
      <div class="brand">Pollos y Parrillas "El Dorado"</div>
      <div class="muted">Boleta de compra / resumen de pedido</div>
      <div class="muted">Fecha: '.e($createdAt).'</div>
    </div>
    <div style="text-align:right;">
      <div class="pill">Codigo '.$order->tracking_code.'</div>
      <div class="muted" style="margin-top:8px;">Estado: '.e($status).'</div>
      <div class="muted">Pago: '.e($paymentStatus).'</div>
    </div>
  </div>

  <div class="grid">
    <div class="box">
      <strong>Cliente</strong>
      <div class="muted" style="margin-top:6px;">'.e($order->customer_name).'</div>
      <div class="muted">Telefono: '.e($order->customer_phone).'</div>
    </div>
    <div class="box">
      <strong>Entrega y pago</strong>
      <div class="muted" style="margin-top:6px;">Tipo: '.e($delivery).'</div>
      <div class="muted">Metodo: '.e($paymentMethod).'</div>
      <div class="muted">Operacion: '.e($order->payment_reference ?: 'sin codigo').'</div>
    </div>
  </div>

  <div class="box">
    <strong>Detalle</strong>
    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th style="text-align:center;">Cant.</th>
          <th style="text-align:right;">P. Unit.</th>
          <th style="text-align:right;">Subtotal</th>
        </tr>
      </thead>
      <tbody>'.$itemsHtml.'</tbody>
    </table>
  </div>

  <div class="total">Total pagado: S/ '.number_format((float) $order->total_amount, 2).'</div>
</div>
</body>
</html>';
    }
}
