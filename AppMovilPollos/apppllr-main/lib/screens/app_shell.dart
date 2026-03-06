import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import 'home_tab.dart';
import 'orders_tab.dart';
import 'cart_tab.dart';
import 'profile_tab.dart';

class AppShell extends StatefulWidget {
  final int initialIndex;

  const AppShell({super.key, this.initialIndex = 0});

  @override
  State<AppShell> createState() => _AppShellState();
}

class _AppShellState extends State<AppShell> {
  late int _index;

  final _pages = const [
    HomeTab(),
    OrdersTab(),
    CartTab(),
    ProfileTab(),
  ];

  @override
  void initState() {
    super.initState();
    _index = widget.initialIndex.clamp(0, _pages.length - 1);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(
        index: _index,
        children: _pages,
      ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: Colors.white,
        elevation: 6,
        onPressed: () => context.push('/chat'),
        child: const Icon(Icons.smart_toy_outlined, color: Colors.orange),
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _index,
        type: BottomNavigationBarType.fixed,
        selectedItemColor: Colors.orange,
        unselectedItemColor: Colors.grey,
        onTap: (i) => setState(() => _index = i),
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home_outlined),
            label: 'Principal',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.receipt_long_outlined),
            label: 'Ordenes',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.shopping_cart_outlined),
            label: 'Carrito',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person_outline),
            label: 'Perfil',
          ),
        ],
      ),
    );
  }
}
