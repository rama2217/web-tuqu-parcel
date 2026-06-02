@extends('layouts.public')
@section('title', 'TuquParcel — Beranda')

@push('seo')
@php
  $__site   = \App\Models\Setting::get('site_name', 'TuquParcel');
  $__tag    = \App\Models\Setting::get('site_tagline', 'Temukan Hadiah yang Sempurna');
  $__logo   = \App\Models\Setting::get('site_logo', '');
  $__wa     = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', ''));
  $__addr   = \App\Models\Setting::get('address', '');
  $__ig     = \App\Models\Setting::get('instagram_url', '');
  $__ogImg  = $__logo ? asset('storage/' . $__logo) : asset('images/og-default.jpg');
  $__url    = route('public.landing');
  $__desc   = $__site . ' — ' . $__tag . '. Rangkaian bunga segar dan bingkisan eksklusif untuk ulang tahun, wisuda, pernikahan, dan momen spesial lainnya.';

  $__schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'LocalBusiness',
    'name'     => $__site,
    'description' => $__desc,
    'url'      => $__url,
    'priceRange' => '$$',
    'currenciesAccepted' => 'IDR',
    'openingHours' => 'Mo-Su 08:00-21:00',
  ];
  if ($__logo)    $__schema['logo'] = asset('storage/' . $__logo);
  if ($__addr)    $__schema['address'] = ['@type' => 'PostalAddress', 'addressLocality' => $__addr, 'addressCountry' => 'ID'];
  if ($__wa)      $__schema['telephone'] = '+' . $__wa;
  if ($__ig)      $__schema['sameAs'] = [$__ig];
@endphp
<meta name="description" content="{{ $__desc }}">
<meta name="keywords" content="buket bunga, parcel hadiah, rangkaian bunga segar, kado ulang tahun, bunga wisuda, {{ strtolower($__site) }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $__url }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $__site }}">
<meta property="og:title" content="{{ $__site }} — {{ $__tag }}">
<meta property="og:description" content="{{ $__desc }}">
<meta property="og:url" content="{{ $__url }}">
<meta property="og:image" content="{{ $__ogImg }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="id_ID">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $__site }} — {{ $__tag }}">
<meta name="twitter:description" content="{{ $__desc }}">
<meta name="twitter:image" content="{{ $__ogImg }}">
<script type="application/ld+json">{!! json_encode($__schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@section('content')
<!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('public.landing') }}">Home</a>
    <a href="{{ route('public.katalog') }}">Katalog</a>
    <a href="{{ route('public.tentang') }}">Tentang Kami</a>
  </div>

  @php
    $heroTagline  = \App\Models\Setting::get('site_tagline', 'Temukan Hadiah yang Sempurna');
    $siteName     = \App\Models\Setting::get('site_name', 'TuquParcel');
    $igUrl        = \App\Models\Setting::get('instagram_url', 'https://instagram.com/tuquparcel');
    $igUsername   = \App\Models\Setting::get('instagram', '@tuquparcel');
    $waRaw        = \App\Models\Setting::get('whatsapp', '');
    $waNum        = preg_replace('/[^0-9]/', '', $waRaw);
    $waUrl        = $waNum ? 'https://wa.me/' . $waNum . '?text=' . urlencode('Halo ' . $siteName . '! Saya ingin memesan produk. Apakah masih tersedia?') : '';
  @endphp

  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="hero-label">EST. 2020 — SEGAR SETIAP HARI</p>
      <h1 class="hero-title">{{ $heroTagline }}</h1>
      <p class="hero-subtitle">Rangkaian bunga segar dan bingkisan eksklusif yang dirancang dengan penuh cinta untuk mengabadikan momen spesial Anda.</p>
      <a href="{{ route('public.katalog') }}" class="btn-primary">
        Lihat Katalog
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </section>

  <!-- SHOP BY OCCASION -->
  @php
    $occasionDefaults = [
      1  => ['label' => 'Ulang Tahun',  'emoji' => '🎂'],
      2  => ['label' => 'Romance',      'emoji' => '💝'],
      3  => ['label' => 'Wisuda',       'emoji' => '🎓'],
      4  => ['label' => 'Grand Opening','emoji' => '🎊'],
      5  => ['label' => 'Get Well Soon','emoji' => '🌿'],
      6  => ['label' => 'Custom',       'emoji' => '✨'],
      7  => ['label' => 'Pernikahan',   'emoji' => '💍'],
      8  => ['label' => 'Baby Shower',  'emoji' => '👶'],
      9  => ['label' => 'Anniversary',  'emoji' => '🥂'],
      10 => ['label' => 'Lebaran',      'emoji' => '🌙'],
      11 => ['label' => 'Natal',        'emoji' => '🎄'],
      12 => ['label' => 'Lainnya',      'emoji' => '🎁'],
    ];
    $occasions = [];
    for ($oc = 1; $oc <= 12; $oc++) {
      $ocLabel = \App\Models\Setting::get("occasion_{$oc}_label", $occasionDefaults[$oc]['label']);
      $ocPhoto = \App\Models\Setting::get("occasion_{$oc}_photo", '');
      $ocLink  = \App\Models\Setting::get("occasion_{$oc}_link", '');
      if (!$ocLink) $ocLink = route('public.katalog');
      $occasions[] = [
        'label' => $ocLabel,
        'photo' => $ocPhoto ? asset('storage/' . $ocPhoto) : '',
        'emoji' => $occasionDefaults[$oc]['emoji'],
        'link'  => $ocLink,
      ];
    }
  @endphp
  <section class="occasion-section section-pad-sm">
    <div class="container">
      <p class="occasion-title reveal">Produk Unggulan Kami</p>

      {{-- Wrapper slider --}}
      <div class="oc-slider-wrapper">
        {{-- Tombol Kiri --}}
        <button class="oc-arrow oc-arrow-left" id="oc-prev" onclick="ocSlide(-1)" aria-label="Sebelumnya">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>

        {{-- Track --}}
        <div class="oc-track-outer">
          <div class="occasion-circle-grid" id="oc-track">
            @foreach($occasions as $idx => $occ)
            @php $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3','reveal-delay-1','reveal-delay-2','reveal-delay-3']; @endphp
            <a href="{{ $occ['link'] }}" class="occasion-circle-item reveal {{ $delays[$idx % 6] }}" data-oc-idx="{{ $idx }}">
              <div class="occasion-circle-img">
                @if($occ['photo'])
                  <img src="{{ $occ['photo'] }}" alt="{{ $occ['label'] }}" loading="lazy">
                @else
                  <div class="occasion-circle-fallback">
                    <span class="occasion-fallback-emoji">{{ $occ['emoji'] }}</span>
                  </div>
                @endif
              </div>
              <span class="occasion-circle-label">{{ strtoupper($occ['label']) }}</span>
            </a>
            @endforeach
          </div>
        </div>

        {{-- Tombol Kanan --}}
        <button class="oc-arrow oc-arrow-right" id="oc-next" onclick="ocSlide(1)" aria-label="Berikutnya">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>

      {{-- Dot indicator --}}
      <div class="oc-dots" id="oc-dots">
        <button class="oc-dot active" onclick="ocGoTo(0)"></button>
        <button class="oc-dot" onclick="ocGoTo(1)"></button>
      </div>
    </div>
  </section>
  <style>
    /* === OCCASION SLIDER === */
    .oc-slider-wrapper {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-top: 32px;
      position: relative;
    }
    .oc-track-outer {
      overflow: hidden;
      flex: 1;
    }
    .occasion-circle-grid {
      display: flex;
      gap: 28px;
      transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
    }
    .occasion-circle-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
      text-decoration: none;
      cursor: pointer;
      flex-shrink: 0;
      width: calc((100% - 28px * 5) / 6);
    }
    .occasion-circle-img {
      width: 100%;
      aspect-ratio: 1;
      border-radius: 50%;
      overflow: hidden;
      background: #f0ede8;
      position: relative;
      transition: transform 0.35s ease, box-shadow 0.35s ease;
      box-shadow: 0 2px 14px rgba(0,0,0,0.10);
    }
    .occasion-circle-item:hover .occasion-circle-img {
      transform: translateY(-5px);
      box-shadow: 0 12px 36px rgba(0,0,0,0.17);
    }
    .occasion-circle-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      border-radius: 50%;
      transition: transform 0.4s ease;
    }
    .occasion-circle-item:hover .occasion-circle-img img {
      transform: scale(1.06);
    }
    .occasion-circle-fallback {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #e8dfd4 0%, #d4c9bb 100%);
    }
    .occasion-fallback-emoji {
      font-size: 46px;
      line-height: 1;
      transition: transform 0.3s ease;
    }
    .occasion-circle-item:hover .occasion-fallback-emoji {
      transform: scale(1.15);
    }
    .occasion-circle-label {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.13em;
      color: #666;
      text-align: center;
      font-family: 'DM Sans', sans-serif;
      transition: color 0.2s;
    }
    .occasion-circle-item:hover .occasion-circle-label {
      color: var(--green-dark, #2D4A3E);
    }

    /* Tombol panah */
    .oc-arrow {
      width: 42px; height: 42px;
      border-radius: 50%;
      border: 1.5px solid #ddd;
      background: #fff;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      flex-shrink: 0;
      transition: all 0.2s;
      color: #555;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .oc-arrow:hover { border-color: var(--green-dark,#2D4A3E); color: var(--green-dark,#2D4A3E); box-shadow: 0 4px 14px rgba(0,0,0,0.14); }
    .oc-arrow:disabled { opacity: 0.3; cursor: default; pointer-events: none; }

    /* Dots */
    .oc-dots {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-top: 24px;
    }
    .oc-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      border: none;
      background: #ddd;
      cursor: pointer;
      padding: 0;
      transition: all 0.25s;
    }
    .oc-dot.active {
      background: var(--green-dark, #2D4A3E);
      width: 24px;
      border-radius: 4px;
    }

    @media (max-width: 960px) {
      .occasion-circle-item { width: calc((100% - 28px * 5) / 6); }
    }
    @media (max-width: 640px) {
      .oc-arrow { width: 34px; height: 34px; }
      .occasion-circle-grid { gap: 16px; }
      .occasion-circle-item { width: calc((100% - 16px * 2) / 3); }
      .occasion-fallback-emoji { font-size: 30px; }
      .occasion-circle-label { font-size: 10px; letter-spacing: 0.09em; }
    }
    @media (max-width: 380px) {
      .occasion-fallback-emoji { font-size: 24px; }
    }
  </style>
  <script>
    (function() {
      var page = 0;
      var perPage = window.innerWidth <= 640 ? 3 : 6;
      var total = 12;
      var pages = Math.ceil(total / perPage);

      function getItemWidth() {
        var track = document.getElementById('oc-track');
        if (!track) return 0;
        var item = track.querySelector('.occasion-circle-item');
        if (!item) return 0;
        var gap = window.innerWidth <= 640 ? 16 : 28;
        return item.offsetWidth + gap;
      }

      function updateSlider() {
        perPage = window.innerWidth <= 640 ? 3 : 6;
        pages = Math.ceil(total / perPage);
        if (page >= pages) page = pages - 1;

        var track = document.getElementById('oc-track');
        var offset = page * perPage * getItemWidth();
        track.style.transform = 'translateX(-' + offset + 'px)';

        // Update panah
        document.getElementById('oc-prev').disabled = page === 0;
        document.getElementById('oc-next').disabled = page >= pages - 1;

        // Update dots
        var dotsEl = document.getElementById('oc-dots');
        dotsEl.innerHTML = '';
        for (var i = 0; i < pages; i++) {
          var d = document.createElement('button');
          d.className = 'oc-dot' + (i === page ? ' active' : '');
          d.setAttribute('onclick', 'ocGoTo(' + i + ')');
          dotsEl.appendChild(d);
        }
      }

      window.ocSlide = function(dir) {
        page = Math.max(0, Math.min(pages - 1, page + dir));
        updateSlider();
      };

      window.ocGoTo = function(p) {
        page = p;
        updateSlider();
      };

      window.addEventListener('resize', function() { updateSlider(); });
      document.addEventListener('DOMContentLoaded', function() { updateSlider(); });
    })();
  </script>

  <!-- PRODUK TERLARIS -->
  <section class="products-section section-pad">
    <div class="container">
      <div class="section-header-row">
        <div class="reveal">
          <div class="section-label">Pilihan Favorit</div>
          <h2 class="section-title">Produk Terlaris</h2>
          <p class="section-subtitle">Pilihan favorit pelanggan kami minggu ini.</p>
          <div class="section-divider"></div>
        </div>
        <a href="{{ route('public.katalog') }}" class="link-all reveal">Lihat Semua →</a>
      </div>
      <div class="product-grid">
        @forelse($featuredProducts as $i => $fp)
        @php
          $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-delay-3','reveal-delay-4'];
          $delay = $delays[$i] ?? 'reveal-delay-1';
          $img = $fp->images->where('is_main', true)->first() ?? $fp->images->first();
          $imgUrl = $img ? asset('storage/' . $img->image_path) : 'https://images.unsplash.com/photo-1565085558534-5b46b8bfcdcd?w=600&q=80';
        @endphp
        <div class="product-card reveal {{ $delay }}">
          <div class="product-img">
            <img src="{{ $imgUrl }}" alt="{{ $fp->name }}" loading="lazy" />
            @if($fp->badge)
              <span class="product-badge">{{ $fp->badge }}</span>
            @endif
          </div>
          <div class="product-info">
            <div class="product-name">{{ $fp->name }}</div>
            <div class="product-price">
              @if($fp->discount_percent > 0)
                <span style="text-decoration:line-through;color:#aaa;font-size:12px;">Rp {{ number_format($fp->price, 0, ',', '.') }}</span>
                Rp {{ number_format($fp->price * (1 - $fp->discount_percent/100), 0, ',', '.') }}
              @else
                Rp {{ number_format($fp->price, 0, ',', '.') }}
              @endif
            </div>
            <a href="{{ route('public.produk', $fp->slug) }}" class="btn-detail">Lihat Detail</a>
          </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:40px;color:#aaa;">
          Belum ada produk unggulan.
        </div>
        @endforelse
      </div>
    </div>
  </section>

 <!-- KENAPA PILIH TUQUPARCEL -->
  <section class="why-section section-pad">
    <div class="container">
      <h2 class="why-title reveal">{{ App\Models\Setting::get('why_title', 'Kenapa Memilih ' . $siteName . '?') }}</h2>
      <div class="why-grid">
        <div class="why-card reveal reveal-delay-1">
          <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="36" height="36"><circle cx="12" cy="12" r="3"/><path d="M12 2a3 3 0 013 3c0 1.5-3 4-3 4S9 6.5 9 5a3 3 0 013-3z"/><path d="M12 22a3 3 0 01-3-3c0-1.5 3-4 3-4s3 2.5 3 4a3 3 0 01-3 3z"/><path d="M2 12a3 3 0 013-3c1.5 0 4 3 4 3S6.5 15 5 15a3 3 0 01-3-3z"/><path d="M22 12a3 3 0 01-3 3c-1.5 0-4-3-4-3s2.5-3 4-3a3 3 0 013 3z"/></svg></div>
          <h3>{{ App\Models\Setting::get('why_card1_title', 'Bunga Segar Premium') }}</h3>
          <p>{{ App\Models\Setting::get('why_card1_desc', 'Kami hanya menggunakan bunga kualitas grade A yang dipetik di pagi hari untuk memastikan kesegaran maksimal saat tiba di tangan Anda.') }}</p>
        </div>
        <div class="why-card reveal reveal-delay-2">
          <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="36" height="36"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg></div>
          <h3>{{ App\Models\Setting::get('why_card2_title', 'Desain Eksklusif') }}</h3>
          <p>{{ App\Models\Setting::get('why_card2_desc', 'Setiap rangkaian dirancang oleh florist profesional kami yang berpengalaman, unik untuk setiap pesanan, dan penuh keeleganan.') }}</p>
        </div>
        <div class="why-card reveal reveal-delay-3">
          <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="36" height="36"><path d="M21 10V7a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 7v10a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0022 17v-3"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/><polyline points="16 8 22 5"/></svg></div>
          <h3>{{ App\Models\Setting::get('why_card3_title', 'Pengiriman Aman') }}</h3>
          <p>{{ App\Models\Setting::get('why_card3_desc', 'Sarana pengiriman tepat waktu dengan packaging khusus yang terlindungi membawa parsel, memastikan hadiah sampai dalam kondisi sempurna.') }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CARA PEMESANAN -->
  <section class="how-section section-pad" id="cara-pemesanan">
    <div class="container">
      <h2 class="how-title reveal">Cara Pemesanan</h2>

      {{-- Tab Toggle --}}
      <div style="display:flex;justify-content:center;margin-bottom:40px;">
        <div style="display:inline-flex;background:#F0EBE4;border-radius:50px;padding:4px;gap:4px;">
          <button id="tabOnline" onclick="switchTab('online')"
            style="padding:10px 28px;border-radius:50px;border:none;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;background:#2D4A3E;color:#F7F3EE;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:6px;"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Order Online
          </button>
          <button id="tabManual" onclick="switchTab('manual')"
            style="padding:10px 28px;border-radius:50px;border:none;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;background:transparent;color:#6B6560;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:6px;"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>Via DM / WhatsApp
          </button>
        </div>
      </div>

      {{-- Tab Online --}}
      <div id="contentOnline" class="how-steps">
        <div class="how-step reveal reveal-delay-1">
          <div class="step-num">1</div>
          <div class="step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
          <div class="step-title">Pilih Produk</div>
          <p class="step-desc">Jelajahi katalog kami dan temukan hadiah yang sesuai dengan momen Anda.</p>
        </div>
        <div class="how-step reveal reveal-delay-2">
          <div class="step-num">2</div>
          <div class="step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div>
          <div class="step-title">Tambah ke Keranjang</div>
          <p class="step-desc">Masukkan produk ke keranjang dan isi data pengiriman Anda.</p>
        </div>
        <div class="how-step reveal reveal-delay-3">
          <div class="step-num">3</div>
          <div class="step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
          <div class="step-title">Checkout & Bayar</div>
          <p class="step-desc">Lakukan pembayaran via transfer bank atau QRIS dan upload bukti pembayaran.</p>
        </div>
      </div>

      {{-- Tab Manual --}}
      <div id="contentManual" class="how-steps" style="display:none;">
        <div class="how-step">
          <div class="step-num">1</div>
          <div class="step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
          <div class="step-title">Pilih Produk</div>
          <p class="step-desc">Jelajahi katalog kami dan temukan hadiah yang sesuai dengan momen Anda.</p>
        </div>
        <div class="how-step">
          <div class="step-num">2</div>
          <div class="step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
          <div class="step-title">Screenshot</div>
          <p class="step-desc">Ambil tangkapan layar (screenshot) produk yang Anda inginkan.</p>
        </div>
        <div class="how-step">
          <div class="step-num">3</div>
          <div class="step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="32" height="32"><path d="M22 16a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h16a2 2 0 012 2z"/><polyline points="22 8 12 13 2 8"/></svg></div>
          <div class="step-title">Hubungi Kami</div>
          <p class="step-desc">Kirim screenshot ke DM Instagram atau WhatsApp kami untuk konfirmasi pesanan.</p>
          <div style="display:flex;flex-direction:column;gap:8px;margin-top:12px;">
            <a href="{{ $igUrl }}" target="_blank" class="chat-link" style="display:inline-flex;align-items:center;gap:6px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
              Instagram {{ $igUsername }}
            </a>
            @if($waUrl)
            <a href="{{ $waUrl }}" target="_blank" class="chat-link" style="display:inline-flex;align-items:center;gap:6px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              WhatsApp {{ $waRaw }}
            </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
  function switchTab(tab) {
    const isOnline = tab === 'online';
    document.getElementById('contentOnline').style.display = isOnline ? 'grid' : 'none';
    document.getElementById('contentManual').style.display = isOnline ? 'none' : 'grid';
    document.getElementById('tabOnline').style.background = isOnline ? '#2D4A3E' : 'transparent';
    document.getElementById('tabOnline').style.color = isOnline ? '#F7F3EE' : '#6B6560';
    document.getElementById('tabManual').style.background = isOnline ? 'transparent' : '#2D4A3E';
    document.getElementById('tabManual').style.color = isOnline ? '#6B6560' : '#F7F3EE';
  }
  // Set default on load
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('contentOnline').style.display = 'grid';
    document.getElementById('contentManual').style.display = 'none';
  });
  </script>

  <!-- TESTIMONIAL -->
  @if($reviews->count() > 0)
  <section class="testi-section section-pad">
    <div class="container">
      <div class="testi-header-row">
        <h2 class="testi-section-title">Ulasan Pelanggan</h2>
        @php $reviewChunks = $reviews->chunk(3); @endphp
      </div>
      <div class="testi-viewport">
        <div class="testi-track" id="testiTrack">
          @foreach($reviewChunks as $pageIdx => $chunk)
          <div class="testi-page">
            <div class="testi-grid">
              @foreach($chunk as $review)
              <div class="testi-card">
                <div class="testi-card-header">
                  <div class="testi-stars">
                    @for($s = 1; $s <= 5; $s++)
                      <span style="{{ $s > $review->rating ? 'color:var(--border,#ddd)' : 'color:var(--gold-light,#c9a96e)' }}">★</span>
                    @endfor
                  </div>
                  <span class="testi-card-name">{{ $review->reviewer_name }}</span>
                </div>
                <p class="testi-card-text">"{{ $review->comment }}"</p>
                @if($review->photo)
                <img src="{{ asset('storage/' . $review->photo) }}" alt="Foto ulasan" class="testi-card-photo" loading="lazy" />
                @endif
                <div class="testi-card-role">{{ $review->product->name ?? '' }}</div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @if($reviewChunks->count() > 1)
      <div class="testi-nav">
        <button class="testi-arrow" id="testiPrev" onclick="testiGoPage(testiCurrentPage - 1)" aria-label="Sebelumnya">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        @foreach($reviewChunks as $pageIdx => $chunk)
        <button class="testi-nav-dot {{ $pageIdx === 0 ? 'testi-nav-dot-active' : '' }}"
          onclick="testiGoPage({{ $pageIdx }})" aria-label="Halaman {{ $pageIdx + 1 }}"></button>
        @endforeach
        <button class="testi-arrow" id="testiNext" onclick="testiGoPage(testiCurrentPage + 1)" aria-label="Berikutnya">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
      @endif
    </div>
  </section>
  <style>
    .testi-header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 36px;
    }
    .testi-section-title {
      font-family: var(--font-serif, Georgia, serif);
      font-size: clamp(1.8rem, 3.5vw, 2.6rem);
      color: var(--green-dark, #2d4a3e);
      font-weight: 400;
      letter-spacing: -0.01em;
      line-height: 1.2;
      margin: 0;
    }
    .testi-arrows {
      display: flex;
      gap: 10px;
      flex-shrink: 0;
    }
    .testi-arrow {
      width: 42px; height: 42px;
      border-radius: 50%;
      border: 1.5px solid var(--border, #e0d8ce);
      background: var(--ivory, #faf7f2);
      color: var(--green-dark, #2d4a3e);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, border-color 0.2s, opacity 0.2s;
    }
    .testi-arrow:hover {
      background: var(--green-dark, #2d4a3e);
      border-color: var(--green-dark, #2d4a3e);
      color: #fff;
    }
    .testi-arrow:disabled {
      opacity: 0.3;
      cursor: default;
      pointer-events: none;
    }
    .testi-viewport {
      overflow: hidden;
    }
    .testi-track {
      display: flex;
      transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
    }
    .testi-page {
      min-width: 100%;
    }
    .testi-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      align-items: start;
    }
    .testi-card {
      padding: 22px;
      border: 1px solid var(--border, #e8e0d5);
      border-radius: var(--radius-card, 16px);
      background: var(--ivory, #faf7f2);
      transition: box-shadow 0.2s;
    }
    .testi-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
    .testi-card-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
    }
    .testi-stars { font-size: 0.95rem; letter-spacing: 1px; }
    .testi-card-name {
      font-weight: 600;
      font-size: 0.88rem;
      color: var(--text, #2a2a2a);
    }
    .testi-card-text {
      font-style: italic;
      font-size: 0.9rem;
      color: var(--text-muted, #666);
      line-height: 1.6;
      margin-bottom: 12px;
    }
    .testi-card-photo {
      width: 100%;
      aspect-ratio: 4/3;
      object-fit: cover;
      border-radius: 10px;
      display: block;
      margin-bottom: 10px;
    }
    .testi-card-role {
      font-size: 0.75rem;
      color: var(--text-muted, #999);
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }
    .testi-nav {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-top: 24px;
    }
    .testi-nav-dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      border: none;
      background: var(--gold-light, #c9a96e);
      opacity: 0.3;
      cursor: pointer;
      padding: 0;
      transition: opacity 0.3s;
    }
    .testi-nav-dot.testi-nav-dot-active { opacity: 1; }
    @media (max-width: 768px) {
      .testi-grid { grid-template-columns: 1fr; }
    }
    @media (min-width: 769px) and (max-width: 1024px) {
      .testi-grid { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
  <script>
    (function() {
      var current = 0;
      var track = document.getElementById('testiTrack');
      var dots  = document.querySelectorAll('.testi-nav-dot');
      var prev  = document.getElementById('testiPrev');
      var next  = document.getElementById('testiNext');
      var total = track ? track.children.length : 0;

      function updateArrows() {
        if (prev) prev.disabled = current === 0;
        if (next) next.disabled = current === total - 1;
      }

      window.testiGoPage = function(n) {
        if (n < 0 || n >= total) return;
        if (dots[current]) dots[current].classList.remove('testi-nav-dot-active');
        current = n;
        if (track) track.style.transform = 'translateX(-' + (current * 100) + '%)';
        if (dots[current]) dots[current].classList.add('testi-nav-dot-active');
        updateArrows();
      };

      window.testiCurrentPage = 0;
      Object.defineProperty(window, 'testiCurrentPage', {
        get: function() { return current; },
        set: function(v) { current = v; }
      });

      updateArrows();
    })();
  </script>
  @endif

  <!-- FOOTER -->
@endsection
