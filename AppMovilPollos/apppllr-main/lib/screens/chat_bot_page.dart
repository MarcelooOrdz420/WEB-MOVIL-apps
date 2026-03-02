import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class ChatBotPage extends StatefulWidget {
  const ChatBotPage({super.key});

  @override
  State<ChatBotPage> createState() => _ChatBotPageState();
}

class _ChatBotPageState extends State<ChatBotPage> {
  final List<_ChatMessage> _messages = [
    const _ChatMessage(
      role: _ChatRole.bot,
      text: 'Hola, soy POLL-IA. Tu pollo de confianza para ayudarte con tus preguntas sobre nuestros productos, pedidos, pagos y delivery. ¿En qué puedo ayudarte hoy?',
    ),
  ];

  final TextEditingController _controller = TextEditingController();
  final ScrollController _scrollController = ScrollController();

  static const List<_IntentRule> _rules = [
    _IntentRule(
      keywords: ['producto', 'productos', 'menu', 'carta', 'pollo', 'parrilla', 'bebida'],
      answer:
          'En el menu tenemos categorias de pollos, parrillas y bebidas. Usa la seccion principal para explorar y el buscador para encontrar un producto exacto.',
      chips: ['Ver productos', 'Buscar pollo', 'Ver bebidas'],
    ),
    _IntentRule(
      keywords: ['pedido', 'pedidos', 'seguimiento', 'codigo', 'orden', 'ordenes'],
      answer:
          'Para revisar tu pedido, entra a la pestaña de ordenes o a "Mis pedidos" en la web. Ahi podras ver tu codigo, estado y seguimiento.',
      chips: ['Mis pedidos', 'Seguimiento', 'Estado de pedido'],
    ),
    _IntentRule(
      keywords: ['pago', 'pagos', 'yape', 'plin', 'transferencia', 'contraentrega', 'qr'],
      answer:
          'Aceptamos Yape, Plin, transferencia bancaria y pago contraentrega. En la pantalla de pago veras el QR de Yape/Plin y los datos de transferencia.',
      chips: ['Yape', 'Plin', 'Transferencia'],
    ),
    _IntentRule(
      keywords: ['delivery', 'envio', 'envios', 'direccion', 'ubicacion', 'reparto'],
      answer:
          'Tenemos delivery y recojo en local. Si eliges delivery, completa tu direccion y referencia para que el pedido llegue con precision.',
      chips: ['Delivery', 'Recojo', 'Ubicacion'],
    ),
    _IntentRule(
      keywords: ['horario', 'hora', 'atienden', 'abren', 'cierran'],
      answer:
          'Atendemos todos los dias de 11:00 a. m. a 10:00 p. m. Si necesitas un pedido grande, te conviene hacerlo con anticipacion.',
      chips: ['Horario', 'Pedidos grandes'],
    ),
    _IntentRule(
      keywords: ['contacto', 'telefono', 'llamar', 'numero', 'soporte'],
      answer:
          'Puedes contactarnos por telefono y tambien desde la app. Para pagos por Yape o Plin, revisa los numeros de la empresa en la pantalla de pago.',
      chips: ['Telefono', 'Pago'],
    ),
    _IntentRule(
      keywords: ['hola', 'buenas', 'buenos dias', 'buenas tardes', 'buenas noches'],
      answer:
          'Hola. Dime si necesitas ayuda con productos, pedidos, pagos o delivery.',
      chips: ['Productos', 'Pedidos', 'Pagos'],
    ),
  ];

  @override
  void dispose() {
    _controller.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _sendText([String? preset]) {
    final text = (preset ?? _controller.text).trim();
    if (text.isEmpty) return;

    setState(() {
      _messages.add(_ChatMessage(role: _ChatRole.user, text: text));
    });
    _controller.clear();
    _scrollToBottom();

    final response = _buildReply(text);
    Future.delayed(const Duration(milliseconds: 260), () {
      if (!mounted) return;
      setState(() {
        _messages.add(response);
      });
      _scrollToBottom();
    });
  }

  _ChatMessage _buildReply(String text) {
    final normalized = text.toLowerCase().trim();

    for (final rule in _rules) {
      if (rule.matches(normalized)) {
        return _ChatMessage(
          role: _ChatRole.bot,
          text: rule.answer,
          suggestions: rule.chips,
        );
      }
    }

    return const _ChatMessage(
      role: _ChatRole.bot,
      text:
          'No encontre una respuesta exacta. Prueba preguntando por productos, pedidos, pagos, delivery, horarios o contacto.',
      suggestions: ['Productos', 'Pedidos', 'Pagos', 'Delivery'],
    );
  }

  void _scrollToBottom() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!_scrollController.hasClients) return;
      _scrollController.animateTo(
        _scrollController.position.maxScrollExtent + 80,
        duration: const Duration(milliseconds: 220),
        curve: Curves.easeOut,
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('POLL-IA'),
        backgroundColor: Colors.orange,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => context.pop(),
        ),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              controller: _scrollController,
              padding: const EdgeInsets.all(12),
              itemCount: _messages.length,
              itemBuilder: (context, index) {
                final msg = _messages[index];
                final isUser = msg.role == _ChatRole.user;

                return Align(
                  alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
                  child: Container(
                    margin: const EdgeInsets.symmetric(vertical: 6),
                    padding: const EdgeInsets.all(12),
                    constraints: BoxConstraints(
                      maxWidth: MediaQuery.of(context).size.width * 0.82,
                    ),
                    decoration: BoxDecoration(
                      color: isUser ? Colors.orange : Colors.grey.shade200,
                      borderRadius: BorderRadius.circular(14),
                    ),
                    child: Column(
                      crossAxisAlignment:
                          isUser ? CrossAxisAlignment.end : CrossAxisAlignment.start,
                      children: [
                        Text(
                          msg.text,
                          style: TextStyle(
                            color: isUser ? Colors.white : Colors.black87,
                            height: 1.35,
                          ),
                        ),
                        if (!isUser && msg.suggestions.isNotEmpty) ...[
                          const SizedBox(height: 10),
                          Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: msg.suggestions.map((item) {
                              return ActionChip(
                                label: Text(item),
                                onPressed: () => _sendText(item),
                                backgroundColor: Colors.white,
                                side: const BorderSide(color: Color(0xFFFFC58F)),
                              );
                            }).toList(),
                          ),
                        ],
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          SafeArea(
            top: false,
            child: Padding(
              padding: const EdgeInsets.fromLTRB(8, 8, 8, 10),
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _controller,
                      onSubmitted: (_) => _sendText(),
                      decoration: InputDecoration(
                        hintText: 'Escribe tu mensaje...',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.send, color: Colors.orange),
                    onPressed: _sendText,
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

enum _ChatRole { user, bot }

class _ChatMessage {
  final _ChatRole role;
  final String text;
  final List<String> suggestions;

  const _ChatMessage({
    required this.role,
    required this.text,
    this.suggestions = const [],
  });
}

class _IntentRule {
  final List<String> keywords;
  final String answer;
  final List<String> chips;

  const _IntentRule({
    required this.keywords,
    required this.answer,
    this.chips = const [],
  });

  bool matches(String input) {
    for (final word in keywords) {
      if (input.contains(word)) return true;
    }
    return false;
  }
}
