import 'package:shared_preferences/shared_preferences.dart';

class ProfileDataService {
  static const _addressesKey = 'saved_addresses_v1';
  static const _cardsKey = 'saved_cards_v1';

  Future<String> _scopeKey(String baseKey) async {
    final prefs = await SharedPreferences.getInstance();
    final userId = prefs.getInt('user_id');
    final userEmail = (prefs.getString('user_email') ?? '').trim().toLowerCase();
    final suffix = userId != null && userId > 0
        ? 'u$userId'
        : (userEmail.isNotEmpty ? userEmail : 'guest');
    return '$baseKey::$suffix';
  }

  Future<List<String>> getAddresses() async {
    final prefs = await SharedPreferences.getInstance();
    final key = await _scopeKey(_addressesKey);
    final raw = prefs.getStringList(key) ?? <String>[];
    return raw;
  }

  Future<void> addAddress(String value) async {
    final prefs = await SharedPreferences.getInstance();
    final key = await _scopeKey(_addressesKey);
    final list = prefs.getStringList(key) ?? <String>[];
    list.add(value);
    await prefs.setStringList(key, list);
  }

  Future<void> removeAddressAt(int index) async {
    final prefs = await SharedPreferences.getInstance();
    final key = await _scopeKey(_addressesKey);
    final list = prefs.getStringList(key) ?? <String>[];
    if (index < 0 || index >= list.length) return;
    list.removeAt(index);
    await prefs.setStringList(key, list);
  }

  Future<List<String>> getCards() async {
    final prefs = await SharedPreferences.getInstance();
    final key = await _scopeKey(_cardsKey);
    return prefs.getStringList(key) ?? <String>[];
  }

  Future<void> addCard(String value) async {
    final prefs = await SharedPreferences.getInstance();
    final key = await _scopeKey(_cardsKey);
    final list = prefs.getStringList(key) ?? <String>[];
    list.add(value);
    await prefs.setStringList(key, list);
  }

  Future<void> removeCardAt(int index) async {
    final prefs = await SharedPreferences.getInstance();
    final key = await _scopeKey(_cardsKey);
    final list = prefs.getStringList(key) ?? <String>[];
    if (index < 0 || index >= list.length) return;
    list.removeAt(index);
    await prefs.setStringList(key, list);
  }
}
