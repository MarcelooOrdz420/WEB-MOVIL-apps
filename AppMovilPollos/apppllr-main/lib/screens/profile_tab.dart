import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../services/session_service.dart';
import '../services/auth_service.dart';
import '../services/profile_data_service.dart';

class ProfileTab extends StatefulWidget {
  const ProfileTab({super.key});

  @override
  State<ProfileTab> createState() => _ProfileTabState();
}

class _ProfileTabState extends State<ProfileTab> {
  final _session = SessionService();
  final _profileData = ProfileDataService();
  bool _logged = false;
  String _name = 'Invitado';
  String _email = '';
  List<String> _addresses = const [];
  List<String> _cards = const [];

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    final logged = await _session.isLoggedIn();
    final name = await _session.getUserName();
    final email = await _session.getUserEmail();
    final addresses = await _profileData.getAddresses();
    final cards = await _profileData.getCards();
    if (!mounted) return;
    setState(() {
      _logged = logged;
      _name = name;
      _email = email;
      _addresses = addresses;
      _cards = cards;
    });
  }

  Future<void> _logout() async {
    await AuthService().logout();
    if (!mounted) return;
    context.go('/');
  }

  Future<void> _addAddress() async {
    final controller = TextEditingController();
    final value = await showDialog<String>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Agregar direccion'),
        content: TextField(
          controller: controller,
          decoration: const InputDecoration(hintText: 'Ej: Av. Principal 123, Lima'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancelar')),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, controller.text.trim()),
            child: const Text('Guardar'),
          ),
        ],
      ),
    );
    if (value == null || value.isEmpty) return;
    await _profileData.addAddress(value);
    await _load();
  }

  Future<void> _addCard() async {
    final controller = TextEditingController();
    final value = await showDialog<String>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Agregar metodo guardado'),
        content: TextField(
          controller: controller,
          decoration: const InputDecoration(hintText: 'Ej: VISA **** 1234'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancelar')),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, controller.text.trim()),
            child: const Text('Guardar'),
          ),
        ],
      ),
    );
    if (value == null || value.isEmpty) return;
    await _profileData.addCard(value);
    await _load();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: RefreshIndicator(
        onRefresh: _load,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            Center(
              child: Column(
                children: [
                  CircleAvatar(radius: 42, child: Text(_name.isNotEmpty ? _name[0].toUpperCase() : 'U')),
                  const SizedBox(height: 10),
                  Text(_name, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 4),
                  Text(_email.isEmpty ? '-' : _email, style: const TextStyle(color: Colors.black54)),
                ],
              ),
            ),
            const SizedBox(height: 18),
            _section(
              title: 'Direcciones guardadas',
              action: TextButton(onPressed: _addAddress, child: const Text('Agregar')),
              child: _addresses.isEmpty
                  ? const Text('No tienes direcciones guardadas.')
                  : Column(
                      children: List.generate(_addresses.length, (index) {
                        return ListTile(
                          contentPadding: EdgeInsets.zero,
                          leading: const Icon(Icons.location_on_outlined),
                          title: Text(_addresses[index]),
                          trailing: IconButton(
                            icon: const Icon(Icons.delete_outline),
                            onPressed: () async {
                              await _profileData.removeAddressAt(index);
                              await _load();
                            },
                          ),
                        );
                      }),
                    ),
            ),
            _section(
              title: 'Tarjetas o metodos guardados',
              action: TextButton(onPressed: _addCard, child: const Text('Agregar')),
              child: _cards.isEmpty
                  ? const Text('No tienes metodos guardados.')
                  : Column(
                      children: List.generate(_cards.length, (index) {
                        return ListTile(
                          contentPadding: EdgeInsets.zero,
                          leading: const Icon(Icons.payment_outlined),
                          title: Text(_cards[index]),
                          trailing: IconButton(
                            icon: const Icon(Icons.delete_outline),
                            onPressed: () async {
                              await _profileData.removeCardAt(index);
                              await _load();
                            },
                          ),
                        );
                      }),
                    ),
            ),
            Card(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
              child: Column(
                children: [
                  ListTile(
                    leading: const Icon(Icons.history),
                    title: const Text('Historial de pedidos'),
                    onTap: () => context.go('/app'),
                  ),
                  const Divider(height: 1),
                  ListTile(
                    leading: Icon(_logged ? Icons.logout : Icons.login),
                    title: Text(_logged ? 'Cerrar sesion' : 'Iniciar sesion'),
                    onTap: _logged ? _logout : () => context.go('/correo'),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _section({
    required String title,
    required Widget child,
    Widget? action,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(child: Text(title, style: const TextStyle(fontWeight: FontWeight.bold))),
                if (action != null) action,
              ],
            ),
            const SizedBox(height: 8),
            child,
          ],
        ),
      ),
    );
  }
}
