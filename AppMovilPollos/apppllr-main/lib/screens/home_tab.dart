import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../models/producto.dart';
import '../services/productos_service.dart';
import '../services/session_service.dart';
import '../state/cart_controller.dart';
import '../widgets/producto_image.dart';

class HomeTab extends StatefulWidget {
  const HomeTab({super.key});

  @override
  State<HomeTab> createState() => _HomeTabState();
}

class _HomeTabState extends State<HomeTab> {
  late Future<List<Producto>> _future;
  final TextEditingController _searchCtrl = TextEditingController();
  String _userName = 'Invitado';
  bool _logged = false;

  @override
  void initState() {
    super.initState();
    _future = ProductosService().listar();
    _loadSession();
  }

  Future<void> _loadSession() async {
    final name = await SessionService().getUserName();
    final logged = await SessionService().isLoggedIn();
    if (!mounted) return;
    setState(() {
      _userName = name;
      _logged = logged;
    });
  }

  List<Producto> _searchProducts(List<Producto> all) {
    final query = _searchCtrl.text.trim().toLowerCase();
    if (query.isEmpty) return const [];
    return all.where((p) {
      final name = p.name.toLowerCase();
      final cat = p.categoria.toLowerCase();
      return name.contains(query) || cat.contains(query);
    }).toList();
  }

  Future<void> _addToCart(BuildContext context, Producto p) async {
    final logged = await SessionService().isLoggedIn();
    if (!logged) {
      if (!context.mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Debes iniciar sesion para comprar.')),
      );
      context.go('/correo');
      return;
    }

    final cart = CartScope.of(context);
    cart.add(p);
    if (!context.mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('${p.name} agregado al carrito')),
    );
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: FutureBuilder<List<Producto>>(
        future: _future,
        builder: (context, snap) {
          if (snap.connectionState != ConnectionState.done) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snap.hasError) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(Icons.wifi_off_rounded, size: 54, color: Colors.orange),
                    const SizedBox(height: 12),
                    const Text(
                      'No se pudo cargar el menu.\nVerifica que Laravel este activo en 127.0.0.1:8000.',
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 10),
                    Text('${snap.error}', textAlign: TextAlign.center),
                  ],
                ),
              ),
            );
          }

          final products = snap.data!;
          final results = _searchProducts(products);
          final pollos = products.where((p) => p.categoria.toLowerCase() == 'pollos').toList();
          final parrillas = products.where((p) => p.categoria.toLowerCase() == 'parrillas').toList();
          final bebidas = products.where((p) => p.categoria.toLowerCase() == 'bebidas').toList();

          return ListView(
            padding: const EdgeInsets.all(12),
            children: [
              Row(
                children: [
                  const CircleAvatar(
                    radius: 16,
                    backgroundColor: Colors.black12,
                    child: Icon(Icons.storefront, size: 18),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Pollos y Parrillas "EL DORADO"', style: TextStyle(fontWeight: FontWeight.bold)),
                        Text('Hola, $_userName', style: const TextStyle(fontSize: 12, color: Colors.black54)),
                      ],
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.search),
                    onPressed: () => setState(() {}),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              _heroCard(
                title: 'Sabor a brasa real',
                subtitle: 'Porciones personales y para dos.',
                product: pollos.isNotEmpty ? pollos.first : null,
              ),
              const SizedBox(height: 10),
              _heroCard(
                title: 'Combos familiares',
                subtitle: 'Platos generosos para compartir.',
                product: pollos.length > 1 ? pollos[1] : (pollos.isNotEmpty ? pollos.first : null),
              ),
              const SizedBox(height: 10),
              _heroCard(
                title: 'Bebidas heladas',
                subtitle: 'Refrescos para completar tu pedido.',
                product: bebidas.isNotEmpty ? bebidas.first : (parrillas.isNotEmpty ? parrillas.first : null),
              ),
              const SizedBox(height: 14),
              TextField(
                controller: _searchCtrl,
                onChanged: (_) => setState(() {}),
                decoration: InputDecoration(
                  hintText: 'Busca productos...',
                  prefixIcon: const Icon(Icons.search),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(14)),
                  filled: true,
                  fillColor: Colors.white,
                ),
              ),
              const SizedBox(height: 12),
              if (_searchCtrl.text.trim().isEmpty)
                const Card(
                  child: Padding(
                    padding: EdgeInsets.all(16),
                    child: Text(
                      'Solo mostramos las 3 imagenes promocionales al inicio. Usa el buscador para ver productos.',
                    ),
                  ),
                ),
              if (_searchCtrl.text.trim().isNotEmpty && results.isEmpty)
                const Card(
                  child: Padding(
                    padding: EdgeInsets.all(16),
                    child: Text('No hay productos con esa busqueda.'),
                  ),
                ),
              if (results.isNotEmpty)
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 12,
                    mainAxisSpacing: 12,
                    childAspectRatio: 0.76,
                  ),
                  itemCount: results.length,
                  itemBuilder: (context, i) {
                    final p = results[i];
                    return _ProductCard(
                      p: p,
                      onOpen: () => context.push('/detalles/${p.id}'),
                      onAdd: () => _addToCart(context, p),
                    );
                  },
                ),
              if (!_logged)
                const Padding(
                  padding: EdgeInsets.only(top: 12),
                  child: Text(
                    'Puedes explorar productos, pero debes iniciar sesion para comprar.',
                    style: TextStyle(color: Colors.black54),
                    textAlign: TextAlign.center,
                  ),
                ),
            ],
          );
        },
      ),
    );
  }

  Widget _heroCard({
    required String title,
    required String subtitle,
    required Producto? product,
  }) {
    return Container(
      height: 170,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(18),
        color: Colors.black,
      ),
      clipBehavior: Clip.antiAlias,
      child: Stack(
        fit: StackFit.expand,
        children: [
          if (product != null) ProductoImage(producto: product, width: double.infinity, height: 170),
          Container(color: Colors.black.withOpacity(0.35)),
          Positioned(
            left: 14,
            right: 14,
            bottom: 14,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 20)),
                const SizedBox(height: 4),
                Text(subtitle, style: const TextStyle(color: Colors.white70)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _ProductCard extends StatelessWidget {
  final Producto p;
  final VoidCallback onOpen;
  final VoidCallback onAdd;

  const _ProductCard({required this.p, required this.onOpen, required this.onAdd});

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onOpen,
      child: Card(
        elevation: 3,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(14)),
              child: ProductoImage(
                producto: p,
                height: 115,
                width: double.infinity,
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(10, 8, 10, 0),
              child: Text(
                p.name,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(10, 4, 10, 0),
              child: Text(
                'S/. ${p.price.toStringAsFixed(2)}',
                style: const TextStyle(color: Colors.orange, fontWeight: FontWeight.w600),
              ),
            ),
            const Spacer(),
            Row(
              children: [
                const SizedBox(width: 8),
                TextButton(onPressed: onOpen, child: const Text('Inspeccionar')),
                const Spacer(),
                Padding(
                  padding: const EdgeInsets.all(8),
                  child: InkWell(
                    onTap: onAdd,
                    child: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.orange,
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.add_shopping_cart, color: Colors.white, size: 18),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
