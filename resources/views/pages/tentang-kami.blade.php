@extends('layouts.public')

@section('title', 'Tentang Kami — ' . \App\Models\Setting::get('site_name', 'TuquParcel'))
@push('seo')
@php
  $__site  = \App\Models\Setting::get('site_name', 'TuquParcel');
  $__logo  = \App\Models\Setting::get('site_logo', '');
  $__addr  = \App\Models\Setting::get('address', '');
  $__ogImg = $__logo ? asset('storage/' . $__logo) : asset('images/og-default.jpg');
  $__url   = route('public.tentang');
  $__title = 'Tentang Kami — ' . $__site;
  $__desc  = 'Kenali lebih dekat ' . $__site
           . ', toko bunga dan parsel hadiah terpercaya'
           . ($__addr ? ' di ' . $__addr : '')
           . '. Hadir sejak 2023 untuk membantu Anda mengabadikan momen spesial dengan rangkaian bunga segar berkualitas premium.';

  $__schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'AboutPage',
    'name'        => $__title,
    'description' => $__desc,
    'url'         => $__url,
    'isPartOf'    => ['@type' => 'WebSite', 'name' => $__site, 'url' => route('public.landing')],
  ];
@endphp
<meta name="description" content="{{ $__desc }}">
<meta name="keywords" content="tentang {{ strtolower($__site) }}, toko bunga, parcel hadiah terpercaya, florist profesional">
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
            --shadow-soft: 0 4px 24px rgba(45, 74, 62, 0.08);
            --shadow-card: 0 2px 16px rgba(45, 74, 62, 0.07);
            --radius-card: 8px;
            --radius-btn: 4px;
        }

        html {
            scroll-behavior: smooth;
        }

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
            transition: background 0.2s, transform 0.15s;
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

        /* ── CONTAINER ── */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 60px;
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            min-height: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            width: 100%;
            /* ← tambah ini */
            left: 0;
            /* ← tambah ini */
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #1a2e27 0%, #2d4a3e 50%, #1a1a1a 100%);
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('{{ \App\Models\Setting::get("hero_photo") ? asset("storage/" . \App\Models\Setting::get("hero_photo")) : asset("assets/HeroLand.jpg") }}') center/cover no-repeat;
            opacity: 0.4;
            mix-blend-mode: luminosity;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26, 40, 34, 0.65);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 100px 24px 60px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            /* ← pastikan ada */
        }

        .hero-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold-light);
            margin-bottom: 16px;
            opacity: 0;
            animation: fadeUp 0.8s 0.2s forwards;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 700;
            line-height: 1.05;
            color: #FDFCFB;
            margin-bottom: 16px;
            opacity: 0;
            animation: fadeUp 0.8s 0.4s forwards;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: rgba(253, 252, 251, 0.7);
            max-width: 480px;
            margin: 0 auto 28px;
            font-weight: 300;
            line-height: 1.7;
            opacity: 0;
            animation: fadeUp 0.8s 0.6s forwards;
        }

        .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid rgba(253, 252, 251, 0.6);
            color: var(--ivory);
            padding: 12px 28px;
            border-radius: var(--radius-btn);
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s;
            opacity: 0;
            animation: fadeUp 0.8s 0.8s forwards;
        }

        .btn-hero:hover {
            background: rgba(253, 252, 251, 0.15);
            border-color: rgba(253, 252, 251, 0.9);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── FROM GARDEN SECTION ── */
        .garden-section {
            padding: 96px 0;
            background: var(--white);
        }

        /* 2 foto berdampingan di atas */
        .garden-photos {
            display: flex;
            justify-content: center;
            margin-bottom: 56px;
        }

        .garden-img {
            border-radius: var(--radius-card);
            overflow: hidden;
            width: 100%;
            max-width: 560px;
            aspect-ratio: 2/3;
            min-height: 550px;
            background: var(--ivory-dark);
            box-shadow: var(--shadow-soft);
            position: relative;
            cursor: pointer;
        }

        .garden-img img {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.6s ease-in-out;
        }

        .garden-img img.garden-slide-active {
            opacity: 1;
        }

        .garden-click-hint {
            position: absolute;
            bottom: 18px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.35);
            color: #fff;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            padding: 5px 14px;
            border-radius: 20px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
        }

        .garden-img:hover .garden-click-hint {
            opacity: 1;
        }

        .garden-slide-dots {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 5;
        }

        .garden-slide-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            transition: background 0.3s;
        }

        .garden-slide-dots span.garden-dot-active {
            background: #fff;
        }

        /* Teks di atas, terpusat */
        .garden-text {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 56px;
        }

        .garden-line {
            width: 40px;
            height: 2px;
            background: var(--gold);
            margin: 0 auto 20px;
        }

        .garden-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 600;
            line-height: 1.15;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .garden-desc {
            font-size: 0.9rem;
            color: var(--text-mid);
            line-height: 1.85;
            font-weight: 300;
            margin-bottom: 14px;
        }

        .garden-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--green-dark);
            text-decoration: none;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-bottom: 1.5px solid var(--green-dark);
            padding-bottom: 2px;
            transition: opacity 0.2s;
            margin-top: 8px;
        }

        .garden-link:hover {
            opacity: 0.65;
        }

        /* ── CORE VALUES ── */
        .values-section {
            padding: 88px 0;
            background: var(--ivory);
        }

        .values-header {
            text-align: center;
            margin-bottom: 52px;
        }

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
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .value-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 40px 28px;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-soft);
        }

        .value-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.4rem;
            transition: all 0.25s;
        }

        .value-card:hover .value-icon {
            border-color: var(--green-dark);
            background: var(--green-light);
        }

        .value-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .value-desc {
            font-size: 0.83rem;
            color: var(--text-muted);
            line-height: 1.75;
            font-weight: 300;
        }

        /* ── SEAMLESS GIFTING ── */
        .gifting-section {
            padding: 88px 0;
            background: var(--green-dark);
        }

        .gifting-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 72px;
            align-items: center;
        }

        .gifting-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 700;
            line-height: 1.1;
            color: var(--ivory);
            margin-bottom: 16px;
        }

        .gifting-desc {
            font-size: 0.9rem;
            color: rgba(247, 243, 238, 0.7);
            line-height: 1.8;
            font-weight: 300;
            margin-bottom: 36px;
        }

        .gifting-steps {
            list-style: none;
        }

        .gifting-step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(201, 169, 110, 0.2);
            border: 1.5px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gold);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .step-title {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--ivory);
            margin-bottom: 3px;
        }

        .step-desc {
            font-size: 0.82rem;
            color: rgba(247, 243, 238, 0.6);
            font-weight: 300;
            line-height: 1.6;
        }

        .gifting-img {
            position: relative;
            border-radius: var(--radius-card);
            overflow: hidden;
            aspect-ratio: 4/5;
            background: rgba(255, 255, 255, 0.05);
        }

        .gifting-img img {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .gifting-img img.slide-active {
            opacity: 0.85;
        }

        .gifting-slide-dots {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 5;
        }

        .gifting-slide-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: background 0.3s;
        }

        .gifting-slide-dots span.dot-active {
            background: #fff;
        }

        .gifting-img-overlay {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .btn-start {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--ivory);
            color: var(--green-dark);
            padding: 12px 24px;
            border-radius: var(--radius-btn);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .btn-start:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
        }

        /* ── FOLLOW BLOOM ── */
        .follow-section {
            padding: 80px 0;
            background: var(--white);
        }

        .follow-inner {
            text-align: center;
            max-width: 520px;
            margin: 0 auto;
        }

        .follow-icon {
            font-size: 2rem;
            margin-bottom: 16px;
            color: var(--text-muted);
        }

        .follow-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .follow-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 300;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .btn-instagram {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--green-dark);
            color: var(--ivory);
            padding: 13px 28px;
            border-radius: var(--radius-btn);
            font-size: 0.88rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-decoration: none;
            transition: all 0.25s;
        }

        .btn-instagram:hover {
            background: var(--green-mid);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(45, 74, 62, 0.2);
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

        .footer-contact-item a {
            color: rgba(253, 252, 251, 0.65);
            text-decoration: none;
        }

        .footer-contact-item a:hover {
            color: var(--gold-light);
        }

        /* ── REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        .reveal-delay-1 {
            transition-delay: 0.08s;
        }

        .reveal-delay-2 {
            transition-delay: 0.16s;
        }

        .reveal-delay-3 {
            transition-delay: 0.24s;
        }

        .reveal-delay-4 {
            transition-delay: 0.32s;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            nav {
                padding: 0 32px;
            }

            .container {
                padding: 0 32px;
            }

            .garden-photos {
                gap: 0;
            }

            .gifting-grid {
                gap: 40px;
            }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 32px;
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

            .hero {
                min-height: 420px;
            }

            .hero-title {
                font-size: 2.6rem;
            }

            .garden-photos {
                gap: 0;
            }

            .garden-img {
                aspect-ratio: 3/4;
                max-width: 100%;
            }

            .values-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
            }

            .gifting-grid {
                grid-template-columns: 1fr;
                gap: 36px;
            }

            .gifting-img {
                aspect-ratio: 4/3;
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

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .team-avatar {
                width: 80px;
                height: 80px;
            }
        }

        /* Override hero-content dari layout utama */
        .hero.about-hero .hero-content {
            text-align: center;
            padding: 100px 24px 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
  @media (min-width: 769px) { .mobile-maps-btn { display: none !important; } }
@endpush

@section('content')
    <!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('public.landing') }}">Home</a>
        <a href="{{ route('public.katalog') }}">Katalog</a>
        <a href="{{ route('public.tentang') }}">Tentang Kami</a>
    </div>

    <!-- HERO -->
    <section class="hero about-hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-label">EST. 2020</p>
            <h1 class="hero-title">Cerita Kami</h1>
            <p class="hero-subtitle">Menghadirkan momen-momen penuh sukacita dengan bunga-bunga terindah dari alam dan paket yang dipilih dengan cermat.
            </p>
            <a href="#garden" class="btn-hero">Lihat cerita kami</a>
        </div>
    </section>

    <!-- FROM GARDEN TO YOUR DOOR -->
    <section class="garden-section" id="garden">
        <div class="container">
            <!-- Teks di atas, terpusat -->
            <div class="garden-text reveal">
                <div class="garden-line"></div>
                <h2 class="garden-title">Tuqu Parcel</h2>
                <p class="garden-desc">TuquParcel lahir dari kecintaan terhadap keindahan dan ketulusan dalam memberi. Sejak 2020, kami hadir di Malang sebagai toko bunga dan parcel yang merangkai setiap produk dengan tangan dan penuh perhatian — bukan sekadar hadiah, melainkan perasaan yang ingin kamu sampaikan kepada orang-orang terkasih.</p>
                <div id="gardenDescMore" style="display:none;">
                  <p class="garden-desc" style="margin-top:12px;">Kami percaya bahwa setiap momen berharga layak dirayakan dengan cara yang istimewa. Dari ulang tahun, wisuda, pernikahan, hingga sekadar ungkapan terima kasih — TuquParcel hadir untuk membantu kamu menemukan hadiah yang tepat, dikemas dengan cantik, dan dikirim dengan penuh tanggung jawab.</p>
                  <p class="garden-desc" style="margin-top:12px;">Setiap rangkaian bunga yang kami buat menggunakan bahan pilihan yang segar dan berkualitas. Setiap parcel disusun dengan teliti agar tiba di tangan penerima dalam kondisi sempurna. Karena bagi kami, kepuasan kamu dan senyum orang yang menerima hadiah adalah hal yang paling berarti.</p>
                </div>
                <button onclick="toggleGardenDesc(this)" style="margin-top:14px;background:none;border:none;padding:0;font-size:0.85rem;font-weight:600;color:var(--green-dark);cursor:pointer;display:flex;align-items:center;gap:6px;text-decoration:underline;text-underline-offset:3px;margin-left:auto;margin-right:auto;">
                  Baca selengkapnya
                  <svg id="gardenDescArrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform 0.3s;"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
            </div>
            @php
                $gardenPhotos = [];
                for ($gi = 1; $gi <= 5; $gi++) {
                    $gp = App\Models\Setting::get('team_photo_' . $gi, '');
                    if ($gp) $gardenPhotos[] = asset('storage/' . $gp);
                }
            @endphp
            <div class="garden-photos reveal">
                <div class="garden-img" id="gardenSlider" onclick="gardenNextSlide()">
                    @if(count($gardenPhotos) > 0)
                        @foreach($gardenPhotos as $gi => $src)
                            <img src="{{ $src }}" alt="Foto {{ $gi+1 }}" class="{{ $gi === 0 ? 'garden-slide-active' : '' }}" />
                        @endforeach
                        @if(count($gardenPhotos) > 1)
                        <div class="garden-slide-dots" id="gardenDots">
                            @foreach($gardenPhotos as $gi => $src)
                                <span class="{{ $gi === 0 ? 'garden-dot-active' : '' }}"></span>
                            @endforeach
                        </div>
                        <div class="garden-click-hint">Klik untuk foto berikutnya</div>
                        @endif
                    @else
                        <div style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;position:absolute;top:0;left:0;">
                            <span style="color:var(--text-muted);font-size:0.85rem;">Foto belum diupload</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- CORE VALUES -->
    <section class="values-section">
        <div class="container">
            <div class="values-header reveal">
                <h2 class="section-title">Mengapa Memilih Kami</h2>
                <p class="section-subtitle">Alasan pelanggan setia mempercayakan momen spesial mereka kepada kami.</p>
            </div>
            <div class="values-grid">
                <div class="value-card reveal reveal-delay-1">
                    <div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><path d="M17 11l-5-5-5 5M9 13l-2 2a2 2 0 000 2.83l.17.17a2 2 0 002.83 0l5-5a2 2 0 000-2.83L13 8"/><path d="M7 17l-4 4M17 7l4-4"/></svg></div>
                    <div class="value-name">500K+ Pelanggan</div>
                    <p class="value-desc">Dipercaya oleh ratusan ribu pelanggan di seluruh Indonesia untuk menghadirkan hadiah terbaik di setiap momen spesial.</p>
                </div>
                <div class="value-card reveal reveal-delay-2">
                    <div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
                    <div class="value-name"> 8000+ Pengikut</div>
                    <p class="value-desc">Jaringan Pengikut yang terus berkembang — dipercaya oleh komunitas luas yang mencintai keindahan rangkaian bunga kami.</p>
                </div>
                <div class="value-card reveal reveal-delay-3">
                    <div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><circle cx="12" cy="14" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke-width="1.8"/></svg></div>
                    <div class="value-name">6+ Tahun Pengalaman</div>
                    <p class="value-desc">Lebih dari satu dekade melayani dengan penuh dedikasi — pengalaman kami adalah jaminan kualitas dan keandalan untuk Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEAMLESS GIFTING -->
    <section class="gifting-section">
        <div class="container">
            <div class="gifting-grid">
                <div class="gifting-text reveal">
                    <h2 class="gifting-title">Keunggulan kami</h2>
                    <p class="gifting-desc">Kami hadir untuk memastikan setiap hadiah yang kamu kirim terasa istimewa
                         — dari pemilihan bunga hingga sampai di tangan penerimanya.</p>
                    <ul class="gifting-steps">
                        <li class="gifting-step">
                            <div class="step-circle">1</div>
                            <div class="step-content">
                                <div class="step-title">Pemesanan yang praktis</div>
                                <p class="step-desc">Cukup pilih produk dari katalog, screenshot, lalu hubungi
                                     kami via WhatsApp atau Instagram. Pesanan kamu langsung kami proses!
                                </p>
                            </div>
                        </li>
                        <li class="gifting-step">
                            <div class="step-circle">2</div>
                            <div class="step-content">
                                <div class="step-title">Produk Berkualitas</div>
                                <p class="step-desc">Setiap rangkaian bunga dan parsel kami dibuat dari bahan pilihan — segar,
                                     cantik, dan dikemas dengan rapi untuk memastikan kualitas terjaga sampai ke tangan penerimanya.</p>
                            </div>
                        </li>
                        <li class="gifting-step">
                            <div class="step-circle">3</div>
                            <div class="step-content">
                                <div class="step-title">Harga terbaik</div>
                                <p class="step-desc">
                                    Kami menawarkan harga yang kompetitif tanpa mengorbankan kualitas. Ada berbagai pilihan yang sesuai dengan
                                     budget kamu, mulai dari yang simpel hingga premium.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="gifting-img reveal" id="giftingSlider">
                    @php
                        $keunggulanPhotos = [];
                        for ($ki = 1; $ki <= 3; $ki++) {
                            $kp = App\Models\Setting::get('keunggulan_photo_' . $ki, '');
                            if ($kp) $keunggulanPhotos[] = asset('storage/' . $kp);
                        }
                        if (empty($keunggulanPhotos)) {
                            $keunggulanPhotos[] = asset('assets/Toko.jpg');
                        }
                    @endphp
                    @foreach($keunggulanPhotos as $i => $src)
                        <img src="{{ $src }}" alt="Keunggulan kami {{ $i+1 }}" class="{{ $i === 0 ? 'slide-active' : '' }}" data-slide="{{ $i }}" />
                    @endforeach
                    <div class="gifting-slide-dots" id="giftingDots">
                        @foreach($keunggulanPhotos as $i => $src)
                            <span class="{{ $i === 0 ? 'dot-active' : '' }}" data-dot="{{ $i }}"></span>
                        @endforeach
                    </div>
                    <div class="gifting-img-overlay">
                        <a href="{{ route('public.katalog') }}" class="btn-start">Mulai Memesan→</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOLLOW THE BLOOM -->
    <section class="follow-section">
        <div class="container">
            <div class="follow-inner reveal">
                <div class="follow-icon">
                  <svg viewBox="0 0 24 24" width="36" height="36" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                      <radialGradient id="ig-grad" cx="30%" cy="107%" r="150%">
                        <stop offset="0%" stop-color="#fdf497"/>
                        <stop offset="10%" stop-color="#fdf497"/>
                        <stop offset="30%" stop-color="#fd5949"/>
                        <stop offset="60%" stop-color="#d6249f"/>
                        <stop offset="90%" stop-color="#285AEB"/>
                      </radialGradient>
                    </defs>
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="url(#ig-grad)"/>
                    <circle cx="12" cy="12" r="4" fill="none" stroke="#fff" stroke-width="1.8"/>
                    <circle cx="17.5" cy="6.5" r="1.2" fill="#fff"/>
                  </svg>
                </div>
                <h2 class="follow-title">Follow Instagram Kami</h2>
                <p class="follow-desc">Gabung komunitas kami di Instagram untuk inspirasi bunga harian dan penawaran eksklusif.</p>
                <a href="https://instagram.com/TuquParcel" target="_blank" class="btn-instagram">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                        <circle cx="12" cy="12" r="3.5" />
                        <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- LOKASI TOKO -->
    @php
      $loc1     = \App\Models\Setting::get('address', '');
      $loc2     = \App\Models\Setting::get('address_2', '');
      $hasTwo   = $loc1 && $loc2;
      $map1q    = urlencode($loc1 ?: 'Malang, Indonesia');
      $map2q    = urlencode($loc2);
    @endphp

    <section class="location-section" style="background:var(--ivory,#F7F3EE);padding:80px 0;">
      <div class="container">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:48px;">
          <div class="section-label reveal">Temukan Kami</div>
          <h2 class="section-title reveal" style="margin-bottom:12px;">Lokasi Toko</h2>
          <p class="section-subtitle reveal" style="max-width:520px;margin:0 auto;">
            @if($hasTwo)
              Kami hadir di {{ $hasTwo ? '2 lokasi' : '1 lokasi' }} untuk melayani Anda lebih dekat.
            @else
              {{ $loc1 ?: 'Kunjungi toko kami untuk melihat koleksi langsung.' }}
            @endif
          </p>
        </div>

        {{-- Layout: 1 peta jika hanya 1 alamat, 2 peta berdampingan jika ada address_2 --}}
        <div style="display:grid;grid-template-columns:{{ $hasTwo ? '1fr 1fr' : '1fr' }};gap:28px;max-width:{{ $hasTwo ? '100%' : '900px' }};margin:0 auto;">

          {{-- PETA 1 --}}
          <div class="reveal" style="border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
            @if($hasTwo)
            <div style="background:var(--green-dark,#2D4A3E);padding:14px 20px;display:flex;align-items:center;gap:10px;">
              <span style="display:inline-flex;align-items:center;"><svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><path fill="#C9A96E" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></span>
              <div>
                <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:2px;">Toko 1</div>
                <div style="font-size:0.83rem;color:#fff;font-weight:500;line-height:1.4;">{{ $loc1 }}</div>
              </div>
            </div>
            @endif
            <iframe
              width="100%"
              height="{{ $hasTwo ? '320' : '420' }}"
              style="border:0;display:block;"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade"
              src="https://maps.google.com/maps?q={{ $map1q }}&output=embed&z=15">
            </iframe>
            <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;background:#fff;gap:12px;flex-wrap:wrap;">
              @if(!$hasTwo && $loc1)
              <span style="font-size:0.82rem;color:var(--text-muted,#6B6560);">{{ $loc1 }}</span>
              @endif
              <a href="https://maps.google.com/maps?q={{ $map1q }}" target="_blank"
                 style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:var(--green-dark,#2D4A3E);color:#fff;border-radius:8px;font-size:12.5px;font-weight:600;text-decoration:none;white-space:nowrap;margin-left:auto;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                Buka Maps
              </a>
            </div>
          </div>

          {{-- PETA 2 (hanya tampil jika address_2 diisi) --}}
          @if($hasTwo)
          <div class="reveal reveal-delay-2" style="border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
            <div style="background:var(--green-dark,#2D4A3E);padding:14px 20px;display:flex;align-items:center;gap:10px;">
              <span style="display:inline-flex;align-items:center;"><svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><path fill="#C9A96E" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></span>
              <div>
                <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:2px;">Toko 2</div>
                <div style="font-size:0.83rem;color:#fff;font-weight:500;line-height:1.4;">{{ $loc2 }}</div>
              </div>
            </div>
            <iframe
              width="100%"
              height="320"
              style="border:0;display:block;"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade"
              src="https://maps.google.com/maps?q={{ $map2q }}&output=embed&z=15">
            </iframe>
            <div style="padding:12px 16px;display:flex;align-items:center;justify-content:flex-end;background:#fff;">
              <a href="https://maps.google.com/maps?q={{ $map2q }}" target="_blank"
                 style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:var(--green-dark,#2D4A3E);color:#fff;border-radius:8px;font-size:12.5px;font-weight:600;text-decoration:none;white-space:nowrap;margin-left:auto;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                Buka Maps
              </a>
            </div>
          </div>
          @endif

        </div>

        {{-- Responsive: stack jadi vertikal di mobile --}}
        <style>
          @media (max-width: 768px) {
            .location-section .container > div[style*="grid-template-columns:1fr 1fr"] {
              grid-template-columns: 1fr !important;
            }
          }
        </style>

      </div>
    </section>

    @php
        $igUrl = \App\Models\Setting::get('instagram_url', 'https://instagram.com/tuquparcel');
        $igUsername = \App\Models\Setting::get('instagram_username', 'tuquparcel');
        $siteName = \App\Models\Setting::get('site_name', 'TuquParcel');
    @endphp
    <!-- FOOTER -->
  <script>
    if (window.innerWidth <= 915) {
      var btn = document.getElementById('mobileMapBtn');
      if (btn) btn.style.display = 'block';
    }
  </script>

@push('scripts')
<script>
  // Baca selengkapnya toggle
  function toggleGardenDesc(btn) {
    var more = document.getElementById('gardenDescMore');
    var arrow = document.getElementById('gardenDescArrow');
    var isOpen = more.style.display !== 'none';
    more.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
    btn.childNodes[0].textContent = isOpen ? 'Baca selengkapnya' : 'Sembunyikan';
  }

  // Garden slider — klik untuk ganti foto
  (function() {
    var slider = document.getElementById('gardenSlider');
    if (!slider) return;
    var imgs   = slider.querySelectorAll('img');
    var dots   = document.querySelectorAll('#gardenDots span');
    if (imgs.length <= 1) return;
    var current = 0;
    function goTo(n) {
      imgs[current].classList.remove('garden-slide-active');
      if (dots[current]) dots[current].classList.remove('garden-dot-active');
      current = (n + imgs.length) % imgs.length;
      imgs[current].classList.add('garden-slide-active');
      if (dots[current]) dots[current].classList.add('garden-dot-active');
    }
    window.gardenNextSlide = function() { goTo(current + 1); };
  })();

  (function() {
    var slider   = document.getElementById('giftingSlider');
    if (!slider) return;
    var imgs     = slider.querySelectorAll('img[data-slide]');
    var dots     = document.querySelectorAll('#giftingDots span');
    if (imgs.length <= 1) return;
    var current  = 0;
    function goTo(n) {
      imgs[current].classList.remove('slide-active');
      dots[current].classList.remove('dot-active');
      current = (n + imgs.length) % imgs.length;
      imgs[current].classList.add('slide-active');
      dots[current].classList.add('dot-active');
    }
    dots.forEach(function(dot, i) {
      dot.addEventListener('click', function() { goTo(i); clearInterval(timer); timer = setInterval(function(){ goTo(current + 1); }, 5000); });
    });
    var timer = setInterval(function() { goTo(current + 1); }, 5000);
  })();
</script>
@endpush

@endsection
