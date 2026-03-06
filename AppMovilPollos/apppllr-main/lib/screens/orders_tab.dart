import 'package:flutter/material.dart';
import '../services/order_api_service.dart';
import '../services/session_service.dart';
import '../state/orders_controller.dart';

class OrdersTab extends StatefulWidget {
  const OrdersTab({super.key});

  @override
  State<OrdersTab> createState() => _OrdersTabState();
}

class _OrdersTabState extends State<OrdersTab> {
  late Future<List<Map<String, dynamic>>> _future;

  @override
  void initState() {
    super.initState();
    _future = _loadOrders();
  }

  String _formatDate(dynamic raw) {
    final value = (raw ?? '').toString().trim();
    if (value.isEmpty) return '-';
    final parsed = DateTime.tryParse(value);
    if (parsed == null) return value;
    return '${parsed.day.toString().padLeft(2, '0')}/${parsed.month.toString().padLeft(2, '0')}/${parsed.year} '
        '${parsed.hour.toString().padLeft(2, '0')}:${parsed.minute.toString().padLeft(2, '0')}';
  }

  Future<List<Map<String, dynamic>>> _loadOrders() async {
    final logged = await SessionService().isLoggedIn();
    if (!logged) return <Map<String, dynamic>>[];

    try {
      return await OrderApiService().myOrders();
    } catch (_) {
      return <Map<String, dynamic>>[];
    }
  }

  @override
  Widget build(BuildContext context) {
    final localOrders = OrdersScope.of(context).orders;

    return SafeArea(
      child: FutureBuilder<List<Map<String, dynamic>>>(
        future: _future,
        builder: (context, snap) {
          final serverOrders = snap.data ?? const <Map<String, dynamic>>[];

          return RefreshIndicator(
            onRefresh: () async {
              setState(() => _future = _loadOrders());
              await _future;
            },
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                const SizedBox(height: 10),
                const Icon(Icons.receipt_long, color: Colors.orange, size: 72),
                const SizedBox(height: 10),
                const Center(
                  child: Text(
                    'Tus pedidos',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                ),
                const SizedBox(height: 20),
                if (serverOrders.isEmpty && localOrders.isEmpty)
                  const Center(child: Text('Aun no tienes pedidos registrados.')),
                ...serverOrders.map((o) => _serverCard(o)),
                ...localOrders.map((o) => _localCard(o)),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _serverCard(Map<String, dynamic> o) {
    final itemList = ((o['items'] as List?) ?? const [])
        .map((e) => (e as Map).cast<String, dynamic>())
        .toList();
    final items = itemList
        .map((e) => e['product_name']?.toString() ?? '')
        .where((e) => e.isNotEmpty)
        .join(', ');
    final total = double.tryParse((o['total_amount'] ?? '0').toString()) ?? 0.0;

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Codigo: ${o['tracking_code'] ?? '-'}',
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 6),
            Text('Estado: ${o['status'] ?? '-'}'),
            Text('Fecha: ${_formatDate(o['created_at'])}'),
            if (items.isNotEmpty) Text('Items: $items'),
            const SizedBox(height: 8),
            Text(
              'Total: S/. ${total.toStringAsFixed(2)}',
              style: const TextStyle(color: Colors.red, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Align(
              alignment: Alignment.centerRight,
              child: TextButton(
                onPressed: () => _showReceipt(o, itemList, total),
                child: const Text('Ver boleta'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _localCard(OrderModel o) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Resumen local ID: #${o.orderId}', style: const TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Text('Fecha: ${o.date.day}/${o.date.month}/${o.date.year}'),
            Text('Articulos: ${o.itemsText}'),
            Text('Cantidad: ${o.totalProducts}'),
            const SizedBox(height: 8),
            Text(
              'Total: S/. ${o.totalPaid.toStringAsFixed(2)}',
              style: const TextStyle(color: Colors.red, fontWeight: FontWeight.bold),
            ),
          ],
        ),
      ),
    );
  }

  void _showReceipt(
    Map<String, dynamic> order,
    List<Map<String, dynamic>> items,
    double total,
  ) {
    showDialog<void>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Boleta ${order['tracking_code'] ?? ''}'),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text('Cliente: ${order['customer_name'] ?? '-'}'),
              Text('Telefono: ${order['customer_phone'] ?? '-'}'),
              Text('Pago: ${order['payment_method'] ?? '-'}'),
              Text('Estado: ${order['status'] ?? '-'}'),
              const SizedBox(height: 10),
              const Text('Detalle', style: TextStyle(fontWeight: FontWeight.bold)),
              const SizedBox(height: 6),
              ...items.map((item) {
                final line = double.tryParse((item['line_total'] ?? '0').toString()) ?? 0.0;
                return Padding(
                  padding: const EdgeInsets.only(bottom: 6),
                  child: Text(
                    '${item['product_name'] ?? '-'} x${item['quantity'] ?? 0}  |  S/. ${line.toStringAsFixed(2)}',
                  ),
                );
              }),
              const Divider(),
              Text(
                'Total: S/. ${total.toStringAsFixed(2)}',
                style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.orange),
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cerrar'),
          ),
        ],
      ),
    );
  }
}
