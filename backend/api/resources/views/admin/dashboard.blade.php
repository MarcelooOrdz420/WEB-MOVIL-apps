<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <title>Pollos y Parrillas El Dorado - Dashboard</title>
    <style>
        :root {
            --bg: #fff8f1;
            --panel: #ffffff;
            --text: #24160f;
            --brand: #d35400;
            --brand-dark: #8b2f00;
            --border: #f0d7c3;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background: radial-gradient(circle at top right, #ffe8d6, var(--bg));
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 16px;
        }

        h1 { color: var(--brand-dark); }

        .cards {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            margin-bottom: 24px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
        }

        .label { font-size: 13px; opacity: 0.75; }
        .value { font-size: 28px; font-weight: 700; color: var(--brand-dark); }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--panel);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        th { background: #fff0e4; }
    </style>
</head>
<body>
<div class="container">
    <h1>Dashboard de Ventas y Pedidos - Pollos y Parrillas "El Dorado"</h1>

    <div class="cards">
        <div class="card">
            <div class="label">Ventas de hoy</div>
            <div class="value">S/ {{ number_format($todaySales, 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Ventas del mes</div>
            <div class="value">S/ {{ number_format($monthSales, 2) }}</div>
        </div>
        <div class="card">
            <div class="label">Pedidos activos</div>
            <div class="value">{{ $pendingOrders }}</div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>Codigo</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Pago</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        @forelse($latestOrders as $order)
            <tr>
                <td>{{ $order->tracking_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>S/ {{ number_format($order->total_amount, 2) }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->payment_method ?? 'n/a' }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Sin pedidos aun.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
