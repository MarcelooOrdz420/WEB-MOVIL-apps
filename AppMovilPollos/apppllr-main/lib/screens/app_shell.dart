import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import 'home_tab.dart';
import 'orders_tab.dart';
import 'cart_tab.dart';
import 'profile_tab.dart';

class AppShell extends StatefulWidget {
  const AppShell({super.key});

  @override
  State<AppShell> createState() => _AppShellState();
}

class _AppShellState extends State<AppShell> {
  int _index = 0;

  final _pages = const [
    HomeTab(),
    OrdersTab(),
    CartTab(),
    ProfileTab(),
  ];

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
              label: 'Principal'),
          BottomNavigationBarItem(
              icon: Icon(Icons.receipt_long_outlined),
              label: 'Órdenes'),
          BottomNavigationBarItem(
              icon: Icon(Icons.shopping_cart_outlined),
              label: 'Carrito'),
          BottomNavigationBarItem(
              icon: Icon(Icons.person_outline),
              label: 'Perfil'),
        ],
      ),
    );
  }
}
