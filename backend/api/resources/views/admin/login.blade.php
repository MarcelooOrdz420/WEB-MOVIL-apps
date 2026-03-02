<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <title>Pollos y Parrillas El Dorado - Login Admin</title>
    <style>
        :root { --black:#111214; --orange:#ff6f1f; --orange-soft:#ff9f62; --text:#f1f1f5; --line:#32333a; }
        * { box-sizing:border-box; }
        body {
            margin:0;
            min-height:100vh;
            display:grid;
            place-items:center;
            font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top, #2a170a 0%, #18191f 45%, #111214 100%);
            color:var(--text);
            padding:16px;
        }
        .card {
            width:100%;
            max-width:420px;
            background:#171820;
            border:1px solid var(--line);
            border-top:4px solid var(--orange);
            border-radius:14px;
            padding:22px;
        }
        h1 { margin:0 0 8px; color:var(--orange); }
        p { margin:0 0 14px; font-size:14px; opacity:.85; }
        label { display:block; margin-bottom:6px; font-size:13px; }
        input {
            width:100%;
            background:#101116;
            border:1px solid #343641;
            color:#fff;
            border-radius:9px;
            padding:11px;
            margin-bottom:10px;
        }
        button {
            width:100%;
            border:0;
            border-radius:9px;
            padding:12px;
            cursor:pointer;
            font-weight:800;
            color:#2e1608;
            background:linear-gradient(120deg,var(--orange),var(--orange-soft));
        }
        .msg { min-height:20px; margin-top:10px; font-size:13px; color:#ffc8a1; }
        .back { margin-top:12px; text-align:center; }
        .back a { color:#ffb889; }
    </style>
</head>
<body>
<main class="card">
    <h1>Admin Pollos y Parrillas "El Dorado"</h1>
    <p>Acceso exclusivo para administradores.</p>

    <form id="adminLoginForm">
        <label for="email">Correo</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Contrasena</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Ingresar al panel</button>
    </form>

    <div id="msg" class="msg"></div>

    <div class="back"><a href="/productos">Volver al sitio</a></div>
</main>

<script>
const form = document.getElementById('adminLoginForm');
const msg = document.getElementById('msg');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    msg.textContent = 'Validando...';

    const payload = {
        email: form.email.value.trim(),
        password: form.password.value,
    };

    try {
        const res = await fetch('/api/v1/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (!res.ok) {
            msg.textContent = data.message || 'Credenciales invalidas';
            return;
        }

        if (!data.user || data.user.role !== 'admin') {
            msg.textContent = 'Este usuario no tiene permisos de administrador.';
            return;
        }

        localStorage.removeItem('ed_cart');
        localStorage.removeItem('ed_last_tracking');
        localStorage.removeItem('ed_recent_trackings');
        localStorage.setItem('ed_token', data.token);
        localStorage.setItem('ed_user', JSON.stringify(data.user));
        localStorage.setItem('ed_session', JSON.stringify({
            role: 'admin',
            lastActivity: Date.now(),
            expiresAt: Date.now() + (30 * 60 * 1000),
        }));
        window.location.href = '/admin/panel';
    } catch {
        msg.textContent = 'Error de conexion.';
    }
});
</script>
</body>
</html>
