@extends('store.layout')

@section('title', 'Pollos y Parrillas El Dorado - Productos')

@section('content')

    <h1 class="title">Productos</h1>

    <section class="panel hero-panel">
        <div id="heroSlider" class="hero-strip">
            <div class="hero-card">
                <img id="heroImageA" src="/images/hero/slide-1.jpg" alt="Promo El Dorado 1" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="hero-copy">
                    <h2>Sabor a brasa real</h2>
                    <p>Porciones personales y para dos con el sabor fuerte de la casa.</p>
                </div>
            </div>
            <div class="hero-card">
                <img id="heroImageB" src="/images/hero/slide-2.jpg" alt="Promo El Dorado 2" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="hero-copy">
                    <h2>Combos familiares</h2>
                    <p>Medios, enteros y platillos generosos pensados para compartir.</p>
                </div>
            </div>
            <div class="hero-card">
                <img id="heroImageC" src="/images/hero/slide-3.jpg" alt="Promo El Dorado 3" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="hero-copy">
                    <h2>Bebidas heladas</h2>
                    <p>Gaseosas, chichas y refrescos para completar el pedido.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="grid-auto">
            <div>
                <label for="searchInput" class="label-main">Buscar por nombre</label>
                <input id="searchInput" type="text" class="input-main" placeholder="Ej: pollo, parrilla, chicha...">
            </div>
            <div>
                <label for="categoryInput" class="label-main">Categoria</label>
                <select id="categoryInput" class="select-main">
                    <option value="">Todas</option>
                    <option value="pollos">Pollos</option>
                    <option value="parrillas">Parrillas</option>
                    <option value="bebidas">Bebidas</option>
                </select>
            </div>
            <div>
                <label for="maxPriceInput" class="label-main">Precio maximo (S/)</label>
                <input id="maxPriceInput" type="number" step="0.10" min="0" class="input-main" placeholder="Ej: 40.00">
            </div>
        </div>
        <p id="filterInfo" class="muted-main filter-info">Escribe en el buscador para ver resultados.</p>
        <div id="searchState" class="search-state" style="display:none;">
            <img src="/images/ui/processing-chicken.png" alt="Procesando" class="search-state-image">
            <div>
                <strong id="searchStateTitle">Espera, estamos buscando...</strong>
                <div id="searchStateText" class="muted-main">Filtrando productos para mostrarte el mejor resultado.</div>
            </div>
        </div>
    </section>

    <section id="productsGrid" class="products-grid"></section>

    <div id="productModal" class="modal-wrap">
        <div class="modal-card">
            <img id="modalImage" alt="" class="modal-image">
            <h3 id="modalName" class="modal-title"></h3>
            <p id="modalDesc"></p>
            <p class="modal-price"><strong id="modalPrice"></strong></p>
            <button id="closeModalBtn" class="btn-soft close-dark">Cerrar</button>
        </div>
    </div>

    <style>
        .hero-panel { padding: 0; overflow: hidden; }
        .hero-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            padding: 12px;
            background:
                radial-gradient(circle at top right, rgba(255,111,31,.14), transparent 30%),
                linear-gradient(180deg, #fffaf5 0%, #fff2e6 100%);
        }
        .hero-card {
            position: relative;
            min-height: 240px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(255, 140, 69, .26);
            box-shadow: 0 16px 34px rgba(72, 27, 0, .10);
            background: #1a1a1a;
            isolation: isolate;
        }
        .hero-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,.18) 48%, transparent 72%);
            transform: translateX(-140%);
            pointer-events: none;
            z-index: 1;
        }
        .hero-image {
            width: 100%;
            height: 100%;
            min-height: 240px;
            object-fit: cover;
            display: block;
            transition: transform .45s ease, filter .45s ease;
        }
        .hero-card:hover::after { animation: hero-glow 1s ease; }
        .hero-card:hover .hero-image {
            transform: scale(1.08);
            filter: saturate(1.08) contrast(1.05);
        }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0, 0, 0, .68), rgba(0, 0, 0, .12)); }
        .hero-copy { position: absolute; left: 16px; right: 16px; bottom: 14px; color: #fff; z-index: 2; }
        .hero-copy h2 { margin: 0; font-size: 24px; line-height: 1.05; }
        .hero-copy p { margin: 6px 0 0; font-size: 13px; opacity: .94; max-width: 240px; }

        .filter-info { margin-top: 10px; }
        .search-state {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 12px;
            padding: 10px 12px;
            border: 1px solid #ffd6b6;
            border-radius: 14px;
            background: linear-gradient(180deg, #fffdfb 0%, #fff4e8 100%);
        }
        .search-state-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #ffc999;
            background: #fff;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 12px;
        }

        .product-name { margin: 0; color: #27160c; font-size: 19px; }
        .product-category { margin: 0; font-size: 12px; text-transform: capitalize; }
        .product-price { font-weight: 900; color: #c35300; font-size: 24px; }
        .product-actions { display: flex; gap: 8px; }
        .product-card {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(255,111,31,.12), transparent 30%),
                linear-gradient(180deg, #fff 0%, #fff8f1 100%);
        }
        .product-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,157,90,.10), transparent 44%);
            pointer-events: none;
        }
        .product-card > * { position: relative; z-index: 1; }
        .product-image { transition: transform .35s ease; }
        .product-card:hover .product-image { transform: scale(1.08); }
        .product-card:hover { transform: translateY(-3px); }
        .product-name,
        .product-price,
        .product-category {
            transition: transform .24s ease, color .24s ease;
        }
        .product-card:hover .product-name,
        .product-card:hover .product-price {
            transform: translateX(2px);
        }
        .product-card:hover .product-category {
            color: #a44a10;
        }
        .product-actions button {
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .product-actions button:hover {
            transform: translateY(-2px);
        }

        .modal-wrap {
            position: fixed;
            inset: 0;
            display: none;
            place-items: center;
            background: rgba(0, 0, 0, .62);
            z-index: 80;
            padding: 14px;
        }

        .modal-card {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            border-top: 4px solid #ff6f1f;
        }

        .modal-image {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
            background: #eee;
        }

        .modal-title { margin-top: 0; }
        .modal-price { font-size: 18px; color: #b44c00; }
        .close-dark { background: #171718; color: #fff; border-color: #2f2f31; }

        @keyframes hero-glow {
            from { transform: translateX(-140%); }
            to { transform: translateX(140%); }
        }

        @media (max-width: 640px) {
            .hero-strip { grid-template-columns: 1fr; }
            .hero-card,
            .hero-image { min-height: 190px; }
            .hero-copy h2 { font-size: 21px; }
        }
    </style>
@endsection

@section('scripts')
<script>
const HERO_FALLBACKS = [
    ['/images/hero/slide-1.jpg', '/images/hero/slide-2.jpg'],
    ['/images/hero/slide-2.jpg', '/images/hero/slide-1.jpg'],
    ['/images/hero/slide-3.jpg', '/images/hero/slide-2.jpg'],
];

const heroImages = [
    document.getElementById('heroImageA'),
    document.getElementById('heroImageB'),
    document.getElementById('heroImageC'),
];
const productsGrid = document.getElementById('productsGrid');
const searchInput = document.getElementById('searchInput');
const categoryInput = document.getElementById('categoryInput');
const maxPriceInput = document.getElementById('maxPriceInput');
const filterInfo = document.getElementById('filterInfo');
const modal = document.getElementById('productModal');
const searchState = document.getElementById('searchState');
const searchStateTitle = document.getElementById('searchStateTitle');
const searchStateText = document.getElementById('searchStateText');

const state = { products: [] };
let slideIndex = 0;
let searchTimer = null;
let heroPools = HERO_FALLBACKS.map(group => [...group]);

function getToken() { return localStorage.getItem('ed_token'); }
function isLoggedIn() { return Boolean(getToken()); }
function getCart() { return JSON.parse(localStorage.getItem('ed_cart') || '[]'); }
function setCart(cart) {
    localStorage.setItem('ed_cart', JSON.stringify(cart));
    window.dispatchEvent(new Event('storage'));
}
function money(n) { return Number(n).toFixed(2); }

function setSearchState(visible, title = 'Espera, estamos buscando...', text = 'Filtrando productos para mostrarte el mejor resultado.') {
    searchState.style.display = visible ? 'flex' : 'none';
    searchStateTitle.textContent = title;
    searchStateText.textContent = text;
}

function productImage(product) {
    return product && product.image_url ? product.image_url : null;
}

function uniqueImages(items) {
    return [...new Set(items.filter(Boolean))];
}

function buildHeroPools() {
    const pollos = state.products.filter(product => String(product.category || '').toLowerCase() === 'pollos');
    const bebidas = state.products.filter(product => String(product.category || '').toLowerCase() === 'bebidas');

    const personal = pollos.filter(product => /1\/4|cuarto|personal|medio|1\/2|doble|para 2|dos/i.test(product.name || ''));
    const family = pollos.filter(product => /entero|familiar|combo|1 pollo|2 pollos|parrilla/i.test(product.name || ''));

    const personalImages = uniqueImages((personal.length ? personal : pollos).map(productImage));
    const familyImages = uniqueImages((family.length ? family : [...pollos].reverse()).map(productImage));
    const drinkImages = uniqueImages(bebidas.map(productImage));

    heroPools = [
        personalImages.length ? personalImages : HERO_FALLBACKS[0],
        familyImages.length ? familyImages : HERO_FALLBACKS[1],
        drinkImages.length ? drinkImages : HERO_FALLBACKS[2],
    ];

    heroImages.forEach((image, index) => {
        image.src = heroPools[index][0];
    });
}

function nextSlide() {
    slideIndex += 1;
    heroImages.forEach((image, index) => {
        const pool = heroPools[index] && heroPools[index].length ? heroPools[index] : HERO_FALLBACKS[index];
        image.src = pool[slideIndex % pool.length];
    });
}

function showProduct(product) {
    document.getElementById('modalImage').src = product.image_url || '/images/products/default.svg';
    document.getElementById('modalName').textContent = product.name;
    document.getElementById('modalDesc').textContent = product.description || 'Sin descripcion.';
    document.getElementById('modalPrice').textContent = `Precio: S/ ${money(product.price)}`;
    modal.style.display = 'grid';
}

function addToCart(product) {
    if (!isLoggedIn()) {
        window.location.href = '/login';
        return;
    }
    const cart = getCart();
    const existing = cart.find(item => item.id === product.id);
    if (existing) existing.qty += 1;
    else cart.push({
        id: product.id,
        name: product.name,
        category: product.category || '',
        price: Number(product.price),
        qty: 1
    });
    setCart(cart);
}

function filteredProducts() {
    const query = searchInput.value.trim().toLowerCase();
    const category = categoryInput.value.trim().toLowerCase();
    const maxPrice = maxPriceInput.value ? Number(maxPriceInput.value) : null;

    if (!query && !category && maxPrice === null) return [];

    return state.products.filter(product => {
        const byName = !query || product.name.toLowerCase().includes(query);
        const byCategory = !category || String(product.category || '').toLowerCase() === category;
        const byPrice = maxPrice === null || Number(product.price) <= maxPrice;
        return byName && byCategory && byPrice;
    });
}

function renderProducts() {
    const list = filteredProducts();

    if (!searchInput.value.trim() && !categoryInput.value && !maxPriceInput.value) {
        productsGrid.innerHTML = '<article class="panel"><strong>Solo mostramos las 3 imagenes promocionales al inicio. Usa el buscador para ver productos.</strong></article>';
        filterInfo.textContent = 'Escribe en el buscador para ver resultados.';
        return;
    }

    filterInfo.textContent = `Resultados: ${list.length}`;
    if (!list.length) {
        productsGrid.innerHTML = '<article class="panel"><strong>No hay productos con esos filtros.</strong></article>';
        return;
    }

    productsGrid.innerHTML = list.map(product => `
        <article class="product-card">
            <div class="product-image-wrap">
                <img src="${product.image_url || '/images/products/default.svg'}" alt="${product.name}" class="product-image">
            </div>
            <h3 class="product-name">${product.name}</h3>
            <p class="muted-main product-category">${product.category || 'general'}</p>
            <div class="product-price">S/ ${money(product.price)}</div>
            <div class="product-actions">
                <button type="button" data-inspect="${product.id}" class="btn-soft">Inspeccionar</button>
                <button type="button" data-buy="${product.id}" class="btn-main">Agregar</button>
            </div>
        </article>
    `).join('');

    productsGrid.querySelectorAll('[data-inspect]').forEach(btn => {
        const product = state.products.find(p => p.id === Number(btn.getAttribute('data-inspect')));
        btn.addEventListener('click', () => showProduct(product));
    });

    productsGrid.querySelectorAll('[data-buy]').forEach(btn => {
        const product = state.products.find(p => p.id === Number(btn.getAttribute('data-buy')));
        btn.addEventListener('click', () => addToCart(product));
    });
}

function queueRenderProducts() {
    setSearchState(true);
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        renderProducts();
        setSearchState(false);
    }, 240);
}

async function loadProducts() {
    setSearchState(true, 'Espera, estamos cargando...', 'Preparando las categorias y las imagenes del menu.');
    const res = await fetch('/api/v1/products');
    const data = await res.json();
    state.products = Array.isArray(data) ? data : [];
    buildHeroPools();
    renderProducts();
    setSearchState(false);
}

setInterval(nextSlide, 3500);
searchInput.addEventListener('input', queueRenderProducts);
categoryInput.addEventListener('change', queueRenderProducts);
maxPriceInput.addEventListener('input', queueRenderProducts);
document.getElementById('closeModalBtn').addEventListener('click', () => { modal.style.display = 'none'; });
modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

loadProducts();
</script>
@endsection
