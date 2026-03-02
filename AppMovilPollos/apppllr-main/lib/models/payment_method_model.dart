enum PaymentType {
  cash,
  card,
  yape,
  plin,
}

class PaymentMethodModel {
  final PaymentType type;
  final String label;

  PaymentMethodModel({
    required this.type,
    required this.label,
  });
}