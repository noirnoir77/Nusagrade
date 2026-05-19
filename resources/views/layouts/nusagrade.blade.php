<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('seo-title', 'Nusagrade - Premium Indonesian Commodities')</title>
    <meta name="description" content="@yield('seo-description', 'Nusagrade is a trusted Indonesian export company connecting the world with authentic spices, coffee, and cocoa from Indonesia\'s fertile islands.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph -->
    <meta property="og:type" content="@yield('og-type', 'website')">
    <meta property="og:site_name" content="Nusagrade">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="@yield('og-title', 'Nusagrade - Premium Indonesian Commodities')">
    <meta property="og:description" content="@yield('og-description', 'Trusted Indonesian export company for premium spices, coffee, and cocoa. Connecting Europe and Asia with the best of the archipelago.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og-image', asset('images/hero.png'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og-title', 'Nusagrade - Premium Indonesian Commodities')">
    <meta name="twitter:description" content="@yield('og-description', 'Trusted Indonesian export company for premium spices, coffee, and cocoa.')">
    <meta name="twitter:image" content="@yield('og-image', asset('images/hero.png'))">

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon_io/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon_io/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon_io/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon_io/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nusagrade.css') }}">

    @stack('head')
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav id="navbar">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">Nusagrade</a>
        <button class="nav-toggle" aria-label="Toggle menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}#about">About</a></li>
            <li><a href="{{ route('home') }}#products">Products</a></li>
            <li><a href="{{ route('home') }}#gallery">Gallery</a></li>
            <li><a href="{{ route('articles.index') }}">Blog</a></li>
            <li><a href="{{ route('home') }}#faq">FAQ</a></li>
            <li class="nav-mobile-cta"><a href="{{ route('home') }}#contact">Contact</a></li>
        </ul>
        <a href="{{ route('home') }}#contact" class="nav-cta">Get in Touch</a>
    </div>
</nav>

@yield('content')

<!-- ===== FOOTER ===== -->
<footer>
    <div class="container">
        <div class="footer-inner">
            <div>
                <div class="footer-brand">Nusagrade</div>
                <p class="footer-tagline">Your trusted source for premium Indonesian commodities - delivered reliably to Europe and Asia.</p>
            </div>
            <div>
                <div class="footer-col-title">Navigation</div>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}#about">About</a></li>
                    <li><a href="{{ route('home') }}#products">Products</a></li>
                    <li><a href="{{ route('home') }}#gallery">Gallery</a></li>
                    <li><a href="{{ route('articles.index') }}">Blog</a></li>
                    <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Products</div>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}#products">Cengkeh</a></li>
                    <li><a href="{{ route('home') }}#products">Kapulaga</a></li>
                    <li><a href="{{ route('home') }}#products">Lada</a></li>
                    <li><a href="{{ route('home') }}#products">Kopi</a></li>
                    <li><a href="{{ route('home') }}#products">Kakao</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Contact</div>
                <ul class="footer-links">
                    <li><a href="mailto:contact@nusagrade.com">contact@nusagrade.com</a></li>
                    <li><a href="{{ route('home') }}#contact">Send a Message</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span class="footer-copy">&copy; {{ date('Y') }} Nusagrade. All rights reserved.</span>
            <div class="footer-socials"></div>
        </div>
    </div>
</footer>

<!-- ===== WHATSAPP BUTTON ===== -->
<a id="wa-btn" href="https://wa.me/6285161223231" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
    </svg>
</a>

@isset($products)
<!-- ===== PRODUCT MODAL ===== -->
<div id="product-modal" role="dialog" aria-modal="true" aria-label="Product details">
    <div class="modal-backdrop" onclick="closeModal()"></div>
    <div class="modal-box">
        <div class="modal-img-side">
            <div id="modal-img-wrap" style="width:100%;height:100%;"></div>
            <div class="modal-img-badge" id="modal-badge"></div>
        </div>
        <div class="modal-content-side">
            <button class="modal-close" onclick="closeModal()" aria-label="Close">&times;</button>
            <div class="modal-product-tag" id="modal-tag"></div>
            <div class="modal-product-name" id="modal-name"></div>
            <div id="modal-latin" style="font-size:0.78rem;color:var(--ink-light);font-style:italic;margin-bottom:14px;"></div>
            <p id="modal-desc" style="font-size:0.88rem;color:var(--ink-mid);line-height:1.7;margin-bottom:20px;"></p>
            <div id="modal-specs-wrap"></div>
            <div id="modal-origins-wrap" style="margin-top:16px;"></div>
        </div>
    </div>
</div>

<script>
const productData = @json($products);

function openModal(key) {
    const p = productData[key];
    if (!p) return;
    document.getElementById('modal-badge').textContent = p.badge;
    document.getElementById('modal-tag').textContent = p.tag;
    document.getElementById('modal-name').textContent = p.name;
    document.getElementById('modal-latin').textContent = p.latin;
    document.getElementById('modal-desc').textContent = p.desc;

    const imgWrap = document.getElementById('modal-img-wrap');
    if (p.image) {
        imgWrap.innerHTML = `<img src="/storage/../${p.image}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover;display:block;">`;
    } else {
        imgWrap.innerHTML = `<div class="img-ph dark" style="width:100%;height:100%;">${p.imgLabel ?? p.name}</div>`;
    }

    const specsWrap = document.getElementById('modal-specs-wrap');
    if (p.specs && p.specs.length) {
        let rows = p.specs.map(([k,v]) => `<tr><td style="padding:5px 8px 5px 0;font-size:0.78rem;color:var(--ink-light);width:50%;font-family:var(--mono);font-size:0.62rem;letter-spacing:0.08em;text-transform:uppercase;">${k}</td><td style="padding:5px 0;font-size:0.82rem;color:var(--ink);font-weight:500;">${v}</td></tr>`).join('');
        specsWrap.innerHTML = `<table style="width:100%;border-collapse:collapse;border-top:1px solid var(--cream-deep);">${rows}</table>`;
    } else {
        specsWrap.innerHTML = '';
    }

    const originsWrap = document.getElementById('modal-origins-wrap');
    if (p.origins && p.origins.length) {
        originsWrap.innerHTML = `<div style="font-family:var(--mono);font-size:0.58rem;letter-spacing:0.14em;text-transform:uppercase;color:var(--brown);margin-bottom:6px;">Origins</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">${p.origins.map(o => `<span style="background:var(--cream-warm);border:1px solid var(--cream-deep);padding:3px 10px;border-radius:2px;font-size:0.78rem;color:var(--ink-mid);">${o}</span>`).join('')}</div>`;
    } else {
        originsWrap.innerHTML = '';
    }

    document.getElementById('product-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('product-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endisset

<script>
// Navbar scroll effect
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 40);
}, { passive: true });

// Mobile menu toggle
function toggleMenu() {
    const links = document.querySelector('.nav-links');
    const toggle = document.querySelector('.nav-toggle');
    links.classList.toggle('open');
    toggle.classList.toggle('open');
    // Recalculate navbar offset after the menu changes (allow layout to settle)
    setTimeout(() => { if (typeof setNavbarOffset === 'function') setNavbarOffset(); }, 60);
}

// Reveal on scroll
const revealEls = document.querySelectorAll('.reveal');
if (revealEls.length) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
    }, { threshold: 0.12 });
    revealEls.forEach(el => observer.observe(el));
}

// FAQ accordion
function toggleFaq(el) {
    const answer = el.nextElementSibling;
    const icon   = el.querySelector('.faq-icon');
    const isOpen = answer.style.maxHeight;
    document.querySelectorAll('.faq-answer').forEach(a => { a.style.maxHeight = ''; });
    document.querySelectorAll('.faq-icon').forEach(i => { i.textContent = '+'; });
    if (!isOpen) { answer.style.maxHeight = answer.scrollHeight + 'px'; icon.textContent = '×'; }
}
</script>
</script>

<!-- Dynamic navbar offset: set CSS variable --nav-offset so fixed navbar doesn't overlap content -->
<script>
function setNavbarOffset() {
    const nav = document.getElementById('navbar');
    if (!nav) return;
    const rect = nav.getBoundingClientRect();
    const height = Math.ceil(rect.height);
    document.documentElement.style.setProperty('--nav-offset', height + 'px');
}

document.addEventListener('DOMContentLoaded', setNavbarOffset);
window.addEventListener('resize', setNavbarOffset);
window.addEventListener('load', () => setTimeout(setNavbarOffset, 60));
</script>

</body>
</html>
