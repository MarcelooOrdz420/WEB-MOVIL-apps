import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LoginFacebookPage extends StatefulWidget {
  const LoginFacebookPage({super.key});

  @override
  State<LoginFacebookPage> createState() => _LoginFacebookPageState();
}

class _LoginFacebookPageState extends State<LoginFacebookPage> {

  @override
  void initState() {
    super.initState();
    _fakeFacebookLogin();
  }

  Future<void> _fakeFacebookLogin() async {
    final prefs = await SharedPreferences.getInstance();

    // 🔥 Simulamos token Facebook
    await prefs.setString('token', 'facebook-demo-token');

    await prefs.setString('user_name', 'Usuario Facebook');
    await prefs.setString('user_email', 'facebook@demo.com');

    if (!mounted) return;
    context.go('/app');
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(
        child: CircularProgressIndicator(),
      ),
    );
  }
}
