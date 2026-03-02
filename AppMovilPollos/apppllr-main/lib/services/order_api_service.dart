import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'api_client.dart';

class OrderApiService {
  Future<List<Map<String, dynamic>>> myOrders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = (prefs.getString('token') ?? '').trim();
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
}
