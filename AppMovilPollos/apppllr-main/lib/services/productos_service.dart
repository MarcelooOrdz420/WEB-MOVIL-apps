import '../models/producto.dart';
import 'api_client.dart';

class ProductosService {
  Future<List<Producto>> listar() async {
    final res = await ApiClient.get<List<dynamic>>('/products');
    final list = (res.data ?? <dynamic>[]).cast<dynamic>();
    return list
        .map((e) => Producto.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<Producto> obtener(int id) async {
    final res = await ApiClient.get<Map<String, dynamic>>('/products/$id');
    return Producto.fromJson((res.data ?? <String, dynamic>{}));
  }
}
