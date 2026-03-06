import '../config/api_config.dart';

class Producto {
  final int id;
  final String name;
  final double price;
  final String description;
  final String image;
  final String categoria;
  final double rating;
  final int stock;

  Producto({
    required this.id,
    required this.name,
    required this.price,
    required this.description,
    required this.image,
    required this.categoria,
    this.rating = 4.5,
    this.stock = 10,
  });

  factory Producto.fromJson(Map<String, dynamic> json) {
    final rawPrice = json['price'];
    final parsedPrice = rawPrice is num
        ? rawPrice.toDouble()
        : double.tryParse((rawPrice ?? '0').toString()) ?? 0.0;
    final rawRating = json['rating'];
    final parsedRating = rawRating is num
        ? rawRating.toDouble()
        : double.tryParse((rawRating ?? '4.5').toString()) ?? 4.5;
    final rawStock = json['stock'];
    final parsedStock = rawStock is num
        ? rawStock.toInt()
        : int.tryParse((rawStock ?? '10').toString()) ?? 10;

    return Producto(
      id: (json['id'] as num).toInt(),
      name: (json['name'] ?? '').toString(),
      price: parsedPrice,
      description: (json['description'] ?? '').toString(),
      image: (json['image_url'] ?? json['image'] ?? '').toString(),
      categoria: (json['category'] ?? json['categoria'] ?? 'pollos').toString(),
      rating: parsedRating,
      stock: parsedStock,
    );
  }

  bool get isNetworkImage =>
      image.startsWith('http://') ||
      image.startsWith('https://') ||
      image.startsWith('/') ||
      image.startsWith('images/');

  String get resolvedImage =>
      isNetworkImage ? ApiConfig.resolveUrl(image) : 'assets/$image';
}
