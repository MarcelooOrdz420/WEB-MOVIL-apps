import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class ProfileDataService {
  static const _addressesKey = 'saved_addresses_v1';
  static const _cardsKey = 'saved_cards_v1';

  Future<List<String>> getAddresses() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getStringList(_addressesKey) ?? <String>[];
    return raw;
  }

  Future<void> addAddress(String value) async {
    final prefs = await SharedPreferences.getInstance();
    final list = prefs.getStringList(_addressesKey) ?? <String>[];
    list.add(value);
    await prefs.setStringList(_addressesKey, list);
  }

  Future<void> removeAddressAt(int index) async {
    final prefs = await SharedPreferences.getInstance();
    final list = prefs.getStringList(_addressesKey) ?? <String>[];
    if (index < 0 || index >= list.length) return;
    list.removeAt(index);
    await prefs.setStringList(_addressesKey, list);
  }

  Future<List<String>> getCards() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getStringList(_cardsKey) ?? <String>[];
  }

  Future<void> addCard(String value) async {
    final prefs = await SharedPreferences.getInstance();
    final list = prefs.getStringList(_cardsKey) ?? <String>[];
    list.add(value);
    await prefs.setStringList(_cardsKey, list);
  }

  Future<void> removeCardAt(int index) async {
    final prefs = await SharedPreferences.getInstance();
    final list = prefs.getStringList(_cardsKey) ?? <String>[];
    if (index < 0 || index >= list.length) return;
    list.removeAt(index);
    await prefs.setStringList(_cardsKey, list);
  }
}
