import 'dart:io' show Platform;
import 'package:flutter/foundation.dart' show kIsWeb;

class ApiConfig {
  static const int port = 8000;
  static const String apiPrefix = '/api/v1';

  static List<String> get origins {
    if (kIsWeb) {
      return [
        'http://127.0.0.1:$port',
        'http://localhost:$port',
      ];
    }

    if (Platform.isAndroid) {
      return ['http://10.0.2.2:$port'];
    }

    return [
      'http://127.0.0.1:$port',
      'http://localhost:$port',
    ];
  }

  static String get origin => origins.first;

  static List<String> get baseUrls =>
      origins.map((base) => '$base$apiPrefix').toList();

  static String get baseUrl => baseUrls.first;

  static String resolveUrl(String? path) {
    final value = (path ?? '').trim();
    if (value.isEmpty) return '$origin/images/products/default.svg';
    if (value.startsWith('http://') || value.startsWith('https://')) return value;
    if (value.startsWith('/')) return '$origin$value';
    return '$origin/$value';
  }
}
