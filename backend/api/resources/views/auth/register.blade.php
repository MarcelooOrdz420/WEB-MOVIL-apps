<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <title>Pollos y Parrillas El Dorado - Registro</title>
    <style>
        :root { --black:#121212; --orange:#ff7a00; --orange-soft:#ff9f4a; --text:#fff; --muted:#bdbdbd; }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; background:radial-gradient(circle at top right,#212121 0%,var(--black) 58%); color:var(--text); padding:16px; }
        .card { width:100%; max-width:460px; background:rgba(0,0,0,.72); border:1px solid #2d2d2d; border-top:4px solid var(--orange); border-radius:14px; padding:24px; box-shadow:0 24px 50px rgba(0,0,0,.45); }
        h1 { margin:0 0 8px; color:var(--orange); }
        p { margin:0 0 18px; color:var(--muted); font-size:14px; }
        label { display:block; font-size:14px; margin-bottom:6px; }
        input { width:100%; border:1px solid #333; background:#171717; color:#fff; padding:12px; border-radius:10px; margin-bottom:14px; }
        button { width:100%; border:0; border-radius:10px; padding:12px; font-weight:700; cursor:pointer; color:#1f1205; background:linear-gradient(135deg,var(--orange),var(--orange-soft)); }
        .footer { margin-top:14px; text-align:center; font-size:14px; }
        a { color:var(--orange-soft); }
        .msg { margin-top:12px; font-size:14px; color:#ffd1a4; min-height:20px; }
    </style>
</head>
<body>
<main class="card">
    <h1>Crear cuenta</h1>
    <p>Registrate para poder comprar en Pollos y Parrillas "El Dorado".</p>

    <form id="registerForm">
        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" required>

        <label for="email">Correo</label>
        <input id="email" name="email" type="email" required>

        <label for="phone">Telefono (opcional)</label>
        <input id="phone" name="phone" type="text">

        <label for="password">Contrasena</label>
        <input id="password" name="password" type="password" required minlength="6">

        <button type="submit">Registrarme</button>
    </form>

    <div id="msg" class="msg"></div>

    <div class="footer">
        Ya tienes cuenta? <a href="/login">Inicia sesion</a><br>
        O visita la <a href="/productos">tienda</a>
    </div>
</main>

<script>
const form = document.getElementById('registerForm');
const msg = document.getElementById('msg');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    msg.textContent = 'Creando cuenta...';

    const payload = {
        name: form.name.value.trim(),
        email: form.email.value.trim(),
        phone: form.phone.value.trim() || null,
        password: form.password.value,
    };

    try {
        const res = await fetch('/api/v1/auth/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (!res.ok) {
            msg.textContent = data.message || 'No se pudo registrar.';
            return;
        }

        localStorage.setItem('ed_token', data.token);
        localStorage.setItem('ed_user', JSON.stringify(data.user));
        localStorage.setItem('ed_session', JSON.stringify({
            role: data.user.role || 'customer',
            lastActivity: Date.now(),
            expiresAt: Date.now() + (60 * 60 * 1000),
        }));
        window.location.href = '/productos';
    } catch {
        msg.textContent = 'No se pudo conectar con el servidor.';
    }
});
</script>
</body>
</html>
