import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'api_client.dart';

class OrderApiService {
  Future<String> _token() async {
    final prefs = await SharedPreferences.getInstance();
    return (prefs.getString('token') ?? '').trim();
  }

  Future<List<Map<String, dynamic>>> myOrders() async {
    final token = await _token();
    if (token.isEmpty) return <Map<String, dynamic>>[];

    final res = await ApiClient.get<List<dynamic>>(
      '/orders/my',
      options: Options(
        headers: {'Authorization': 'Bearer $token'},
      ),
    );

    final list = (res.data ?? <dynamic>[]);
    return list.map((e) => (e as Map).cast<String, dynamic>()).toList();
  }

  Future<Map<String, dynamic>> createOrder({
    required String customerName,
    required String customerPhone,
    required String deliveryType,
    required String paymentMethod,
    String? paymentReference,
    String? saladType,
    String? drinkNote,
    String? address,
    String? reference,
    double? latitude,
    double? longitude,
    required List<Map<String, dynamic>> items,
  }) async {
    final token = await _token();
    if (token.isEmpty) {
      throw Exception('Debes iniciar sesion para realizar pedidos.');
    }

    final payload = <String, dynamic>{
      'customer_name': customerName,
      'customer_phone': customerPhone,
      'delivery_type': deliveryType,
      'payment_method': paymentMethod,
      'payment_reference': paymentReference,
      'salad_type': saladType,
      'drink_note': drinkNote,
      'address': address,
      'reference': reference,
      'latitude': latitude,
      'longitude': longitude,
      'items': items,
    };

    final res = await ApiClient.post<Map<String, dynamic>>(
      '/orders',
      data: payload,
      options: Options(
        headers: {'Authorization': 'Bearer $token'},
      ),
    );

    return (res.data ?? <String, dynamic>{}).cast<String, dynamic>();
  }
}
