<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'TuquParcel')</title>

  @stack('seo')

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green-dark: #2D4A3E;
      --green-mid: #3D6B59;
      --green-light: #E8F0EB;
      --ivory: #F7F3EE;
      --ivory-dark: #EDE8E1;
      --gold: #C9A96E;
      --gold-light: #E8D5B0;
      --white: #FDFCFB;
      --text-dark: #1A1A1A;
      --text-mid: #4A4A4A;
      --text-muted: #6B6560;
      --border: #E0D9D0;
      --shadow-soft: 0 4px 24px rgba(45,74,62,0.08);
      --shadow-card: 0 2px 16px rgba(45,74,62,0.07);
      --radius-card: 8px;
      --radius-btn: 4px;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--white);
      color: var(--text-dark);
      font-size: 16px;
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* ── NAVBAR ── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 60px;
      height: 68px;
      background: rgba(253,252,251,0.92);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(224,217,208,0.5);
      transition: box-shadow 0.3s;
    }
    nav.scrolled { box-shadow: var(--shadow-soft); }

    .nav-logo {
      font-family: 'Cormorant Garamond', serif;
      font-weight: 700;
      color: var(--green-dark);
      letter-spacing: 0.02em;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
    .nav-logo-text {
        margin-left: 10px;
      font-family: 'Cormorant Garamond', serif;
      font-weight: 700;
      color: #1a1a1a;
      letter-spacing: 0.05em;
      text-transform: uppercase;
    }

    .nav-links {
      display: flex;
      gap: 36px;
      list-style: none;
    }
    .nav-links a {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-mid);
      text-decoration: none;
      letter-spacing: 0.02em;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: var(--green-dark); }
    .nav-links a.nav-active {
      color: var(--green-dark);
      font-weight: 700;
      position: relative;
    }
    .nav-links a.nav-active::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      right: 0;
      height: 2px;
      background: var(--green-dark);
      border-radius: 2px;
    }

    .btn-nav {
      background: var(--green-dark);
      color: var(--ivory) !important;
      padding: 10px 22px;
      border-radius: var(--radius-btn);
      font-size: 0.875rem;
      font-weight: 500;
      text-decoration: none;
      transition: background 0.2s, transform 0.15s;
      letter-spacing: 0.02em;
    }
    .btn-nav:hover { background: var(--green-mid); transform: translateY(-1px); }

    /* ── NAV AUTH ── */
    .nav-auth {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .btn-nav-outline {
      border: 1.5px solid var(--green-dark);
      color: var(--green-dark);
      padding: 8px 18px;
      border-radius: var(--radius-btn);
      font-size: 0.85rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s;
      letter-spacing: 0.02em;
      white-space: nowrap;
    }
    .btn-nav-outline:hover { background: var(--green-dark); color: var(--ivory); }

    /* ── ACCOUNT DROPDOWN ── */
    .nav-account {
      position: relative;
    }
    .nav-account-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: none;
      border: 1.5px solid var(--border);
      padding: 7px 14px 7px 10px;
      border-radius: var(--radius-btn);
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--green-dark);
      transition: all 0.2s;
      white-space: nowrap;
    }
    .nav-account-btn:hover {
      border-color: var(--green-dark);
      background: var(--green-light);
    }
    .nav-account-btn svg { flex-shrink: 0; }
    .nav-account-name {
      max-width: 120px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .nav-account-chevron {
      transition: transform 0.2s;
      flex-shrink: 0;
    }
    .nav-account.open .nav-account-chevron { transform: rotate(180deg); }

    .nav-dropdown {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 10px);
      width: 210px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 10px;
      box-shadow: 0 8px 32px rgba(45,74,62,0.12);
      z-index: 200;
      overflow: hidden;
    }
    .nav-account.open .nav-dropdown { display: block; }

    .nav-dropdown-header {
      padding: 14px 16px 10px;
      border-bottom: 1px solid var(--border);
    }
    .nav-dropdown-header-name {
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--text-dark);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .nav-dropdown-header-email {
      font-size: 0.75rem;
      color: var(--text-muted);
      margin-top: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .nav-dropdown-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      font-size: 0.85rem;
      color: var(--text-mid);
      text-decoration: none;
      transition: background 0.15s;
    }
    .nav-dropdown-item:hover { background: var(--ivory); color: var(--green-dark); }
    .nav-dropdown-item svg { color: var(--text-muted); flex-shrink: 0; }
    .nav-dropdown-item:hover svg { color: var(--green-dark); }
    .nav-dropdown-item.active { background: var(--green-light); color: var(--green-dark); border-left: 3px solid var(--green-dark); font-weight: 600; }
    .nav-dropdown-item.active svg { color: var(--green-dark); }

    .nav-dropdown-divider { height: 1px; background: var(--border); margin: 2px 0; }

    .nav-dropdown-item.danger { color: #dc2626; }
    .nav-dropdown-item.danger:hover { background: #fef2f2; color: #dc2626; }
    .nav-dropdown-item.danger svg { color: #dc2626; }

    .nav-cart-badge {
      margin-left: auto;
      background: var(--gold);
      color: white;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 1px 7px;
      border-radius: 20px;
      min-width: 20px;
      text-align: center;
    }

    /* hamburger */
    .hamburger {
      display: none;
      flex-direction: column;
      gap: 5px;
      cursor: pointer;
      padding: 4px;
    }
    .hamburger span {
      display: block;
      width: 22px; height: 2px;
      background: var(--green-dark);
      border-radius: 2px;
      transition: all 0.3s;
    }
    .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2) { opacity: 0; }
    .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    .mobile-menu {
      display: none;
      position: fixed;
      top: 68px; left: 0; right: 0;
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 20px 24px 28px;
      z-index: 99;
      flex-direction: column;
      gap: 4px;
      box-shadow: var(--shadow-soft);
    }
    .mobile-menu.open { display: flex; }
    .mobile-menu a {
      font-size: 1rem;
      font-weight: 500;
      color: var(--text-mid);
      text-decoration: none;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }
    .mobile-menu a:last-child { border-bottom: none; }
    .mobile-menu .btn-nav-mobile {
      margin-top: 12px;
      background: var(--green-dark);
      color: var(--ivory);
      text-align: center;
      padding: 12px;
      border-radius: var(--radius-btn);
      border-bottom: none;
    }
    .mobile-menu a.mob-active {
      color: var(--green-dark);
      font-weight: 700;
      padding-left: 12px;
      border-left: 3px solid var(--green-dark);
    }

    /* ── MOBILE AUTH ── */
    .mobile-menu-divider {
      height: 1px;
      background: var(--border);
      margin: 6px 0;
    }

    .mobile-auth-card {
      display: flex;
      align-items: center;
      gap: 12px;
      background: var(--green-light);
      border-radius: 8px;
      padding: 12px 14px;
      margin: 4px 0 6px;
      border-bottom: none !important;
    }
    .mobile-auth-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--green-dark);
      color: var(--ivory);
      font-size: 0.9rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-family: 'Cormorant Garamond', serif;
      letter-spacing: 0.02em;
    }
    .mobile-auth-info-text {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    .mobile-auth-name {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--text-dark);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .mobile-auth-email {
      font-size: 0.72rem;
      color: var(--text-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .mobile-account-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--text-mid);
      text-decoration: none;
      padding: 9px 4px !important;
      border-bottom: 1px solid var(--border);
      transition: color 0.2s;
    }
    .mobile-account-item svg {
      color: var(--text-muted);
      flex-shrink: 0;
    }
    .mobile-account-item:hover { color: var(--green-dark); }
    .mobile-account-item:hover svg { color: var(--green-dark); }
    .mobile-account-item:last-of-type { border-bottom: none; }

    .mob-logout-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      text-align: left;
      background: none;
      border: none;
      border-top: 1px solid var(--border);
      padding: 9px 4px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      font-weight: 500;
      color: #dc2626;
      cursor: pointer;
      margin-top: 2px;
    }
    .mob-logout-btn svg { flex-shrink: 0; }

    /* ── HERO ── */
    .hero {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      overflow: hidden;
    }
    .scroll-indicator {
      position: absolute;
      bottom: 32px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
      display: none;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      animation: bounceDown 2s infinite;
    }
    .scroll-indicator span {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 0.12em;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
    }
    .scroll-indicator svg {
      color: rgba(255,255,255,0.6);
    }
    @keyframes bounceDown {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(8px); }
    }
    @media (max-width: 768px) {
      .scroll-indicator { display: flex; }
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, #1a2e27 0%, #2d4a3e 40%, #1a1a1a 100%);
    }
    .hero-bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background: url('{{ \App\Models\Setting::get("hero_photo") ? asset("storage/" . \App\Models\Setting::get("hero_photo")) : asset("assets/HeroLand.jpg") }}') center/cover no-repeat;
      opacity: 0.45;
      mix-blend-mode: luminosity;
    }
    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to right, rgba(26,46,39,0.85) 45%, rgba(26,26,26,0.3) 100%);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 60px;
      padding-top: 80px;
      width: 100%;
    }

    .hero-label {
      font-size: 0.75rem;
      font-weight: 500;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--gold-light);
      margin-bottom: 20px;
      opacity: 0;
      animation: fadeUp 0.8s 0.2s forwards;
    }

    .hero-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(3rem, 6vw, 5.5rem);
      font-weight: 700;
      line-height: 1.05;
      color: #FDFCFB;
      margin-bottom: 20px;
      max-width: 600px;
      opacity: 0;
      animation: fadeUp 0.8s 0.4s forwards;
    }

    .hero-subtitle {
      font-size: 1rem;
      color: rgba(253,252,251,0.75);
      max-width: 420px;
      line-height: 1.7;
      margin-bottom: 36px;
      font-weight: 300;
      opacity: 0;
      animation: fadeUp 0.8s 0.6s forwards;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--ivory);
      color: var(--green-dark);
      padding: 14px 28px;
      border-radius: var(--radius-btn);
      font-size: 0.875rem;
      font-weight: 600;
      text-decoration: none;
      letter-spacing: 0.04em;
      transition: all 0.25s;
      opacity: 0;
      animation: fadeUp 0.8s 0.8s forwards;
    }
    .btn-primary:hover {
      background: var(--gold-light);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    .btn-primary svg { transition: transform 0.2s; }
    .btn-primary:hover svg { transform: translateX(3px); }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ── SECTION BASE ── */
    section { width: 100%; }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 60px;
    }
    .section-pad { padding: 88px 0; }
    .section-pad-sm { padding: 64px 0; }

    .section-label {
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 10px;
    }
    .section-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(2rem, 3.5vw, 2.75rem);
      font-weight: 600;
      color: var(--text-dark);
      line-height: 1.2;
    }
    .section-subtitle {
      font-size: 0.9rem;
      color: var(--text-muted);
      margin-top: 8px;
      font-weight: 300;
    }
    .section-header-row {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      margin-bottom: 40px;
    }
    .link-all {
      font-size: 0.8rem;
      font-weight: 500;
      color: var(--green-dark);
      text-decoration: none;
      letter-spacing: 0.05em;
      display: flex;
      align-items: center;
      gap: 4px;
      white-space: nowrap;
      border-bottom: 1px solid var(--green-dark);
      padding-bottom: 1px;
      transition: opacity 0.2s;
    }
    .link-all:hover { opacity: 0.7; }

    /* ── OCCASION ── */
    .occasion-section { background: var(--white); }
    .occasion-title {
      text-align: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 36px;
      letter-spacing: 0.01em;
    }
    .occasion-grid {
      display: flex;
      justify-content: center;
      gap: 16px;
      flex-wrap: wrap;
    }
    .occasion-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      text-decoration: none;
      padding: 18px 20px;
      border-radius: var(--radius-card);
      border: 1px solid var(--border);
      background: var(--white);
      transition: all 0.25s;
      min-width: 90px;
    }
    .occasion-item:hover {
      border-color: var(--green-dark);
      background: var(--green-light);
      transform: translateY(-3px);
      box-shadow: var(--shadow-card);
    }
    .occasion-icon {
      width: 44px; height: 44px;
      border-radius: 50%;
      background: var(--ivory);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      transition: background 0.2s;
    }
    .occasion-item:hover .occasion-icon { background: white; }
    .occasion-label {
      font-size: 0.78rem;
      font-weight: 500;
      color: var(--text-mid);
      text-align: center;
      letter-spacing: 0.01em;
    }

    /* ── PRODUCTS ── */
    .products-section { background: var(--ivory); }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }
    .product-card {
      background: var(--white);
      border-radius: var(--radius-card);
      overflow: hidden;
      box-shadow: var(--shadow-card);
      transition: transform 0.25s, box-shadow 0.25s;
      cursor: pointer;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 32px rgba(45,74,62,0.12);
    }
    .product-img {
      position: relative;
      aspect-ratio: 3/4;
      overflow: hidden;
      background: var(--ivory-dark);
    }
    .product-img img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.5s;
    }
    .product-card:hover .product-img img { transform: scale(1.05); }
    .product-badge {
      position: absolute;
      top: 12px; left: 12px;
      background: var(--green-dark);
      color: var(--ivory);
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 4px 10px;
      border-radius: 2px;
    }
    .product-badge.limited {
      background: var(--gold);
      color: var(--text-dark);
    }
    .product-info {
      padding: 16px 18px 18px;
    }
    .product-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 4px;
      line-height: 1.3;
    }
    .product-price {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-bottom: 14px;
      font-weight: 400;
    }
    .btn-detail {
      display: block;
      width: 100%;
      text-align: center;
      padding: 10px;
      border: 1.5px solid var(--green-dark);
      color: var(--green-dark);
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.05em;
      border-radius: var(--radius-btn);
      text-decoration: none;
      transition: all 0.2s;
      background: transparent;
      cursor: pointer;
    }
    .btn-detail:hover {
      background: var(--green-dark);
      color: var(--ivory);
    }

    /* ── WHY US ── */
    .why-section { background: var(--ivory-dark); }
    .why-title {
      text-align: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1.75rem, 3vw, 2.5rem);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 48px;
    }
    .why-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
    }
    .why-card {
      background: var(--white);
      border-radius: var(--radius-card);
      padding: 36px 28px;
      text-align: center;
      box-shadow: var(--shadow-card);
      border: 1px solid var(--border);
      transition: transform 0.25s, box-shadow 0.25s;
    }
    .why-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-soft);
    }
    .why-icon {
      width: 56px; height: 56px;
      border-radius: 50%;
      background: var(--green-light);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 18px;
      font-size: 1.5rem;
    }
    .why-card h3 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 10px;
    }
    .why-card p {
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.7;
      font-weight: 300;
    }

    /* ── HOW TO ORDER ── */
    .how-section { background: var(--white); }
    .how-title {
      text-align: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1.75rem, 3vw, 2.5rem);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 56px;
    }
    .how-steps {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0;
      position: relative;
    }
    .how-steps::before {
      content: '';
      position: absolute;
      top: 36px;
      left: calc(16.67% + 28px);
      right: calc(16.67% + 28px);
      height: 1px;
      background: linear-gradient(to right, var(--gold), var(--gold-light), var(--gold));
      z-index: 0;
    }
    .how-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 0 24px;
      position: relative;
      z-index: 1;
    }
    .step-num {
      width: 72px; height: 72px;
      border-radius: 50%;
      background: var(--green-dark);
      color: var(--ivory);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      position: relative;
      z-index: 2;
      flex-shrink: 0;
      box-shadow: 0 4px 16px rgba(45,74,62,0.25);
    }
    .step-icon {
      font-size: 1.4rem;
      margin-bottom: 14px;
      color: var(--text-muted);
    }
    .step-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 10px;
    }
    .step-desc {
      font-size: 0.83rem;
      color: var(--text-muted);
      line-height: 1.7;
      font-weight: 300;
    }
    .chat-link {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--green-dark);
      text-decoration: none;
      margin-top: 10px;
      border-bottom: 1px solid var(--green-dark);
      padding-bottom: 1px;
    }
    .chat-link:hover { opacity: 0.7; }

    /* ── TESTIMONIAL ── */
    .testi-section { background: var(--ivory); }
    .testi-inner {
      max-width: 760px;
      margin: 0 auto;
      text-align: center;
    }
    .testi-quote-mark {
      font-family: 'Cormorant Garamond', serif;
      font-size: 6rem;
      line-height: 0.5;
      color: var(--gold);
      display: block;
      margin-bottom: 28px;
      font-weight: 700;
    }
    .testi-text {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1.2rem, 2.5vw, 1.6rem);
      font-style: italic;
      color: var(--text-dark);
      line-height: 1.6;
      margin-bottom: 28px;
      font-weight: 400;
    }
    .testi-divider {
      width: 48px; height: 1px;
      background: var(--gold);
      margin: 0 auto 20px;
    }
    .testi-name {
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--text-dark);
      margin-bottom: 4px;
    }
    .testi-role {
      font-size: 0.78rem;
      color: var(--text-muted);
      font-weight: 300;
    }

    /* ── FOOTER ── */
    footer {
      background: var(--green-dark);
      color: rgba(253,252,251,0.75);
    }
    .footer-top {
      padding: 64px 0 40px;
      display: grid;
      grid-template-columns: 1.8fr 1fr 1fr 1.2fr;
      gap: 48px;
    }
    .footer-brand .footer-logo {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--ivory);
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 14px;
      text-decoration: none;
    }
    .footer-logo-dot {
      width: 22px; height: 22px;
      background: var(--gold);
      border-radius: 50%;
      flex-shrink: 0;
    }
    .footer-brand p {
      font-size: 0.83rem;
      line-height: 1.75;
      font-weight: 300;
      max-width: 260px;
      margin-bottom: 20px;
    }
    .footer-social {
      display: flex;
      gap: 10px;
    }
    .social-btn {
      width: 36px; height: 36px;
      border-radius: 50%;
      border: 1px solid rgba(253,252,251,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(253,252,251,0.7);
      text-decoration: none;
      transition: all 0.2s;
      flex-shrink: 0;
    }
    .social-btn:hover {
      background: var(--gold);
      border-color: var(--gold);
      color: var(--green-dark);
    }
    .footer-col h4 {
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--ivory);
      margin-bottom: 18px;
    }
    .footer-col ul { list-style: none; }
    .footer-col ul li { margin-bottom: 10px; }
    .footer-col ul li a {
      font-size: 0.83rem;
      color: rgba(253,252,251,0.65);
      text-decoration: none;
      font-weight: 300;
      transition: color 0.2s;
    }
    .footer-col ul li a:hover { color: var(--gold-light); }

    .footer-contact-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 12px;
      font-size: 0.83rem;
      color: rgba(253,252,251,0.65);
      font-weight: 300;
    }
    .footer-contact-item span:first-child {
      color: var(--gold);
      flex-shrink: 0;
      margin-top: 2px;
    }

    .footer-bottom {
      border-top: 1px solid rgba(253,252,251,0.1);
      padding: 20px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .footer-bottom p {
      font-size: 0.78rem;
      color: rgba(253,252,251,0.4);
      font-weight: 300;
    }
    .footer-bottom-links {
      display: flex;
      gap: 20px;
    }
    .footer-bottom-links a {
      font-size: 0.78rem;
      color: rgba(253,252,251,0.4);
      text-decoration: none;
      font-weight: 300;
      transition: color 0.2s;
    }
    .footer-bottom-links a:hover { color: rgba(253,252,251,0.7); }

    .footer-contact-item a {
    color: rgba(253,252,251,0.65);
    text-decoration: none;}

    .footer-contact-item a:hover {
    color: var(--gold-light);}
    /* ── DIVIDER ── */
    .section-divider {
      width: 56px; height: 1.5px;
      background: var(--gold);
      margin: 12px 0 0;
    }
    .section-divider.center { margin: 12px auto 0; }

    /* ── SCROLL REVEAL ── */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .reveal.visible {
      opacity: 1;
      transform: none;
    }
    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }
    .reveal-delay-4 { transition-delay: 0.4s; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
      nav { padding: 0 32px; }
      .container { padding: 0 32px; }
      .hero-content { padding: 0 32px; padding-top: 80px; }
      .product-grid { grid-template-columns: repeat(2, 1fr); }
      .footer-top { grid-template-columns: 1fr 1fr; gap: 32px; }
    }

    @media (max-width: 768px) {
      nav { padding: 0 20px; }
      .nav-links, .btn-nav, .nav-auth { display: none; }
      .hamburger { display: flex; }
      .container { padding: 0 20px; }
      .section-pad { padding: 60px 0; }
      .section-pad-sm { padding: 44px 0; }

      .hero-content { padding: 0 20px; padding-top: 80px; }
      .hero-title { font-size: 2.6rem; }

      .occasion-grid { gap: 10px; }
      .occasion-item { min-width: 76px; padding: 14px 12px; }

      .product-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }

      .why-grid { grid-template-columns: 1fr; gap: 16px; }

      .how-steps {
        grid-template-columns: 1fr;
        gap: 40px;
      }
      .how-steps::before { display: none; }

      .footer-top { grid-template-columns: 1fr; gap: 28px; padding: 44px 0 32px; }
      .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
      .section-header-row { flex-direction: column; align-items: flex-start; gap: 8px; }
    }

    @media (max-width: 480px) {
      .hero-title { font-size: 2rem; }
      .product-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
      .occasion-item { min-width: 68px; padding: 12px 8px; }
      .occasion-label { font-size: 0.7rem; }
    }

  @media (max-width: 768px) {
    .mobile-menu { display: none; }
    .mobile-menu.open { display: flex; flex-direction: column; }
  }
  </style>
  @stack('styles')
</head>
<body>

@php $__nav_logo = \App\Models\Setting::get('site_logo_navbar') ?: \App\Models\Setting::get('site_logo', ''); @endphp
@php $__customer = Auth::guard('customer')->user(); @endphp
<nav id="navbar">
    <a href="{{ route('public.landing') }}" class="nav-logo">
      @if($__nav_logo)
        <img src="{{ asset('storage/' . $__nav_logo) }}" alt="{{ \App\Models\Setting::get('site_name', 'TuquParcel') }}" style="height:56px;object-fit:contain;width:auto;">
      @endif
      <span class="nav-logo-text">{{ \App\Models\Setting::get('site_name', 'TuquParcel') }}</span>
    </a>
    <ul class="nav-links">
      <li><a href="{{ route('public.landing') }}" class="{{ request()->routeIs('public.landing') ? 'nav-active' : '' }}">Beranda</a></li>
      <li><a href="{{ route('public.katalog') }}" class="{{ request()->routeIs('public.katalog') ? 'nav-active' : '' }}">Katalog</a></li>
      <li><a href="{{ route('public.landing') }}#cara-pemesanan">Cara Pemesanan</a></li>
      <li><a href="{{ route('public.tentang') }}" class="{{ request()->routeIs('public.tentang') ? 'nav-active' : '' }}">Tentang Kami</a></li>
    </ul>

    {{-- Desktop Auth --}}
    <div class="nav-auth" id="navAuthDesktop">
      @if($__customer)
        {{-- Sudah login: dropdown akun --}}
        <div class="nav-account" id="navAccount">
          <button class="nav-account-btn" onclick="toggleAccountDropdown()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-account-name">{{ $__customer->name }}</span>
            <svg class="nav-account-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div class="nav-dropdown" id="navDropdown">
            {{-- Header info --}}
            <div class="nav-dropdown-header">
              <div class="nav-dropdown-header-name">{{ $__customer->name }}</div>
              <div class="nav-dropdown-header-email">{{ $__customer->email }}</div>
            </div>
            {{-- Menu items --}}
            <a href="{{ route('account.profile') }}" class="nav-dropdown-item {{ request()->routeIs('account.profile') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Pengaturan Akun
            </a>
            <a href="{{ route('account.orders') }}" class="nav-dropdown-item {{ request()->routeIs('account.orders', 'account.order-detail') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Pesanan Saya
            </a>
            <a href="{{ route('cart.index') }}" class="nav-dropdown-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              Keranjang
              @php $__cartCount = $__customer->cartItems()->count(); @endphp
              <span class="nav-cart-badge" id="navCartBadge" style="{{ $__cartCount > 0 ? '' : 'display:none' }}">{{ $__cartCount }}</span>
            </a>
            <div class="nav-dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
              @csrf
              <button type="submit" class="nav-dropdown-item danger" style="width:100%;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;text-align:left;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
              </button>
            </form>
          </div>
        </div>
      @else
        {{-- Belum login --}}
        <a href="{{ route('login') }}" class="btn-nav-outline">Masuk</a>
        <a href="{{ route('register') }}" class="btn-nav">Daftar</a>
      @endif
    </div>

    <div class="hamburger" id="hamburger" onclick="toggleMenu()">
      <span></span><span></span><span></span>
    </div>
  </nav>

  {{-- MOBILE MENU --}}
<div class="mobile-menu" id="mobileMenu">
  <a href="{{ route('public.landing') }}" class="{{ request()->routeIs('public.landing') ? 'mob-active' : '' }}">Beranda</a>
  <a href="{{ route('public.katalog') }}" class="{{ request()->routeIs('public.katalog') ? 'mob-active' : '' }}">Katalog</a>
  <a href="{{ route('public.landing') }}#cara-pemesanan">Cara Pemesanan</a>
  <a href="{{ route('public.tentang') }}" class="{{ request()->routeIs('public.tentang') ? 'mob-active' : '' }}">Tentang Kami</a>

  <div class="mobile-menu-divider"></div>

  @if($__customer)
    <div class="mobile-auth-card">
      <div class="mobile-auth-avatar">{{ strtoupper(substr($__customer->name, 0, 1)) }}</div>
      <div class="mobile-auth-info-text">
        <span class="mobile-auth-name">{{ $__customer->name }}</span>
        <span class="mobile-auth-email">{{ $__customer->email }}</span>
      </div>
    </div>
    <a href="{{ route('account.profile') }}" class="mobile-account-item">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      Pengaturan Akun
    </a>
    <a href="{{ route('account.orders') }}" class="mobile-account-item">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      Pesanan Saya
    </a>
    <a href="{{ route('cart.index') }}" class="mobile-account-item">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      Keranjang
      @php $__cartCountMob = $__customer->cartItems()->count(); @endphp
      <span id="navCartBadgeMob" style="margin-left:auto;background:var(--gold);color:white;font-size:0.7rem;font-weight:700;padding:1px 8px;border-radius:20px;{{ $__cartCountMob > 0 ? '' : 'display:none' }}">{{ $__cartCountMob }}</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="mob-logout-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Keluar
      </button>
    </form>
  @else
    <a href="{{ route('login') }}" style="color:var(--green-dark);font-weight:600;">Masuk</a>
    <a href="{{ route('register') }}" class="btn-nav-mobile" style="background:var(--green-dark);color:var(--ivory);text-align:center;padding:12px;border-radius:var(--radius-btn);border-bottom:none;margin-top:4px;">Daftar Sekarang</a>
  @endif
</div>

@yield('content')

@php
  $s_name      = \App\Models\Setting::get('site_name', 'TuquParcel');
  $s_tagline   = \App\Models\Setting::get('site_tagline', '');
  $s_ig        = \App\Models\Setting::get('instagram', 'tuquparcel');
  $s_ig_url    = \App\Models\Setting::get('instagram_url', 'https://instagram.com/tuquparcel');
  $s_wa        = \App\Models\Setting::get('whatsapp', '');
  $s_email     = \App\Models\Setting::get('email_contact', '');
  $s_address   = \App\Models\Setting::get('address', '');
  $s_address_2 = \App\Models\Setting::get('address_2', '');
  $s_logo        = \App\Models\Setting::get('site_logo', '');
  $s_logo_navbar = \App\Models\Setting::get('site_logo_navbar', '');
  $wa_url      = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $s_wa);
  $s_privacy   = \App\Models\Setting::get('privacy_policy', '');
  $s_terms     = \App\Models\Setting::get('terms_of_service', '');
@endphp
<footer>
    <div class="container">
      <div class="footer-top">
        <div class="footer-brand">
          <a href="{{ route('public.landing') }}" class="footer-logo">
            @if($s_logo)
              <img src="{{ asset('storage/' . $s_logo) }}" alt="{{ $s_name }}" style="height:32px;object-fit:contain;">
            @else
              <div class="footer-logo-dot"></div>
              {{ $s_name }}
            @endif
          </a>
          @if($s_tagline)
            <p>{{ $s_tagline }}</p>
          @else
            <p>Menyediakan rangkaian bunga dan hadiah-parsel berkualitas tinggi untuk mengabadikan momen berharga keluarga dalam hidup Anda.</p>
          @endif
          <div class="footer-social">
            @if($s_ig_url)
              <a href="{{ $s_ig_url }}" target="_blank" class="social-btn" title="Instagram">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
              </a>
            @endif
            @if($s_wa)
              <a href="{{ $wa_url }}" target="_blank" class="social-btn" title="WhatsApp">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              </a>
            @endif
            @php $s_tiktok = \App\Models\Setting::get('tiktok_url', ''); @endphp
            @if($s_tiktok)
              <a href="{{ $s_tiktok }}" target="_blank" class="social-btn" title="TikTok">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>
              </a>
            @endif
          </div>
        </div>
        <div class="footer-col">
          <h4>Menu</h4>
          <ul>
            <li><a href="{{ route('public.landing') }}">Beranda</a></li>
            <li><a href="{{ route('public.katalog') }}">Katalog</a></li>
            <li><a href="{{ route('public.tentang') }}">Tentang Kami</a></li>
            <li><a href="{{ route('public.landing') }}#cara-pemesanan">Cara Pemesanan</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Kategori</h4>
          <ul>
            @foreach(\App\Models\Category::all() as $cat)
            <li><a href="{{ route('public.katalog', ['kategori[]' => $cat->slug]) }}">{{ $cat->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="footer-col">
          <h4>Hubungi Kami</h4>
          {{-- Alamat 1 --}}
          @if($s_address)
          <div class="footer-contact-item">
            <span><svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></span>
            <span>
              @if($s_address_2)
                <span style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;opacity:0.6;display:block;margin-bottom:2px;">Toko 1</span>
              @endif
              {{ $s_address }}
            </span>
          </div>
          @endif

          @if($s_address_2)
          <div class="footer-contact-item">
            <span><svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></span>
            <span>
              <span style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;opacity:0.6;display:block;margin-bottom:2px;">Toko 2</span>
              {{ $s_address_2 }}
            </span>
          </div>
          @endif
          @if($s_wa)
          <div class="footer-contact-item">
            <span><svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V20a1 1 0 01-1 1C10.61 21 3 13.39 3 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.46.57 3.58a1 1 0 01-.25 1.01l-2.2 2.2z"/></svg></span>
            <span><a href="{{ $wa_url }}" target="_blank" style="color:inherit;">{{ $s_wa }}</a></span>
          </div>
          @endif
          @if($s_email)
          <div class="footer-contact-item">
            <span><svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4.7l-8 5.34L4 8.7V6.87l8 5.33 8-5.33V8.7z"/></svg></span>
            <span><a href="mailto:{{ $s_email }}" style="color:inherit;">{{ $s_email }}</a></span>
          </div>
          @endif
        </div>
      </div>
      <div class="footer-bottom">
        <p>© {{ date('Y') }} {{ $s_name }}. All rights reserved.</p>
        <div class="footer-bottom-links">
          @if($s_privacy)
            <a href="javascript:void(0)" onclick="openModal('privacy-modal')">Privacy Policy</a>
          @endif
          @if($s_terms)
            <a href="javascript:void(0)" onclick="openModal('terms-modal')">Terms of Service</a>
          @endif
        </div>
      </div>
    </div>
  </footer>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
  // Mobile menu
  function toggleMenu() {
    const mobileMenu = document.getElementById("mobileMenu");
    const hamburger = document.getElementById("hamburger");
    if(mobileMenu) mobileMenu.classList.toggle("open");
    if(hamburger) hamburger.classList.toggle("open");
  }

  // Account dropdown (desktop)
  function toggleAccountDropdown() {
    const acc = document.getElementById("navAccount");
    if(acc) acc.classList.toggle("open");
  }
  // Tutup dropdown jika klik di luar
  document.addEventListener("click", function(e) {
    const acc = document.getElementById("navAccount");
    if(acc && !acc.contains(e.target)) {
      acc.classList.remove("open");
    }
  });

  // Sticky Nav
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      if(navbar) navbar.classList.add('scrolled');
    } else {
      if(navbar) navbar.classList.remove('scrolled');
    }
  });

  // Set active link di mobile menu
  (function() {
    const path = window.location.pathname;
    const mobileLinks = document.querySelectorAll('.mobile-menu a:not(.btn-nav-mobile)');
    mobileLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && href !== 'javascript:void(0)' && !href.includes('#')) {
        const url = new URL(href, window.location.origin);
        if (url.pathname === path) {
          link.classList.add('mob-active');
        }
      }
    });
  })();

// Scroll indicator - hanya di halaman landing (bukan about-hero)
  (function() {
    const hero = document.querySelector('.hero:not(.about-hero)');
    if (hero) {
      const ind = document.createElement('div');
      ind.className = 'scroll-indicator';
      ind.onclick = function() { window.scrollBy({ top: window.innerHeight, behavior: 'smooth' }); };
      ind.innerHTML = '<span>Scroll</span><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';
      hero.appendChild(ind);
    }
  })();

  // Scroll reveal
  const reveals = document.querySelectorAll(".reveal");
  if(reveals.length > 0) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
          }
        });
      },
      { threshold: 0.12 }
    );
    reveals.forEach(el => observer.observe(el));
  }
</script>

  <!-- PRIVACY POLICY MODAL -->
  <div id="privacy-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:16px;max-width:640px;width:100%;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      <div style="padding:20px 24px;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;">
        <h3 style="font-size:17px;font-weight:700;color:#1a2e27;margin:0;">Privacy Policy</h3>
        <button onclick="closeModal('privacy-modal')" style="border:none;background:none;font-size:22px;cursor:pointer;color:#888;line-height:1;">×</button>
      </div>
      <div style="padding:24px;overflow-y:auto;font-size:14px;line-height:1.8;color:#3d5446;white-space:pre-wrap;">{{ $s_privacy }}</div>
    </div>
  </div>

  <!-- TERMS OF SERVICE MODAL -->
  <div id="terms-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:16px;max-width:640px;width:100%;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
      <div style="padding:20px 24px;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;">
        <h3 style="font-size:17px;font-weight:700;color:#1a2e27;margin:0;">Terms of Service</h3>
        <button onclick="closeModal('terms-modal')" style="border:none;background:none;font-size:22px;cursor:pointer;color:#888;line-height:1;">×</button>
      </div>
      <div style="padding:24px;overflow-y:auto;font-size:14px;line-height:1.8;color:#3d5446;white-space:pre-wrap;">{{ $s_terms }}</div>
    </div>
  </div>

  <script>
    function openModal(id) {
      const m = document.getElementById(id);
      m.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
      document.body.style.overflow = '';
    }
    // Tutup modal kalau klik backdrop
    ['privacy-modal','terms-modal'].forEach(id => {
      const m = document.getElementById(id);
      if(m) m.addEvent
Listener('click', function(e) {
        if(e.target === m) closeModal(id);
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
