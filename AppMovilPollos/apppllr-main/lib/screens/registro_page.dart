import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../services/auth_service.dart';

class RegistroPage extends StatefulWidget {
  const RegistroPage({super.key});

  @override
  State<RegistroPage> createState() => _RegistroPageState();
}

class _RegistroPageState extends State<RegistroPage> {
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _correoController = TextEditingController();
  final _passwordController = TextEditingController();

  bool _loading = false;
  bool _obscure = true;

  bool _isEmailValid(String email) {
    // validaciÃ³n simple y suficiente
    final r = RegExp(r'^[^@\s]+@[^@\s]+\.[^@\s]+$');
    return r.hasMatch(email);
  }

  String _cleanError(Object e) {
    return e.toString().replaceFirst('Exception: ', '').trim();
  }

  Future<void> _doRegister() async {
    final email = _correoController.text.trim();
    final name = _nameController.text.trim().isEmpty
        ? (email.isEmpty ? 'Cliente El Dorado' : email.split('@').first)
        : _nameController.text.trim();
    final pass = _passwordController.text.trim();

    if (email.isEmpty || pass.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Completa correo y contraseÃ±a')),
      );
      return;
    }

    if (!_isEmailValid(email)) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Correo invÃ¡lido')),
      );
      return;
    }

    if (pass.length < 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('La contraseÃ±a debe tener mÃ­nimo 6 caracteres')),
      );
      return;
    }

    setState(() => _loading = true);
    try {
      // âœ… registrar
      await AuthService().register(
        email: email,
        password: pass,
        name: name,
        phone: _phoneController.text.trim(),
      );

      // âœ… auto-login para guardar token
      await AuthService().login(email: email, password: pass);

      if (!mounted) return;
      context.go('/app');
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(_cleanError(e))),
      );
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _correoController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Registro'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: _loading ? null : () => context.go("/"),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(
              controller: _nameController,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(
                labelText: 'Nombre',
                prefixIcon: Icon(Icons.person),
              ),
              enabled: !_loading,
            ),
            const SizedBox(height: 12),

            TextField(
              controller: _phoneController,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(
                labelText: 'Telefono (opcional)',
                prefixIcon: Icon(Icons.phone),
              ),
              enabled: !_loading,
            ),
            const SizedBox(height: 12),

            TextField(
              controller: _correoController,
              keyboardType: TextInputType.emailAddress,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(
                labelText: 'Correo electrÃ³nico',
                prefixIcon: Icon(Icons.email),
              ),
              enabled: !_loading,
            ),
            const SizedBox(height: 12),

            TextField(
              controller: _passwordController,
              obscureText: _obscure,
              textInputAction: TextInputAction.done,
              onSubmitted: (_) => _loading ? null : _doRegister(),
              decoration: InputDecoration(
                labelText: 'ContraseÃ±a (mÃ­n. 6)',
                prefixIcon: const Icon(Icons.lock),
                suffixIcon: IconButton(
                  onPressed: _loading
                      ? null
                      : () => setState(() => _obscure = !_obscure),
                  icon: Icon(_obscure ? Icons.visibility : Icons.visibility_off),
                ),
              ),
              enabled: !_loading,
            ),

            const SizedBox(height: 20),

            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _loading ? null : _doRegister,
                child: _loading
                    ? const SizedBox(
                        height: 18,
                        width: 18,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      )
                    : const Text('Registrarme'),
              ),
            ),

            const SizedBox(height: 12),
            TextButton(
              onPressed: _loading ? null : () => context.go('/correo'),
              child: const Text('Ya tengo cuenta, iniciar sesion'),
            ),
          ],
        ),
      ),
    );
  }
}

