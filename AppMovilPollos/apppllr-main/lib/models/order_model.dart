import 'producto.dart';

class OrderModel {
  final String orderId;
  final List<OrderItem> items;
  final double subtotal;
  final double deliveryFee;
  final double total;
  final DateTime createdAt;
  final String status;

  OrderModel({
    required this.orderId,
    required this.items,
    required this.subtotal,
    required this.deliveryFee,
    required this.total,
    required this.createdAt,
    required this.status,
  });
}

class OrderItem {
  final Producto producto;
  final int quantity;

  OrderItem({
    required this.producto,
    required this.quantity,
  });

  double get total => producto.price * quantity;
}