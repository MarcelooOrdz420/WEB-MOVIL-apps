const express = require("express");
const cors = require("cors");

const app = express();
app.use(express.json());

// ✅ CORS (para Flutter Web / otros)
app.use(
  cors({
    origin: true,
    credentials: true,
  })
);

const users = []; 
const productos = [
  // ===== PLATILLOS =====
  {
    id: 1,
    name: "Pollo Entero",
    price: 45.0,
    description: "Delicioso pollo a la brasa con papas y ensalada.",
    image: "pollo_entero.png",
    categoria: "Platillos"
  },
  {
    id: 2,
    name: "Cuarto Pollo",
    price: 25.0,
    description: "Cuarto de pollo acompañado de papas fritas.",
    image: "cuarto_pollo.png",
    categoria: "Platillos"
  },
  {
    id: 3,
    name: "Medio Pollo",
    price: 30.0,
    description: "Medio pollo acompañado de cremas y papas.",
    image: "medio_pollo.png",
    categoria: "Platillos"
  },

  // ===== COMBOS =====
  {
    id: 4,
    name: "Combo Familiar",
    price: 65.0,
    description: "1 pollo + papas + gaseosa 1.5L.",
    image: "combo_familiar.png",
    categoria: "Combos"
  },
  {
    id: 5,
    name: "Combo Personal",
    price: 14.0,
    description: "1/4 pollo + papas + gaseosa 500ml.",
    image: "combo_personal.png",
    categoria: "Combos"
  },

  // ===== ENSALADAS =====
  {
    id: 6,
    name: "Ensalada Fresca",
    price: 12.0,
    description: "Lechuga, tomate, cebolla y aderezo especial.",
    image: "ensalada_fresca.png",
    categoria: "Ensaladas"
  },
  {
    id: 7,
    name: "Ensalada César",
    price: 18.0,
    description: "Lechuga romana con pollo, crutones y queso parmesano.",
    image: "ensalada_cesar.png",
    categoria: "Ensaladas"
  },

  // ===== BEBIDAS =====
  {
    id: 8,
    name: "Gaseosa Inca Kola 1.5L",
    price: 12.0,
    description: "Gaseosa familiar 1.5 litros.",
    image: "inca_kola.png",
    categoria: "Bebidas"
  },
  {
    id: 9,
    name: "Coca Cola 1.5L",
    price: 12.0,
    description: "Gaseosa Coca Cola familiar.",
    image: "coca_cola.png",
    categoria: "Bebidas"
  },
  {
    id: 10,
    name: "Chicha Morada",
    price: 10.0,
    description: "Bebida tradicional peruana.",
    image: "chicha.png",
    categoria: "Bebidas"
  }
];



// ====== AUTH ======
app.post("/api/auth/register", (req, res) => {
  const { email, password, name = "" } = req.body || {};
  if (!email || !password)
    return res.status(400).json({ message: "Email y password son obligatorios" });

  const exists = users.find((u) => u.email === email);
  if (exists) return res.status(409).json({ message: "El correo ya está registrado" });

  const newUser = { id: users.length + 1, email, password, name };
  users.push(newUser);

  return res.json({ message: "Registrado OK", user: { id: newUser.id, email, name } });
});

app.post("/api/auth/login", (req, res) => {
  const { email, password } = req.body || {};
  if (!email || !password)
    return res.status(400).json({ message: "Email y password son obligatorios" });

  const user = users.find((u) => u.email === email && u.password === password);
  if (!user) return res.status(401).json({ message: "Credenciales incorrectas" });

  const token = `token-demo-${user.id}-${Date.now()}`;

  return res.json({
    token,
    user: { id: user.id, email: user.email, name: user.name },
  });
});

// ====== PRODUCTOS ======
app.get("/api/productos", (req, res) => {
  const { categoria } = req.query;

  if (categoria) {
    const filtrados = productos.filter(
      (p) => p.categoria.toLowerCase() === categoria.toLowerCase()
    );
    return res.json(filtrados);
  }

  return res.json(productos);
});

app.get("/api/productos/:id", (req, res) => {
  const id = Number(req.params.id);
  const p = productos.find((x) => x.id === id);
  if (!p) return res.status(404).json({ message: "Producto no encontrado" });
  return res.json(p);
});

app.get("/", (req, res) => res.send("API OK ✅"));

const PORT = 3000;
app.listen(PORT, () => console.log(`API corriendo en http://localhost:${PORT}`));
