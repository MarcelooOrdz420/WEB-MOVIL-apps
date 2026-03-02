import 'package:flutter/foundation.dart';
import 'package:flutter/widgets.dart';
import '../models/producto.dart';

class CartItem {
  final Producto producto;
  int qty;

  CartItem({required this.producto, this.qty = 1});

  double get total => producto.price * qty;
}

class CartController extends ChangeNotifier {
  final Map<int, CartItem> _items = {};

  List<CartItem> get items => _items.values.toList();

  int get totalItemsCount => _items.values.fold(0, (a, b) => a + b.qty);

  double get subtotal => _items.values.fold(0.0, (a, b) => a + b.total);

  double deliveryFee({double freeOver = 70, double fee = 4.0}) {
    if (subtotal >= freeOver) return 0.0;
    if (subtotal == 0) return 0.0;
    return fee;
  }

  double total({double freeOver = 70, double fee = 4.0}) {
    return subtotal + deliveryFee(freeOver: freeOver, fee: fee);
  }

  void add(Producto p) {
    final existing = _items[p.id];
    if (existing != null) {
      existing.qty += 1;
    } else {
      _items[p.id] = CartItem(producto: p, qty: 1);
    }
    notifyListeners();
  }

  void removeOne(int productId) {
    final existing = _items[productId];
    if (existing == null) return;
    existing.qty -= 1;
    if (existing.qty <= 0) _items.remove(productId);
    notifyListeners();
  }

  void setQty(int productId, int qty) {
    final existing = _items[productId];
    if (existing == null) return;
    existing.qty = qty.clamp(1, 999);
    notifyListeners();
  }

  void delete(int productId) {
    _items.remove(productId);
    notifyListeners();
  }

  void clear() {
    _items.clear();
    notifyListeners();
  }
}

/// Provider sin paquetes (InheritedNotifier)
class CartScope extends InheritedNotifier<CartController> {
  const CartScope({super.key, required CartController controller, required super.child})
      : super(notifier: controller);

  static CartController of(context) {
    final scope = context.dependOnInheritedWidgetOfExactType<CartScope>();
    if (scope == null) {
      throw Exception('CartScope no encontrado. Envuélvelo en main.dart');
    }
    return scope.notifier!;
  }
}
