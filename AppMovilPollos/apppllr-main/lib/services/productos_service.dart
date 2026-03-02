import '../models/producto.dart';
import 'api_client.dart';

class ProductosService {
  Future<List<Producto>> listar() async {
    final res = await ApiClient.dio.get('/products');
    final list = (res.data as List).cast<dynamic>();
    return list
        .map((e) => Producto.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<Producto> obtener(int id) async {
    final res = await ApiClient.dio.get('/products/$id');
    return Producto.fromJson(res.data as Map<String, dynamic>);
  }
}
