import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../config/api_config.dart';
import '../state/cart_controller.dart';

enum PayMethod { yape, plin, transferencia, efectivo }
enum DeliveryType { delivery, pickup }

class PaymentPage extends StatefulWidget {
  const PaymentPage({super.key});

  @override
  State<PaymentPage> createState() => _PaymentPageState();
}

class _PaymentPageState extends State<PaymentPage> {
  final _nameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _addressCtrl = TextEditingController();
  final _referenceCtrl = TextEditingController();
  final _operationCtrl = TextEditingController();
  final _latitudeCtrl = TextEditingController();
  final _longitudeCtrl = TextEditingController();

  PayMethod _method = PayMethod.yape;
  DeliveryType _deliveryType = DeliveryType.delivery;
  String _saladType = 'dulce';

  @override
  void dispose() {
    _nameCtrl.dispose();
    _phoneCtrl.dispose();
    _addressCtrl.dispose();
    _referenceCtrl.dispose();
    _operationCtrl.dispose();
    _latitudeCtrl.dispose();
    _longitudeCtrl.dispose();
    super.dispose();
  }

  String get _yapeQr => ApiConfig.resolveUrl('/images/yape-qr.png');
  String get _plinQr => ApiConfig.resolveUrl('/images/plin-qr.png');

  bool get _needsOperationCode => _method != PayMethod.efectivo;

  @override
  Widget build(BuildContext context) {
    final cart = CartScope.of(context);
    final hasChicken = cart.items.any((item) => item.producto.categoria.toLowerCase() == 'pollos');
    final deliveryFee = _deliveryType == DeliveryType.delivery ? cart.deliveryFee() : 0.0;
    final total = cart.subtotal + deliveryFee;

    return Scaffold(
      appBar: AppBar(title: const Text('Pago y entrega')),
      body: ListView(
        padding: const EdgeInsets.all(14),
        children: [
          const Text(
            'Completa tus datos y elige como pagar',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
          ),
          const SizedBox(height: 12),
          _section(
            title: 'Datos del cliente',
            child: Column(
              children: [
                _field(_nameCtrl, 'Nombre'),
                const SizedBox(height: 10),
                _field(_phoneCtrl, 'Telefono'),
              ],
            ),
          ),
          _section(
            title: 'Entrega',
            child: Column(
              children: [
                SegmentedButton<DeliveryType>(
                  segments: const [
                    ButtonSegment(
                      value: DeliveryType.delivery,
                      label: Text('Delivery'),
                      icon: Icon(Icons.delivery_dining),
                    ),
                    ButtonSegment(
                      value: DeliveryType.pickup,
                      label: Text('Recojo'),
                      icon: Icon(Icons.storefront),
                    ),
                  ],
                  selected: {_deliveryType},
                  onSelectionChanged: (values) {
                    setState(() => _deliveryType = values.first);
                  },
                ),
                if (_deliveryType == DeliveryType.delivery) ...[
                  const SizedBox(height: 10),
                  _field(_addressCtrl, 'Direccion de entrega'),
                  const SizedBox(height: 10),
                  _field(_referenceCtrl, 'Referencia (opcional)'),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Expanded(child: _field(_latitudeCtrl, 'Latitud (opcional)')),
                      const SizedBox(width: 10),
                      Expanded(child: _field(_longitudeCtrl, 'Longitud (opcional)')),
                    ],
                  ),
                  const SizedBox(height: 10),
                  OutlinedButton.icon(
                    onPressed: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text(
                            'Ingresa tu latitud y longitud si deseas compartir tu ubicacion exacta en esta version.',
                          ),
                        ),
                      );
                    },
                    icon: const Icon(Icons.my_location_outlined),
                    label: const Text('Usar ubicacion exacta'),
                  ),
                ],
              ],
            ),
          ),
          if (hasChicken)
            _section(
              title: 'Ensalada para pollos',
              child: DropdownButtonFormField<String>(
                value: _saladType,
                items: const [
                  DropdownMenuItem(value: 'dulce', child: Text('Dulce')),
                  DropdownMenuItem(value: 'salada', child: Text('Salada')),
                ],
                decoration: _decor('Tipo de ensalada'),
                onChanged: (value) => setState(() => _saladType = value ?? 'dulce'),
              ),
            ),
          _section(
            title: 'Metodo de pago',
            child: Column(
              children: [
                _payTile('Yape', PayMethod.yape, Icons.qr_code_2),
                _payTile('Plin', PayMethod.plin, Icons.qr_code),
                _payTile('Transferencia bancaria', PayMethod.transferencia, Icons.account_balance),
                _payTile('Pago contraentrega', PayMethod.efectivo, Icons.local_shipping),
                const SizedBox(height: 10),
                _paymentPanel(),
                if (_needsOperationCode) ...[
                  const SizedBox(height: 10),
                  _field(_operationCtrl, 'Codigo de operacion'),
                ],
              ],
            ),
          ),
          _section(
            title: 'Resumen',
            child: Column(
              children: [
                _row('Subtotal', cart.subtotal),
                _row('Delivery', deliveryFee),
                const Divider(),
                _row('Total', total, highlight: true),
              ],
            ),
          ),
          const SizedBox(height: 8),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.orange,
                padding: const EdgeInsets.symmetric(vertical: 14),
              ),
              onPressed: cart.items.isEmpty ? null : () => context.push('/confirmacion'),
              child: const Text(
                'Continuar con el pedido',
                style: TextStyle(color: Colors.white),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _section({required String title, required Widget child}) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
            const SizedBox(height: 10),
            child,
          ],
        ),
      ),
    );
  }

  Widget _field(TextEditingController controller, String label) {
    return TextField(
      controller: controller,
      decoration: _decor(label),
    );
  }

  InputDecoration _decor(String label) {
    return InputDecoration(
      labelText: label,
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
      filled: true,
      fillColor: const Color(0xFFFFFBF8),
    );
  }

  Widget _payTile(String title, PayMethod method, IconData icon) {
    return RadioListTile<PayMethod>(
      contentPadding: EdgeInsets.zero,
      value: method,
      groupValue: _method,
      onChanged: (value) => setState(() => _method = value!),
      title: Text(title),
      secondary: Icon(icon, color: Colors.orange),
    );
  }

  Widget _paymentPanel() {
    if (_method == PayMethod.yape) {
      return _paymentInfoCard(
        title: 'Yape Empresa',
        subtitle: 'Numero: 999 888 777',
        child: _networkPreview(_yapeQr),
      );
    }

    if (_method == PayMethod.plin) {
      return _paymentInfoCard(
        title: 'Plin Empresa',
        subtitle: 'Numero: 999 888 777',
        child: _networkPreview(_plinQr),
      );
    }

    if (_method == PayMethod.transferencia) {
      return _paymentInfoCard(
        title: 'Transferencia',
        subtitle: 'BCP - Cuenta: 123-4567890-12\nCCI: 00212300456789012345',
        child: const Icon(Icons.account_balance_wallet_outlined, size: 44, color: Colors.orange),
      );
    }

    return _paymentInfoCard(
      title: 'Pago contraentrega',
      subtitle: 'Pagas cuando recibes tu pedido.',
      child: const Icon(Icons.payments_outlined, size: 44, color: Colors.orange),
    );
  }

  Widget _paymentInfoCard({
    required String title,
    required String subtitle,
    required Widget child,
  }) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(14),
        color: const Color(0xFFFFF7EF),
        border: Border.all(color: const Color(0xFFFFD4B1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 4),
          Text(subtitle, style: const TextStyle(color: Colors.black54)),
          const SizedBox(height: 10),
          Center(child: child),
        ],
      ),
    );
  }

  Widget _networkPreview(String url) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(14),
      child: Image.network(
        url,
        width: 170,
        height: 170,
        fit: BoxFit.contain,
        webHtmlElementStrategy: WebHtmlElementStrategy.prefer,
        errorBuilder: (_, __, ___) => Container(
          width: 170,
          height: 170,
          color: Colors.white,
          alignment: Alignment.center,
          child: const Text('Coloca tu QR\nen /public/images', textAlign: TextAlign.center),
        ),
      ),
    );
  }

  Widget _row(String label, double amount, {bool highlight = false}) {
    final style = TextStyle(
      fontWeight: highlight ? FontWeight.bold : FontWeight.normal,
      color: highlight ? Colors.orange.shade700 : Colors.black87,
    );
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Expanded(child: Text(label, style: style)),
          Text('S/. ${amount.toStringAsFixed(2)}', style: style),
        ],
      ),
    );
  }
}
