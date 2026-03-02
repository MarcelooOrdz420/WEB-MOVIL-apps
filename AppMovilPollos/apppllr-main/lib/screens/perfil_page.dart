import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../services/session_service.dart';
import '../services/auth_service.dart';

class PerfilPage extends StatefulWidget {
  const PerfilPage({super.key});

  @override
  State<PerfilPage> createState() => _PerfilPageState();
}

class _PerfilPageState extends State<PerfilPage> {
  final _session = SessionService();
  String _name = 'Invitado';
  String _email = '';
  bool _logged = false;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    final logged = await _session.isLoggedIn();
    final name = await _session.getUserName();
    final email = await _session.getUserEmail();
    if (!mounted) return;
    setState(() {
      _logged = logged;
      _name = name;
      _email = email;
    });
  }

  Future<void> _logout() async {
    await AuthService().logout();
    if (!mounted) return;
    context.go('/');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Perfil')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            CircleAvatar(
              radius: 42,
              child: Text(_name.isNotEmpty ? _name[0].toUpperCase() : 'U'),
            ),
            const SizedBox(height: 12),
            Text(_name, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 4),
            Text(_email.isEmpty ? '—' : _email, style: const TextStyle(color: Colors.black54)),
            const SizedBox(height: 24),

            ListTile(
              leading: const Icon(Icons.history),
              title: const Text('Historial de pedidos'),
              onTap: () {},
            ),
            const Divider(),
            ListTile(
              leading: const Icon(Icons.logout),
              title: Text(_logged ? 'Cerrar sesión' : 'Iniciar sesión'),
              onTap: _logged ? _logout : () => context.go('/'),
            ),
          ],
        ),
      ),
    );
  }
}
