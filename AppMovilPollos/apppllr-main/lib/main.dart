import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import 'screens/my_home_page.dart';
import 'screens/invitado_page.dart';
import 'screens/correo_page.dart';
import 'screens/registro_page.dart';
import 'screens/app_shell.dart';
import 'screens/search_page.dart';
import 'screens/detalles_page_api.dart';
import 'screens/payment_page.dart';
import 'screens/order_confirmed_page.dart';
import 'screens/chat_bot_page.dart';

import 'state/cart_controller.dart';
import 'state/orders_controller.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await _orders.load();
  runApp(const MyApp());
}

final _cart = CartController();
final _orders = OrdersController();

final GoRouter _router = GoRouter(
  routes: [

    // Parte externa de la app
    GoRoute(
      path: '/',
      builder: (context, state) => const MyHomePage(title: ''),
    ),

    // Loggeo de cliente
    GoRoute(
      path: '/correo',
      builder: (context, state) => const LoginCorreoPage(),
    ),

    // Registro de cliente
    GoRoute(
      path: '/registro',
      builder: (context, state) => const RegistroPage(),
    ),

    // Visita de persona sin registro
    GoRoute(
      path: '/invitado',
      builder: (context, state) => const InvitadoPage(),
    ),

    // APP PRINCIPAL (Bottom Navigation)
    GoRoute(
      path: '/app',
      builder: (context, state) {
        final tab = int.tryParse(state.uri.queryParameters['tab'] ?? '0') ?? 0;
        return AppShell(initialIndex: tab);
      },
    ),

    // BUSCAR
    GoRoute(
      path: '/buscar',
      builder: (context, state) => const SearchPage(),
    ),

    // DETALLE
    GoRoute(
      path: '/detalles/:id',
      builder: (context, state) {
        final id = int.parse(state.pathParameters['id']!);
        return DetallesPageApi(productId: id);
      },
    ),

    // PAGO
    GoRoute(
      path: '/pago',
      builder: (context, state) => const PaymentPage(),
    ),

    // CONFIRMACION
    GoRoute(
      path: '/confirmacion',
      builder: (context, state) {
        final extra = state.extra;
        final order = extra is Map<String, dynamic> ? extra : null;
        return OrderConfirmedPage(serverOrder: order);
      },
    ),

    // CHATBOT
    GoRoute(
      path: '/chat',
      builder: (context, state) => const ChatBotPage(),
    ),
  ],
);

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return CartScope(
      controller: _cart,
      child: OrdersScope(
        controller: _orders,
        child: MaterialApp.router(
          debugShowCheckedModeBanner: false,
          routerConfig: _router,
        ),
      ),
    );
  }
}
