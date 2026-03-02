import 'package:shared_preferences/shared_preferences.dart';

class SessionService {
  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    return (prefs.getString('token') ?? '').isNotEmpty;
  }

  Future<String> getUserName() async {
    final prefs = await SharedPreferences.getInstance();
    final name = (prefs.getString('user_name') ?? '').trim();
    if (name.isNotEmpty) return name;
    final email = (prefs.getString('user_email') ?? '').trim();
    return email.isNotEmpty ? email : 'Invitado';
  }

  Future<String> getUserEmail() async {
    final prefs = await SharedPreferences.getInstance();
    return (prefs.getString('user_email') ?? '').trim();
  }

  Future<String> getUserRole() async {
    final prefs = await SharedPreferences.getInstance();
    return (prefs.getString('user_role') ?? 'customer').trim();
  }
}
