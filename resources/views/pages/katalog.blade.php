@extends('layouts.public')

@section('title', 'Katalog Produk — ' . \App\Models\Setting::get('site_name', 'TuquParcel'))
@push('seo')
@php
  $__site  = \App\Models\Setting::get('site_name', 'TuquParcel');
  $__logo  = \App\Models\Setting::get('site_logo', '');
  $__ogImg = $__logo ? asset('storage/' . $__logo) : asset('images/og-default.jpg');
  $__url   = route('public.katalog');
  $__title = 'Katalog Produk — ' . $__site;
  $__desc  = 'Jelajahi koleksi lengkap buket bunga segar, parcel hadiah, dan bingkisan eksklusif dari ' . $__site . '. Tersedia untuk berbagai momen: ulang tahun, wisuda, pernikahan, dan lainnya.';

  $__schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'CollectionPage',
    'name'        => $__title,
    'description' => $__desc,
    'url'         => $__url,
    'isPartOf'    => ['@type' => 'WebSite', 'name' => $__site, 'url' => route('public.landing')],
  ];
@endphp
<meta name="description" content="{{ $__desc }}">
<meta name="keywords" content="katalog buket bunga, buket wisuda, parcel hadiah, rangkaian bunga, {{ strtolower($__site) }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $__url }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $__site }}">
<meta property="og:title" content="{{ $__title }}">
<meta property="og:description" content="{{ $__desc }}">
<meta property="og:url" content="{{ $__url }}">
<meta property="og:image" content="{{ $__ogImg }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="id_ID">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $__title }}">
<meta name="twitter:description" content="{{ $__desc }}">
<meta name="twitter:image" content="{{ $__ogImg }}">
<script type="application/ld+json">{!! json_encode($__schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush



@push('styles')
<style>

      *,
      *::before,
      *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      :root {
        --green-dark: #2d4a3e;
        --green-mid: #3d6b59;
        --green-light: #e8f0eb;
        --ivory: #f7f3ee;
        --ivory-dark: #ede8e1;
        --gold: #c9a96e;
        --gold-light: #e8d5b0;
        --white: #fdfcfb;
        --text-dark: #1a1a1a;
        --text-mid: #4a4a4a;
        --text-muted: #6b6560;
        --border: #e0d9d0;
        --shadow-soft: 0 4px 24px rgba(45, 74, 62, 0.08);
        --shadow-card: 0 2px 16px rgba(45, 74, 62, 0.07);
        --radius-card: 8px;
        --radius-btn: 4px;
      }

      html {
        scroll-behavior: smooth;
      }
      body {
        font-family: "DM Sans", sans-serif;
        background: var(--ivory);
        color: var(--text-dark);
        font-size: 16px;
        line-height: 1.6;
        overflow-x: hidden;
      }

      /* ── NAVBAR ── */
      nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 60px;
        height: 68px;
        background: rgba(253, 252, 251, 0.95);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(224, 217, 208, 0.6);
        box-shadow: var(--shadow-soft);
      }
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
        align-items: center;
      }
      .nav-links a {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-mid);
        text-decoration: none;
        letter-spacing: 0.02em;
        transition: color 0.2s;
      }
      .nav-links a:hover,
      .nav-links a.active {
        color: var(--green-dark);
      }
      .btn-nav {
        background: var(--green-dark);
        color: var(--ivory) !important;
        padding: 10px 22px;
        border-radius: var(--radius-btn);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition:
          background 0.2s,
          transform 0.15s;
      }
      .btn-nav:hover {
        background: var(--green-mid);
        transform: translateY(-1px);
      }

      .hamburger {
        display: none;
        flex-direction: column;
        gap: 5px;
        cursor: pointer;
        padding: 4px;
      }
      .hamburger span {
        display: block;
        width: 22px;
        height: 2px;
        background: var(--green-dark);
        border-radius: 2px;
        transition: all 0.3s;
      }
      .hamburger.open span:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
      }
      .hamburger.open span:nth-child(2) {
        opacity: 0;
      }
      .hamburger.open span:nth-child(3) {
        transform: translateY(-7px) rotate(-45deg);
      }

      .mobile-menu {
        display: none;
        position: fixed;
        top: 68px;
        left: 0;
        right: 0;
        background: var(--white);
        border-bottom: 1px solid var(--border);
        padding: 20px 24px 28px;
        z-index: 99;
        flex-direction: column;
        gap: 4px;
        box-shadow: var(--shadow-soft);
      }
      .mobile-menu.open {
        display: flex;
      }
      .mobile-menu a {
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-mid);
        text-decoration: none;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
      }
      .mobile-menu .btn-nav-mobile {
        margin-top: 12px;
        background: var(--green-dark);
        color: var(--ivory);
        text-align: center;
        padding: 12px;
        border-radius: var(--radius-btn);
        border-bottom: none;
      }

      /* ── PAGE HEADER ── */
      .page-header {
        position: relative;
        padding-top: 200px;
        padding-bottom: 130px;
        overflow: hidden;
      }
      .page-header-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #1a2e27 0%, #2d4a3e 40%, #1a1a1a 100%);
      }
      .page-header-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url('{{ \App\Models\Setting::get("hero_photo") ? asset("storage/" . \App\Models\Setting::get("hero_photo")) : asset("assets/HeroLand.jpg") }}') center/cover no-repeat;
        opacity: 0.35;
        mix-blend-mode: luminosity;
      }
      .page-header-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(26,46,39,0.88) 50%, rgba(26,26,26,0.25) 100%);
      }
      .page-header .container {
        position: relative;
        z-index: 2;
      }
      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 60px;
      }
      .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.6);
        margin-bottom: 14px;
        flex-wrap: wrap;
      }
      .breadcrumb a {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        transition: color 0.2s;
      }
      .breadcrumb a:hover {
        color: #fff;
      }
      .breadcrumb span {
        color: rgba(255,255,255,0.35);
      }
      .breadcrumb .current {
        color: rgba(255,255,255,0.9);
        font-weight: 500;
      }
      .page-title {
        font-family: "Cormorant Garamond", serif;
        font-size: clamp(2.4rem, 4.5vw, 3.8rem);
        font-weight: 600;
        color: #FDFCFB;
        line-height: 1.1;
        margin-bottom: 8px;
        text-align: center;
        margin-bottom: 20px;
      }
      .page-subtitle {
            max-width: 600px;
            margin: 0 auto;
            font-size: 1rem;
            color: rgba(253, 252, 251, 0.7);
            text-align: center;
            font-weight: 300;
            line-height: 1.7;
            opacity: 0;
            animation: fadeUp 0.8s 0.6s forwards;
        }

      /* ── MAIN LAYOUT ── */
      .catalog-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 36px;
        padding-top: 48px;
        padding-bottom: 80px;
        align-items: start;
      }

      /* ── SIDEBAR ── */
      .sidebar {
        position: sticky;
        top: 88px;
        background: var(--white);
        border-radius: var(--radius-card);
        border: 1px solid var(--border);
        padding: 28px 24px;
        box-shadow: var(--shadow-card);
      }

      .filter-group {
        margin-bottom: 28px;
      }
      .filter-group:last-child {
        margin-bottom: 0;
      }

      .filter-title {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-dark);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
      }

      .filter-option {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        cursor: pointer;
      }
      .filter-option input[type="checkbox"] {
        appearance: none;
        width: 16px;
        height: 16px;
        border: 1.5px solid var(--border);
        border-radius: 3px;
        cursor: pointer;
        position: relative;
        flex-shrink: 0;
        transition: all 0.2s;
      }
      .filter-option input[type="checkbox"]:checked {
        background: var(--green-dark);
        border-color: var(--green-dark);
      }
      .filter-option input[type="checkbox"]:checked::after {
        content: "";
        position: absolute;
        left: 4px;
        top: 1px;
        width: 5px;
        height: 9px;
        border: 2px solid white;
        border-top: none;
        border-left: none;
        transform: rotate(45deg);
      }
      .filter-option label {
        font-size: 0.85rem;
        color: var(--text-mid);
        cursor: pointer;
        font-weight: 400;
        transition: color 0.2s;
      }
      .filter-option:hover label {
        color: var(--green-dark);
      }

      /* Price range dual slider */
      .price-dual-wrap { position: relative; height: 20px; margin: 8px 0; }
      .price-dual-wrap input[type=range] {
        position: absolute; width: 100%; pointer-events: none;
        -webkit-appearance: none; height: 4px; background: transparent; outline: none;
        top: 50%; transform: translateY(-50%); z-index: 3;
      }
      .price-dual-wrap input[type=range]::-webkit-slider-thumb {
        pointer-events: all; -webkit-appearance: none;
        width: 18px; height: 18px; border-radius: 50%;
        background: var(--green-dark); cursor: pointer;
        border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.25);
      }
      .price-track {
        position: absolute; top: 50%; transform: translateY(-50%);
        height: 4px; width: 100%; border-radius: 4px;
        background: var(--border, #ddd); z-index: 1;
      }
      .price-track-fill {
        position: absolute; height: 4px; top: 50%; transform: translateY(-50%);
        background: var(--green-mid); border-radius: 4px; z-index: 2;
      }
      /* Price range */
      .price-range-inputs {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.78rem;
        color: var(--text-muted);
      }
      .price-slider {
        width: 100%;
        appearance: none;
        height: 3px;
        background: linear-gradient(to right, var(--green-dark) 0%, var(--border) 0%);
        border-radius: 2px;
        outline: none;
        cursor: pointer;
      }
      .price-slider::-webkit-slider-thumb {
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--green-mid);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(45, 74, 62, 0.3);
      }

      /* Mobile filter toggle */
      .filter-toggle-btn {
        display: none;
        align-items: center;
        gap: 8px;
        background: var(--white);
        border: 1.5px solid var(--border);
        color: var(--text-dark);
        padding: 10px 18px;
        border-radius: var(--radius-btn);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
      }
      .filter-toggle-btn:hover {
        border-color: var(--green-dark);
        color: var(--green-dark);
      }

      /* ── PRODUCT AREA ── */

      .product-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
      }
      .results-count {
        font-size: 0.83rem;
        color: var(--text-muted);
        font-weight: 300;
      }
      .results-count strong {
        color: var(--text-dark);
        font-weight: 500;
      }
      .toolbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .sort-select {
        appearance: none;
        background: var(--white);
        border: 1.5px solid var(--border);
        color: var(--text-dark);
        padding: 9px 36px 9px 14px;
        border-radius: var(--radius-btn);
        font-size: 0.83rem;
        font-weight: 500;
        font-family: "DM Sans", sans-serif;
        cursor: pointer;
        outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B6560' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: border-color 0.2s;
      }
      .sort-select:hover,
      .sort-select:focus {
        border-color: var(--green-dark);
      }

      /* ── PRODUCT GRID ── */
      .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px;
      }

      .product-card {
        background: var(--white);
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        border: 1px solid rgba(224, 217, 208, 0.5);
        transition:
          transform 0.25s,
          box-shadow 0.25s;
        cursor: pointer;
        text-decoration: none;
        display: block;
      }
      .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 32px rgba(45, 74, 62, 0.12);
      }

      /* wrapper card agar form bisa ada di dalam */
      .product-card-wrap {
        background: var(--white);
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        border: 1px solid rgba(224, 217, 208, 0.5);
        transition: transform 0.25s, box-shadow 0.25s;
        display: flex;
        flex-direction: column;
      }
      .product-card-wrap:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 32px rgba(45, 74, 62, 0.12);
      }
      .product-card-wrap .product-card {
        box-shadow: none;
        border: none;
        border-radius: 0;
        flex: 1;
      }
      .product-card-wrap .product-card:hover {
        transform: none;
        box-shadow: none;
      }
      .product-card-actions {
        padding: 0 14px 14px;
      }

      /* ── Baris tombol kartu produk ── */
      .card-btn-row {
        display: flex;
        gap: 8px;
        align-items: stretch;
      }
      .card-btn-row form {
        margin: 0;
        display: flex;
      }

      /* Tombol icon keranjang (kotak kecil) */
      .btn-cart-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--green-light);
        color: var(--green-dark);
        border: 1.5px solid var(--green-dark);
        width: 42px;
        height: 42px;
        border-radius: var(--radius-btn);
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
      }
      .btn-cart-icon:hover {
        background: var(--green-dark);
        color: var(--ivory);
      }

      /* Tombol Checkout */
      .btn-checkout-now {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--green-dark);
        color: var(--ivory);
        border: none;
        padding: 10px 0;
        border-radius: var(--radius-btn);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background 0.2s;
        flex: 1;
        text-decoration: none;
        text-align: center;
        letter-spacing: 0.01em;
      }
      .btn-checkout-now:hover {
        background: var(--green-mid);
        color: var(--ivory);
      }

      /* Stok habis full-width */
      .btn-add-cart {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: var(--green-dark);
        color: var(--ivory);
        border: none;
        padding: 10px 18px;
        border-radius: var(--radius-btn);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background 0.2s;
        width: 100%;
        text-decoration: none;
        text-align: center;
      }
      .btn-add-cart:hover {
        background: var(--green-mid);
        color: var(--ivory);
      }
      .btn-add-cart:disabled {
        background: var(--border);
        color: var(--text-muted);
        cursor: not-allowed;
      }

      @media (max-width: 480px) {
        .btn-cart-icon { width: 38px; height: 38px; }
        .btn-checkout-now { font-size: 0.78rem; }
      }

      .product-img {
        position: relative;
        aspect-ratio: 4/5;
        overflow: hidden;
        background: var(--ivory-dark);
      }
      .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
      }
      .product-card:hover .product-img img {
        transform: scale(1.04);
      }

      /* badges */
      .badge {
        position: absolute;
        top: 12px;
        left: 12px;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 2px;
        z-index: 2;
      }
      .badge-sale {
        background: #c0392b;
        color: white;
      }
      .badge-new {
        background: var(--green-dark);
        color: var(--ivory);
      }
      .badge-out {
        position: absolute;
        inset: 0;
        background: rgba(26, 26, 26, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
      }
      .badge-out span {
        background: rgba(26, 26, 26, 0.85);
        color: white;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 7px 18px;
        border-radius: 2px;
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .product-info {
        padding: 16px 18px 20px;
        text-align: center;
      }
      .product-name {
        font-family: "Cormorant Garamond", serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
        line-height: 1.3;
      }
      .product-card.out-of-stock .product-name {
        color: var(--text-muted);
      }
      .product-price {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
      }
      .price-original {
        font-size: 0.82rem;
        color: var(--text-muted);
        text-decoration: line-through;
        font-weight: 400;
        margin-right: 6px;
      }
      .price-discount {
        color: var(--green-dark);
        font-weight: 700;
      }

      /* ── PAGINATION ── */
      .pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
      }
      .page-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-btn);
        border: 1.5px solid var(--border);
        background: var(--white);
        color: var(--text-mid);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-family: "DM Sans", sans-serif;
      }
      .page-btn:hover {
        border-color: var(--green-dark);
        color: var(--green-dark);
      }
      .page-btn.active {
        background: var(--green-dark);
        border-color: var(--green-dark);
        color: var(--ivory);
      }
      .page-btn.arrow {
        font-size: 1rem;
        color: var(--text-muted);
      }
      .page-dots {
        color: var(--text-muted);
        font-size: 0.85rem;
        padding: 0 4px;
      }

      /* ── FOOTER ── */
      footer {
        background: var(--green-dark);
        color: rgba(253, 252, 251, 0.75);
      }
      .footer-top {
        padding: 64px 0 40px;
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr 1.2fr;
        gap: 48px;
      }
      .footer-brand .footer-logo {
        font-family: "Cormorant Garamond", serif;
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
        width: 22px;
        height: 22px;
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
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid rgba(253, 252, 251, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(253, 252, 251, 0.7);
        text-decoration: none;
        transition: all 0.2s;
        text-transform: uppercase;
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
      .footer-col ul {
        list-style: none;
      }
      .footer-col ul li {
        margin-bottom: 10px;
      }
      .footer-col ul li a {
        font-size: 0.83rem;
        color: rgba(253, 252, 251, 0.65);
        text-decoration: none;
        font-weight: 300;
        transition: color 0.2s;
      }
      .footer-col ul li a:hover {
        color: var(--gold-light);
      }
      .footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.83rem;
        color: rgba(253, 252, 251, 0.65);
        font-weight: 300;
      }
      .footer-contact-item span:first-child {
        color: var(--gold);
        flex-shrink: 0;
        margin-top: 2px;
      }
      .footer-bottom {
        border-top: 1px solid rgba(253, 252, 251, 0.1);
        padding: 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .footer-bottom p {
        font-size: 0.78rem;
        color: rgba(253, 252, 251, 0.4);
        font-weight: 300;
      }
      .footer-bottom-links {
        display: flex;
        gap: 20px;
      }
      .footer-bottom-links a {
        font-size: 0.78rem;
        color: rgba(253, 252, 251, 0.4);
        text-decoration: none;
        font-weight: 300;
        transition: color 0.2s;
      }
      .footer-bottom-links a:hover {
        color: rgba(253, 252, 251, 0.7);
      }

      /* ── MOBILE SIDEBAR OVERLAY ── */
      .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 200;
      }
      .sidebar-overlay.open {
        display: block;
      }
      .sidebar-drawer {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 300px;
        background: var(--white);
        z-index: 201;
        padding: 28px 24px;
        overflow-y: auto;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      .sidebar-drawer.open {
        transform: translateX(0);
      }
      .drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
      }
      .drawer-title {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-dark);
      }
      .drawer-close {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 1.2rem;
        background: none;
        border: none;
      }

      /* ── SCROLL REVEAL ── */
      .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition:
          opacity 0.6s ease,
          transform 0.6s ease;
      }
      .reveal.visible {
        opacity: 1;
        transform: none;
      }
      .reveal-delay-1 {
        transition-delay: 0.05s;
      }
      .reveal-delay-2 {
        transition-delay: 0.1s;
      }
      .reveal-delay-3 {
        transition-delay: 0.15s;
      }
      .reveal-delay-4 {
        transition-delay: 0.2s;
      }
      .reveal-delay-5 {
        transition-delay: 0.25s;
      }
      .reveal-delay-6 {
        transition-delay: 0.3s;
      }

      /* ── RESPONSIVE ── */
      @media (max-width: 1024px) {
        nav {
          padding: 0 32px;
        }
        .container {
          padding: 0 32px;
        }
        .catalog-layout {
          grid-template-columns: 210px 1fr;
          gap: 24px;
        }
        .product-grid {
          grid-template-columns: repeat(2, 1fr);
        }
        .footer-top {
          grid-template-columns: 1fr 1fr;
          gap: 32px;
        }
      }

      @media (max-width: 768px) {
        nav {
          padding: 0 20px;
        }
        .nav-links,
        .btn-nav {
          display: none;
        }
        .hamburger {
          display: flex;
        }
        .container {
          padding: 0 20px;
        }

        .catalog-layout {
          grid-template-columns: 1fr;
          gap: 0;
        }
        .sidebar {
          display: none;
        }
        .filter-toggle-btn {
          display: flex;
        }

        .product-toolbar {
          flex-direction: row;
          align-items: center;
        }
        .product-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 14px;
        }

        .footer-top {
          grid-template-columns: 1fr;
          gap: 28px;
          padding: 44px 0 32px;
        }
        .footer-bottom {
          flex-direction: column;
          gap: 12px;
          text-align: center;
        }
      }

      .footer-contact-item a {
        color: rgba(253, 252, 251, 0.65);
        text-decoration: none;
      }
      .footer-contact-item a:hover {
        color: var(--gold-light);
      }

      @media (max-width: 480px) {
        .product-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 10px;
        }
        .product-info {
          padding: 12px 12px 14px;
        }
        .product-name {
          font-size: 0.95rem;
        }
      }

</style>
@endpush

@section('content')
<!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
      <a href="{{ route('public.landing') }}">Home</a>
      <a href="{{ route('public.katalog') }}">Katalog</a>
      <a href="{{ route('public.tentang') }}">Tentang Kami</a>
    </div>

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div
      class="sidebar-overlay"
      id="sidebarOverlay"
      onclick="closeSidebar()"
    ></div>
    <div class="sidebar-drawer" id="sidebarDrawer">
      <div class="drawer-header">
        <span class="drawer-title">Filter Produk</span>
        <button class="drawer-close" onclick="closeSidebar()">✕</button>
      </div>
      <form method="GET" action="{{ route('public.katalog') }}" id="filter-form-mobile">
        @if(request('sort'))
          <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif

        {{-- Kategori --}}
        <div class="filter-group">
          <div class="filter-title">Kategori</div>
          @foreach($categories as $cat)
          <div class="filter-option">
            <input type="checkbox" id="m-cat-{{ $cat->id }}" name="kategori[]" value="{{ $cat->slug }}"
              {{ in_array($cat->slug, (array) request('kategori', [])) ? 'checked' : '' }} />
            <label for="m-cat-{{ $cat->id }}">{{ $cat->name }}</label>
          </div>
          @endforeach
        </div>

        {{-- Status --}}
        <div class="filter-group">
          <div class="filter-title">Status</div>
          @foreach(['available' => 'Tersedia', 'low' => 'Stok Rendah', 'out' => 'Habis'] as $val => $label)
          <div class="filter-option">
            <input type="radio" id="m-status-{{ $val }}" name="status" value="{{ $val }}"
              {{ request('status') == $val ? 'checked' : '' }} />
            <label for="m-status-{{ $val }}">{{ $label }}</label>
          </div>
          @endforeach
        </div>

        {{-- Harga --}}
        <div class="filter-group">
          <div class="filter-title">Harga</div>
          <div class="price-range-inputs" style="justify-content:space-between;margin-top:8px;">
            <span id="m-price-min-label">Rp {{ number_format(request('min_price', 0), 0, ',', '.') }}</span>
            <span id="m-price-max-label">Rp {{ number_format(request('max_price', 5000000), 0, ',', '.') }}</span>
          </div>
          <div class="price-dual-wrap" id="m-price-wrap">
            <div class="price-track"></div>
            <div class="price-track-fill" id="m-price-fill"></div>
            <input type="range" id="m-price-min-slider" min="0" max="5000000" step="50000"
              value="{{ request('min_price', 0) }}" oninput="updateDualPriceMobile()">
            <input type="range" id="m-price-max-slider" min="0" max="5000000" step="50000"
              value="{{ request('max_price', 5000000) }}" oninput="updateDualPriceMobile()">
          </div>
        </div>

        <div style="padding:16px 0 8px;">
          <button type="button" onclick="applyMobileFilter()"
            style="width:100%;padding:12px;background:var(--green-dark);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;">
            Terapkan Filter
          </button>
          @if(request()->hasAny(['kategori','status','min_price','max_price']))
          <a href="{{ route('public.katalog') }}"
            style="display:block;text-align:center;font-size:0.8rem;color:var(--green-dark);font-weight:600;margin-top:10px;text-decoration:underline;">
            Reset Semua Filter
          </a>
          @endif
        </div>
      </form>
    </div>

    <!-- PAGE HEADER -->
    <div class="page-header">
      <div class="page-header-bg"></div>
      <div class="page-header-overlay"></div>
      <div class="container">
        <h1 class="page-title reveal">Koleksi Kami</h1>
        <p class="page-subtitle reveal">
          Rangkaian bunga pilihan dan paket hadiah khusus untuk setiap momen berharga.
        </p>
      </div>
    </div>

    <!-- CATALOG LAYOUT -->
    <div class="container">
      <div class="catalog-layout">
                <!-- SIDEBAR (Desktop) -->
        <form method="GET" action="{{ route('public.katalog') }}" id="filter-form">
          {{-- Preserve sort --}}
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
          @endif

          <aside class="sidebar">

            {{-- Kategori --}}
            <div class="filter-group">
              <div class="filter-title">Kategori</div>
              @foreach($categories as $cat)
              <div class="filter-option">
                <input type="checkbox" id="cat-{{ $cat->id }}" name="kategori[]" value="{{ $cat->slug }}"
                  {{ in_array($cat->slug, (array) request('kategori', [])) ? 'checked' : '' }}
                  onchange="document.getElementById('filter-form').submit()" />
                <label for="cat-{{ $cat->id }}">{{ $cat->name }}</label>
              </div>
              @endforeach
            </div>

            {{-- Status --}}
            <div class="filter-group">
              <div class="filter-title">Status</div>
              @foreach(['available' => 'Tersedia', 'low' => 'Stok Rendah', 'out' => 'Habis'] as $val => $label)
              <div class="filter-option">
                <input type="radio" id="status-{{ $val }}" name="status" value="{{ $val }}"
                  {{ request('status') == $val ? 'checked' : '' }}
                  onchange="document.getElementById('filter-form').submit()" />
                <label for="status-{{ $val }}">{{ $label }}</label>
              </div>
              @endforeach
              @if(request('status'))
              <div style="margin-top:8px;">
                <a href="{{ route('public.katalog', request()->except('status','page')) }}"
                   style="font-size:0.75rem;color:var(--text-muted);text-decoration:underline;">
                  Reset status
                </a>
              </div>
              @endif
            </div>

            {{-- Harga --}}
            <div class="filter-group">
              <div class="filter-title">Harga</div>
              <div class="price-range-inputs" style="justify-content:space-between;">
                <span id="price-min-label">Rp {{ number_format(request('min_price', 0), 0, ',', '.') }}</span>
                <span id="price-max-label">Rp {{ number_format(request('max_price', 5000000), 0, ',', '.') }}</span>
              </div>
              <div class="price-dual-wrap" id="price-wrap">
                <div class="price-track"></div>
                <div class="price-track-fill" id="price-fill"></div>
                <input type="range" id="price-min-slider" min="0" max="5000000" step="50000"
                  value="{{ request('min_price', 0) }}" oninput="updateDualPrice()">
                <input type="range" id="price-max-slider" min="0" max="5000000" step="50000"
                  value="{{ request('max_price', 5000000) }}" oninput="updateDualPrice()">
              </div>
              <button type="button" onclick="applyDesktopPrice()"
                style="width:100%;margin-top:10px;padding:8px;background:var(--green-dark);color:#fff;border:none;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;">
                Terapkan Harga
              </button>
            </div>

            {{-- Reset semua --}}
            @if(request()->hasAny(['kategori','status','min_price','max_price']))
            <a href="{{ route('public.katalog') }}"
               style="display:block;text-align:center;font-size:0.8rem;color:var(--green-dark);font-weight:600;margin-top:4px;text-decoration:underline;">
              Reset Semua Filter
            </a>
            @endif

          </aside>
        </form>

        <!-- PRODUCT AREA -->
        <div class="product-area">
          <div class="product-toolbar reveal">
            <span class="results-count">
              @if($products->total() > 0)
                Menampilkan <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong> dari <strong>{{ $products->total() }} results</strong>
              @else
                Tidak ada produk ditemukan
              @endif
            </span>
            <div class="toolbar-right">
              <button class="filter-toggle-btn" onclick="openSidebar()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="4" y1="6" x2="20" y2="6" />
                  <line x1="8" y1="12" x2="16" y2="12" />
                  <line x1="11" y1="18" x2="13" y2="18" />
                </svg>
                Filter
              </button>
              <form method="GET" action="{{ route('public.katalog') }}" id="sort-form" style="margin:0;">
                <select class="sort-select" name="sort" onchange="document.getElementById('sort-form').submit()">
                  <option value="terbaru" {{ request('sort','terbaru')=='terbaru'?'selected':'' }}>Sort by: Terbaru</option>
                  <option value="harga_asc" {{ request('sort')=='harga_asc'?'selected':'' }}>Harga: Rendah ke Tinggi</option>
                  <option value="harga_desc" {{ request('sort')=='harga_desc'?'selected':'' }}>Harga: Tinggi ke Rendah</option>
                  <option value="terlama" {{ request('sort')=='terlama'?'selected':'' }}>Sort by: Terlama</option>
                </select>
              </form>
            </div>
          </div>

          <div class="product-grid">
            @forelse($products as $i => $product)
            @php
              $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3'];
              $delay = $delays[$i % 3];
              $img = $product->images->first();
              $isOut = $product->stock <= 0;
            @endphp
            <div class="product-card-wrap reveal {{ $delay }}">
              <a href="{{ route('public.produk', $product->slug) }}"
                 class="product-card {{ $isOut ? 'out-of-stock' : '' }}">
                <div class="product-img">
                  @if($img)
                    <img src="{{ $img->url }}" alt="{{ $product->name }}" loading="lazy" />
                  @else
                    <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600&q=80" alt="{{ $product->name }}" loading="lazy" />
                  @endif

                  {{-- Out of stock overlay --}}
                  @if($isOut)
                    <div class="badge-out"><span>Out of Stock</span></div>
                  @endif

                  {{-- Badge (Sale/New/dll) --}}
                  @if($product->badge && !$isOut)
                    @php
                      $badgeClass = strtolower($product->badge) === 'sale' ? 'badge-sale' : 'badge-new';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $product->badge }}</span>
                  @endif
                </div>
                <div class="product-info">
                  <div class="product-name">{{ $product->name }}</div>
                  <div class="product-price">
                    @if($product->discount_percent > 0)
                      <span class="price-original">{{ $product->formatted_price }}</span>
                      <span class="price-discount">{{ $product->formatted_final_price }}</span>
                    @else
                      {{ $product->formatted_price }}
                    @endif
                  </div>
                </div>
              </a>

              {{-- Tombol Keranjang + Checkout --}}
              <div class="product-card-actions">
                @if($isOut)
                  <button class="btn-add-cart" disabled>Stok Habis</button>
                @elseif(Auth::guard('customer')->check())
                  <div class="card-btn-row">
                    {{-- Icon Keranjang (AJAX - tanpa reload) --}}
                    <button type="button" class="btn-cart-icon" title="Tambah ke Keranjang"
                      onclick="addToCartAjax(this, {{ $product->id }})">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                    </button>
                    {{-- Checkout Langsung --}}
                    <form method="POST" action="{{ route('cart.add') }}" style="flex:1;">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <input type="hidden" name="quantity" value="1">
                      <input type="hidden" name="redirect_checkout" value="1">
                      <button type="submit" class="btn-checkout-now" style="width:100%;">
                        Checkout
                      </button>
                    </form>
                  </div>
                @else
                  <a href="{{ route('login') }}" class="btn-add-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pesan Sekarang
                  </a>
                @endif
              </div>
            </div>
            @empty
            <div style="grid-column:1/-1; text-align:center; padding:60px 20px; color:var(--text-muted);">
              <p style="font-size:1.1rem; margin-bottom:8px;">Belum ada produk tersedia.</p>
            </div>
            @endforelse
          </div>

          {{-- Pagination --}}
          @if($products->hasPages())
          <div class="pagination reveal">
            {{-- Prev --}}
            @if($products->onFirstPage())
              <span class="page-btn arrow" style="opacity:0.4;">‹</span>
            @else
              <a href="{{ $products->previousPageUrl() }}" class="page-btn arrow">‹</a>
            @endif

            {{-- Page numbers --}}
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
              @if($page == $products->currentPage())
                <span class="page-btn active">{{ $page }}</span>
              @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
              @endif
            @endforeach

            {{-- Next --}}
            @if($products->hasMorePages())
              <a href="{{ $products->nextPageUrl() }}" class="page-btn arrow">›</a>
            @else
              <span class="page-btn arrow" style="opacity:0.4;">›</span>
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- FOOTER -->
@endsection

@push('scripts')
<script>
  // ── Toast Notification ──────────────────────────────────
  function showToast(msg, type) {
    const existing = document.getElementById('cartToast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'cartToast';
    toast.style.cssText = [
      'position:fixed',
      'bottom:28px',
      'right:28px',
      'z-index:9999',
      'background:' + (type === 'error' ? '#dc2626' : '#2d4a3e'),
      'color:#f7f3ee',
      'padding:14px 20px',
      'border-radius:8px',
      'font-size:0.875rem',
      'font-weight:500',
      'font-family:DM Sans,sans-serif',
      'box-shadow:0 8px 32px rgba(0,0,0,0.18)',
      'display:flex',
      'align-items:center',
      'gap:10px',
      'opacity:0',
      'transform:translateY(12px)',
      'transition:opacity 0.25s,transform 0.25s',
      'max-width:320px',
    ].join(';');

    const icon = type === 'error'
      ? '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
      : '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';

    toast.innerHTML = icon + '<span>' + msg + '</span>';
    document.body.appendChild(toast);

    requestAnimationFrame(() => {
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';
    });

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(12px)';
      setTimeout(() => toast.remove(), 280);
    }, 2500);
  }

  // ── AJAX Tambah Keranjang (icon 🛒) ─────────────────────
  function addToCartAjax(btn, productId) {
    btn.disabled = true;
    btn.style.opacity = '0.6';

    fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ product_id: productId, quantity: 1 }),
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Update badge navbar desktop
        const badge = document.getElementById('navCartBadge');
        if (badge) {
          badge.textContent = data.cart_count;
          badge.style.display = '';
        }
        // Update badge navbar mobile
        const badgeMob = document.getElementById('navCartBadgeMob');
        if (badgeMob) {
          badgeMob.textContent = data.cart_count;
          badgeMob.style.display = '';
        }
        // Animasi icon hijau sebentar
        btn.style.background = '#2d4a3e';
        btn.style.color = '#f7f3ee';
        setTimeout(() => {
          btn.style.background = '';
          btn.style.color = '';
          btn.disabled = false;
          btn.style.opacity = '';
        }, 900);
        showToast('Produk ditambahkan ke keranjang!', 'success');
      } else {
        showToast(data.message || 'Gagal menambahkan produk.', 'error');
        btn.disabled = false;
        btn.style.opacity = '';
      }
    })
    .catch(() => {
      showToast('Terjadi kesalahan. Coba lagi.', 'error');
      btn.disabled = false;
      btn.style.opacity = '';
    });
  }

  const SLIDER_MAX = 5000000;

  // ── Desktop slider ──
  function updateDualPrice() {
    const minSlider = document.getElementById('price-min-slider');
    const maxSlider = document.getElementById('price-max-slider');
    if (!minSlider || !maxSlider) return;
    let minVal = parseInt(minSlider.value);
    let maxVal = parseInt(maxSlider.value);
    if (minVal > maxVal) { minVal = maxVal; minSlider.value = minVal; }
    document.getElementById('price-min-label').textContent = 'Rp ' + minVal.toLocaleString('id-ID');
    document.getElementById('price-max-label').textContent = 'Rp ' + maxVal.toLocaleString('id-ID');
    const pctMin = (minVal / SLIDER_MAX * 100).toFixed(1);
    const pctMax = (maxVal / SLIDER_MAX * 100).toFixed(1);
    const fill = document.getElementById('price-fill');
    if (fill) { fill.style.left = pctMin + '%'; fill.style.width = (pctMax - pctMin) + '%'; }
  }

  // ── Mobile slider ──
  function updateDualPriceMobile() {
    const minSlider = document.getElementById('m-price-min-slider');
    const maxSlider = document.getElementById('m-price-max-slider');
    if (!minSlider || !maxSlider) return;
    let minVal = parseInt(minSlider.value);
    let maxVal = parseInt(maxSlider.value);
    if (minVal > maxVal) { minVal = maxVal; minSlider.value = minVal; }
    document.getElementById('m-price-min-label').textContent = 'Rp ' + minVal.toLocaleString('id-ID');
    document.getElementById('m-price-max-label').textContent = 'Rp ' + maxVal.toLocaleString('id-ID');
    const fill = document.getElementById('m-price-fill');
    if (fill) {
      const pctMin = (minVal / SLIDER_MAX * 100).toFixed(1);
      const pctMax = (maxVal / SLIDER_MAX * 100).toFixed(1);
      fill.style.left = pctMin + '%'; fill.style.width = (pctMax - pctMin) + '%';
    }
  }

  // ── Terapkan harga desktop (redirect URL) ──
  function applyDesktopPrice() {
    const minVal = parseInt(document.getElementById('price-min-slider').value);
    const maxVal = parseInt(document.getElementById('price-max-slider').value);
    const url = new URL(window.location.href);
    url.searchParams.delete('page');
    // Hanya kirim jika bukan default
    if (minVal > 0) url.searchParams.set('min_price', minVal);
    else url.searchParams.delete('min_price');
    if (maxVal < SLIDER_MAX) url.searchParams.set('max_price', maxVal);
    else url.searchParams.delete('max_price');
    window.location.href = url.toString();
  }

  // ── Terapkan filter mobile (ambil kategori + status dari form, tambah harga) ──
  function applyMobileFilter() {
    const form = document.getElementById('filter-form-mobile');
    const url = new URL('{{ route("public.katalog") }}');

    // Kategori (checkbox)
    const cats = form.querySelectorAll('input[name="kategori[]"]:checked');
    cats.forEach(c => url.searchParams.append('kategori[]', c.value));

    // Status (radio)
    const status = form.querySelector('input[name="status"]:checked');
    if (status) url.searchParams.set('status', status.value);

    // Harga (slider)
    const minSlider = document.getElementById('m-price-min-slider');
    const maxSlider = document.getElementById('m-price-max-slider');
    if (minSlider && parseInt(minSlider.value) > 0)
      url.searchParams.set('min_price', minSlider.value);
    if (maxSlider && parseInt(maxSlider.value) < SLIDER_MAX)
      url.searchParams.set('max_price', maxSlider.value);

    // Sort (preserve)
    const sort = '{{ request("sort") }}';
    if (sort) url.searchParams.set('sort', sort);

    window.location.href = url.toString();
  }

  // ── Init slider fill on load ──
  document.addEventListener('DOMContentLoaded', function() {
    updateDualPrice();
    updateDualPriceMobile();
  });

  function openSidebar() {
    document.getElementById('sidebarOverlay').classList.add('open');
    document.getElementById('sidebarDrawer').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    document.getElementById('sidebarOverlay').classList.remove('open');
    document.getElementById('sidebarDrawer').classList.remove('open');
    document.body.style.overflow = '';
  }

  function toggleMenu() {
    const m = document.getElementById('mobileMenu');
    const h = document.getElementById('hamburger');
    if (m) m.classList.toggle('open');
    if (h) h.classList.toggle('open');
  }

  // Scroll reveal
  const revealEls = document.querySelectorAll('.reveal');
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); revealObserver.unobserve(e.target); }
    });
  }, { threshold: 0.1 });
  revealEls.forEach(el => revealObserver.observe(el));
</script>
@endpush
