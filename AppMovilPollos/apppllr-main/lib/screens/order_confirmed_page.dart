import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class OrderConfirmedPage extends StatelessWidget {
  final Map<String, dynamic>? serverOrder;

  const OrderConfirmedPage({super.key, this.serverOrder});

  @override
  Widget build(BuildContext context) {
    final tracking = (serverOrder?['tracking_code'] ?? 'SIN-CODIGO').toString();
    final status = (serverOrder?['status'] ?? 'pending').toString();
    final total = double.tryParse((serverOrder?['total_amount'] ?? '0').toString()) ?? 0.0;
    final items = ((serverOrder?['items'] as List?) ?? const [])
        .map((e) => (e as Map).cast<String, dynamic>())
        .toList();

    return Scaffold(
      appBar: AppBar(title: const Text('Pedido confirmado')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const SizedBox(height: 20),
            const Icon(Icons.verified, color: Colors.green, size: 90),
            const SizedBox(height: 10),
            const Text(
              'Pedido registrado correctamente',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 12),
            Card(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Resumen del pedido', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    Text('Codigo: $tracking', style: const TextStyle(color: Colors.black54)),
                    const SizedBox(height: 4),
                    Text('Estado: $status', style: const TextStyle(color: Colors.black54)),
                    const SizedBox(height: 8),
                    if (items.isNotEmpty)
                      ...items.map((item) {
                        final line = double.tryParse((item['line_total'] ?? '0').toString()) ?? 0.0;
                        return Padding(
                          padding: const EdgeInsets.only(bottom: 4),
                          child: Text(
                            '${item['product_name'] ?? '-'} x${item['quantity'] ?? 0} | S/. ${line.toStringAsFixed(2)}',
                            style: const TextStyle(color: Colors.black87),
                          ),
                        );
                      }),
                    const Divider(),
                    Row(
                      children: [
                        const Expanded(
                          child: Text('Total:', style: TextStyle(fontWeight: FontWeight.bold)),
                        ),
                        Text(
                          'S/. ${total.toStringAsFixed(2)}',
                          style: const TextStyle(color: Colors.red, fontWeight: FontWeight.bold),
                        ),
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
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  padding: const EdgeInsets.symmetric(vertical: 14),
                ),
                onPressed: () => context.go('/app'),
                child: const Text('Volver al inicio', style: TextStyle(color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
