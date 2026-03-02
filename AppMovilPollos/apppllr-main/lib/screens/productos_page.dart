import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../models/producto.dart';
import '../services/productos_service.dart';
import '../services/auth_service.dart';
import '../services/session_service.dart';
import '../widgets/producto_image.dart';

class ProductosPage extends StatefulWidget {
  const ProductosPage({super.key});

  @override
  State<ProductosPage> createState() => _ProductosPageState();
}

class _ProductosPageState extends State<ProductosPage> {
  late Future<List<Producto>> _future;

  final _session = SessionService();
  bool _isLoggedIn = false;
  String _userName = 'Invitado';

  @override
  void initState() {
    super.initState();
    _future = ProductosService().listar();
    _loadUser();
  }

  Future<void> _loadUser() async {
    final logged = await _session.isLoggedIn();
    final name = await _session.getUserName();
    if (!mounted) return;
    setState(() {
      _isLoggedIn = logged;
      _userName = name;
    });
  }

  Future<void> _refresh() async {
    setState(() {
      _future = ProductosService().listar();
    });
    await _loadUser();
  }

  Future<void> _doLogout() async {
    await AuthService().logout();
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Sesión cerrada')),
    );
    context.go('/');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.orange,
        titleSpacing: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Pollería "EL DORADO"', style: TextStyle(fontSize: 16)),
            Text(
              'Hola, $_userName',
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500),
            ),
          ],
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => context.go('/'),
        ),
        actions: [
          PopupMenuButton<String>(
            icon: const Icon(Icons.person),
            onSelected: (value) {
              if (value == 'logout') _doLogout();
              if (value == 'login') context.go('/');
              if (value == 'perfil') context.go('/perfil'); // si tienes perfil route
            },
            itemBuilder: (context) {
              if (_isLoggedIn) {
                return const [
                  PopupMenuItem(
                    value: 'perfil',
                    child: Row(
                      children: [
                        Icon(Icons.account_circle, size: 18),
                        SizedBox(width: 8),
                        Text('Mi perfil'),
                      ],
                    ),
                  ),
                  PopupMenuItem(
                    value: 'logout',
                    child: Row(
                      children: [
                        Icon(Icons.logout, size: 18),
                        SizedBox(width: 8),
                        Text('Cerrar sesión'),
                      ],
                    ),
                  ),
                ];
              }
              return const [
                PopupMenuItem(
                  value: 'login',
                  child: Row(
                    children: [
                      Icon(Icons.login, size: 18),
                      SizedBox(width: 8),
                      Text('Iniciar sesión'),
                    ],
                  ),
                ),
              ];
            },
          ),
        ],
      ),

      body: FutureBuilder<List<Producto>>(
        future: _future,
        builder: (context, snap) {
          if (snap.connectionState != ConnectionState.done) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snap.hasError) {
            return RefreshIndicator(
              onRefresh: _refresh,
              child: ListView(
                physics: const AlwaysScrollableScrollPhysics(),
                children: [
                  const SizedBox(height: 80),
                  Center(child: Text('Error: ${snap.error}')),
                  const SizedBox(height: 16),
                  const Center(child: Text('Desliza hacia abajo para reintentar')),
                ],
              ),
            );
          }

          final productos = snap.data!;

          return RefreshIndicator(
            onRefresh: _refresh,
            child: SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // ✅ Chip estilo "estado de sesión" (como apps reales)
                    Align(
                      alignment: Alignment.centerLeft,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                        decoration: BoxDecoration(
                          color: _isLoggedIn ? Colors.green.shade50 : Colors.grey.shade200,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(
                              _isLoggedIn ? Icons.verified_user : Icons.person_outline,
                              size: 16,
                              color: _isLoggedIn ? Colors.green : Colors.black54,
                            ),
                            const SizedBox(width: 6),
                            Text(
                              _isLoggedIn ? 'Sesión: $_userName' : 'Modo: Invitado',
                              style: const TextStyle(fontSize: 12),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),

                    // 1) Entrega a domicilio
                    Container(
                      padding: const EdgeInsets.all(10),
                      color: Colors.orange.shade50,
                      child: Row(
                        children: [
                          Icon(Icons.delivery_dining, color: Colors.orange),
                          const SizedBox(width: 10),
                          const Expanded(
                            child: Text(
                              'Entrega a domicilio: Av. Principal 123, Lima\n15-25 minutos Aprox',
                              style: TextStyle(fontSize: 16),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // 2) Descuento
                    Container(
                      padding: const EdgeInsets.all(12),
                      color: Colors.orange.shade100,
                      child: Row(
                        children: [
                          const Icon(Icons.local_offer, color: Colors.red),
                          const SizedBox(width: 10),
                          const Expanded(
                            child: Text(
                              '¡Descuento de 20%! En combos familiares. Válido hasta medianoche.',
                              style: TextStyle(fontSize: 16),
                            ),
                          ),
                          TextButton(
                            onPressed: () {},
                            child: const Text('Ver Oferta', style: TextStyle(color: Colors.orange)),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // 3) Categorías
                    const Text('Categorías', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 10),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: const [
                        CategoryIcon(icon: Icons.fastfood, label: 'Pollos'),
                        CategoryIcon(icon: Icons.fastfood, label: 'Combos'),
                        CategoryIcon(icon: Icons.food_bank, label: 'Acompañamientos'),
                        CategoryIcon(icon: Icons.local_drink, label: 'Bebidas'),
                      ],
                    ),
                    const SizedBox(height: 20),

                    // 4) Mayor demanda (API)
                    const Text('Mayor demanda', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 10),

                    GridView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        crossAxisSpacing: 12,
                        mainAxisSpacing: 12,
                        childAspectRatio: 0.82,
                      ),
                      itemCount: productos.length,
                      itemBuilder: (context, index) {
                        final p = productos[index];
                        return PopularProductCard(
                          producto: p,
                          name: p.name,
                          price: p.price.toStringAsFixed(2),
                          description: p.description,
                          onTap: () => context.push('/detalles/${p.id}'),
                        );
                      },
                    ),
                  ],
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}

class CategoryIcon extends StatelessWidget {
  final IconData icon;
  final String label;

  const CategoryIcon({super.key, required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        CircleAvatar(
          radius: 30,
          backgroundColor: Colors.orangeAccent.shade100,
          child: Icon(icon, size: 30, color: Colors.orange),
        ),
        const SizedBox(height: 8),
        Text(label),
      ],
    );
  }
}

class PopularProductCard extends StatelessWidget {
  final Producto producto;
  final String name;
  final String price;
  final String description;
  final VoidCallback onTap;

  const PopularProductCard({
    super.key,
    required this.producto,
    required this.name,
    required this.price,
    required this.description,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        elevation: 4,
        child: Column(
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(12),
                topRight: Radius.circular(12),
              ),
              child: ProductoImage(
                producto: producto,
                height: 120,
                width: double.infinity,
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(8),
              child: Text(name, style: const TextStyle(fontWeight: FontWeight.bold)),
            ),
            Padding(
              padding: const EdgeInsets.all(8),
              child: Text('S/ $price', style: const TextStyle(color: Colors.orange)),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 8),
              child: Text(
                description,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(color: Colors.black54),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
