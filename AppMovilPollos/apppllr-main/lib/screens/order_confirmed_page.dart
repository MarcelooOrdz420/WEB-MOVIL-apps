import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../state/cart_controller.dart';
import '../state/orders_controller.dart';

class OrderConfirmedPage extends StatelessWidget {
  const OrderConfirmedPage({super.key});

  String _orderId() {
    final ts = DateTime.now().millisecondsSinceEpoch.toString();
    return 'PBS${ts.substring(ts.length - 10)}';
  }

  @override
  Widget build(BuildContext context) {
    final cart = CartScope.of(context);
    final orders = OrdersScope.of(context);

    final id = _orderId();
    final totalPaid = cart.total(freeOver: 70, fee: 4);

    return Scaffold(
      appBar: AppBar(title: const Text('Pedido Confirmado')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const SizedBox(height: 20),
            const Icon(Icons.verified, color: Colors.green, size: 90),
            const SizedBox(height: 10),
            const Text('¡Pedido Confirmado!', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),

            Card(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Resumen del Pedido', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    Text('ID: #$id', style: const TextStyle(color: Colors.black54)),
                    const SizedBox(height: 6),
                    Text('Artículos: ${cart.items.map((e) => e.producto.name).join(', ')}',
                        style: const TextStyle(color: Colors.black54)),
                    const SizedBox(height: 6),
                    Text('Cantidad de Productos: ${cart.totalItemsCount}', style: const TextStyle(color: Colors.black54)),
                    const Divider(),
                    Row(
                      children: [
                        const Expanded(child: Text('Total Pagado:', style: TextStyle(fontWeight: FontWeight.bold))),
                        Text('S/. ${totalPaid.toStringAsFixed(2)}',
                            style: const TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ],
                ),
              ),
            ),

            const Spacer(),

            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(backgroundColor: Colors.orange, padding: const EdgeInsets.symmetric(vertical: 14)),
                onPressed: () async {
                  // ✅ Guardar pedido en Órdenes
                  final order = OrderModel(
                    orderId: id,
                    date: DateTime.now(),
                    items: cart.items.map((e) => OrderItem(name: e.producto.name, qty: e.qty)).toList(),
                    totalPaid: totalPaid,
                  );
                  await orders.addOrder(order);

                  // ✅ Vaciar carrito
                  cart.clear();

                  if (context.mounted) context.go('/app');
                },
                child: const Text('Volver al inicio', style: TextStyle(color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
