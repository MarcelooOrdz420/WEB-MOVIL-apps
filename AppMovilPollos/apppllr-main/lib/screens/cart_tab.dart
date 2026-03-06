import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../services/session_service.dart';
import '../state/cart_controller.dart';
import '../widgets/producto_image.dart';

class CartTab extends StatefulWidget {
  const CartTab({super.key});

  @override
  State<CartTab> createState() => _CartTabState();
}

class _CartTabState extends State<CartTab> {
  bool _delivery = true; // Delivery / Recibo en local
  bool _now = true; // Ahora / Programar

  @override
  Widget build(BuildContext context) {
    final cart = CartScope.of(context);

    final subtotal = cart.subtotal;
    final deliveryFee = cart.deliveryFee(freeOver: 70, fee: _delivery ? 4 : 0);
    final total = subtotal + deliveryFee;

    return SafeArea(
      child: ListView(
        padding: const EdgeInsets.all(12),
        children: [
          const Text('Carrito', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),

          if (cart.items.isEmpty)
            const Padding(
              padding: EdgeInsets.only(top: 50),
              child: Center(child: Text('Tu carrito esta vacio')),
            ),

          ...cart.items.map((it) {
            return Card(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              child: Padding(
                padding: const EdgeInsets.all(10),
                child: Column(
                  children: [
                    Row(
                      children: [
                        ProductoImage(
                          producto: it.producto,
                          width: 54,
                          height: 54,
                          borderRadius: BorderRadius.circular(10),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(it.producto.name, style: const TextStyle(fontWeight: FontWeight.bold)),
                              const SizedBox(height: 4),
                              Text('S/. ${it.producto.price.toStringAsFixed(2)}'),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        _qtyBtn('-', onTap: () => cart.removeOne(it.producto.id)),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 12),
                          child: Text('${it.qty}', style: const TextStyle(fontWeight: FontWeight.bold)),
                        ),
                        _qtyBtn('+', onTap: () => cart.add(it.producto)),
                        const Spacer(),
                        TextButton(
                          onPressed: () => cart.delete(it.producto.id),
                          child: const Text('Eliminar', style: TextStyle(color: Colors.red)),
                        )
                      ],
                    ),
                  ],
                ),
              ),
            );
          }),

          if (cart.items.isNotEmpty) ...[
            const SizedBox(height: 16),
            const Text('Resumen de orden', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            _row('Precio productos', subtotal),
            _row('Delivery (gratis mayor a 70.00)', deliveryFee),
            const Divider(),
            _row('Total', total, bold: true, valueColor: Colors.red),

            const SizedBox(height: 16),
            const Text('Opciones de delivery', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),

            Row(
              children: [
                Expanded(
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: _delivery ? Colors.orange : Colors.white,
                      foregroundColor: _delivery ? Colors.white : Colors.black,
                      side: _delivery ? null : const BorderSide(color: Colors.black12),
                    ),
                    onPressed: () => setState(() => _delivery = true),
                    child: const Text('Delivery'),
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: !_delivery ? Colors.orange : Colors.white,
                      foregroundColor: !_delivery ? Colors.white : Colors.black,
                      side: !_delivery ? null : const BorderSide(color: Colors.black12),
                    ),
                    onPressed: () => setState(() => _delivery = false),
                    child: const Text('Recibo en local'),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 12),
            Row(
              children: [
                const Icon(Icons.location_on_outlined, size: 18),
                const SizedBox(width: 6),
                const Expanded(child: Text('123 Av. Huancavelica, Huancayo')),
                TextButton(onPressed: () {}, child: const Text('Cambiar')),
              ],
            ),

            const SizedBox(height: 12),
            const Text('Hora de entrega', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 6),
            Row(
              children: [
                Expanded(
                  child: InkWell(
                    onTap: () => setState(() => _now = true),
                    child: Row(
                      children: [
                        Icon(_now ? Icons.radio_button_checked : Icons.radio_button_off,
                            size: 18, color: Colors.orange),
                        const SizedBox(width: 6),
                        const Text('Ahora'),
                      ],
                    ),
                  ),
                ),
                Expanded(
                  child: InkWell(
                    onTap: () => setState(() => _now = false),
                    child: Row(
                      children: [
                        Icon(!_now ? Icons.radio_button_checked : Icons.radio_button_off,
                            size: 18, color: Colors.orange),
                        const SizedBox(width: 6),
                        const Text('Programar horario'),
                      ],
                    ),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(backgroundColor: Colors.orange, padding: const EdgeInsets.symmetric(vertical: 14)),
                onPressed: () async {
                  final logged = await SessionService().isLoggedIn();
                  if (!context.mounted) return;
                  if (!logged) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Debes iniciar sesion para continuar con la compra.')),
                    );
                    context.go('/correo');
                    return;
                  }
                  context.push('/pago');
                },
                child: Text(
                  'Proceder al pago (${cart.items.length} productos)',
                  style: const TextStyle(color: Colors.white),
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _row(String label, double value, {bool bold = false, Color? valueColor}) {
    final st = TextStyle(fontWeight: bold ? FontWeight.bold : FontWeight.normal);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Expanded(child: Text(label, style: st)),
          Text('S/. ${value.toStringAsFixed(2)}', style: st.copyWith(color: valueColor)),
        ],
      ),
    );
  }

  Widget _qtyBtn(String text, {required VoidCallback onTap}) {
    return InkWell(
      onTap: onTap,
      child: Container(
        width: 34,
        height: 34,
        alignment: Alignment.center,
        decoration: BoxDecoration(
          border: Border.all(color: Colors.black12),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Text(text, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
      ),
    );
  }
}


