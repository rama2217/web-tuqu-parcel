@extends('layouts.public')
@section('title', $product->name . ' — TuquParcel')


{{-- ============================================================
  PATCH FINAL — detail-produk.blade.php
  Taruh SETELAH: @section('title', $product->name . ' — TuquParcel')
  ============================================================ --}}
@push('seo')
@php
  $__site    = \App\Models\Setting::get('site_name', 'TuquParcel');
  $__logo    = \App\Models\Setting::get('site_logo', '');
  $__url     = route('public.produk', $product->slug);
  $__title   = $product->name . ' — ' . $__site;

  // Gambar utama produk
  $__mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
  $__ogImg   = $__mainImg
             ? asset('storage/' . $__mainImg->image_path)
             : ($__logo ? asset('storage/' . $__logo) : asset('images/og-default.jpg'));

  // Deskripsi — pakai substr+strip_tags, bukan Str:: untuk menghindari import issue
  $__rawDesc = $product->description ?? '';
  $__desc    = $__rawDesc
             ? substr(strip_tags($__rawDesc), 0, 155)
             : $product->name . ' — tersedia di ' . $__site . '. Rangkaian eksklusif untuk momen spesialmu.';

  // Harga
  $__price   = $product->discount_percent > 0
             ? round($product->price * (1 - $product->discount_percent / 100))
             : $product->price;

  // Semua gambar sebagai array URL
  $__images  = $product->images->map(fn($i) => asset('storage/' . $i->image_path))->values()->toArray();
  if (empty($__images)) $__images = [$__ogImg];

  // Reviews — aman meski relasi tidak ada
  $__reviews = [];
  $__avgRating = null;
  try {
    $__rvCol   = $product->reviews ?? collect();
    $__reviews = $__rvCol->take(5)->toArray();
    if ($__rvCol->count() > 0) {
      $__avgRating = round($__rvCol->avg('rating'), 1);
    }
  } catch (\Exception $e) {}

  // Bangun schema Product
  $__schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $product->name,
    'description' => $__desc,
    'url'         => $__url,
    'sku'         => $product->sku ?? $product->slug,
    'image'       => $__images,
    'brand'       => ['@type' => 'Brand', 'name' => $__site],
    'offers'      => [
      '@type'         => 'Offer',
      'url'           => $__url,
      'priceCurrency' => 'IDR',
      'price'         => (string) $__price,
      'availability'  => ($product->stock ?? 1) > 0
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
      'seller'        => ['@type' => 'Organization', 'name' => $__site],
    ],
  ];
  if ($__avgRating && count($__reviews) > 0) {
    $__schema['aggregateRating'] = [
      '@type'       => 'AggregateRating',
      'ratingValue' => (string) $__avgRating,
      'reviewCount' => (string) count($__reviews),
      'bestRating'  => '5',
      'worstRating' => '1',
    ];
  }
@endphp
<meta name="description" content="{{ $__desc }}">
<meta name="keywords" content="{{ $product->name }}, buket bunga, hadiah spesial, {{ strtolower($__site) }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $__url }}">
<meta property="og:type" content="product">
<meta property="og:site_name" content="{{ $__site }}">
<meta property="og:title" content="{{ $__title }}">
<meta property="og:description" content="{{ $__desc }}">
<meta property="og:url" content="{{ $__url }}">
<meta property="og:image" content="{{ $__ogImg }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="1200">
<meta property="og:locale" content="id_ID">
<meta property="product:price:amount" content="{{ $__price }}">
<meta property="product:price:currency" content="IDR">
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
      .nav-links a:hover {
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

      /* ── CONTAINER ── */
      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 60px;
      }

      /* ── MOBILE BACK BUTTON ── */
      .mobile-back-btn {
        display: none;
      }
      @media (max-width: 768px) {
        .mobile-back-btn {
          display: flex;
          align-items: center;
          gap: 6px;
          padding: 88px 20px 12px;
          background: var(--white);
          color: var(--text-muted);
          font-size: 0.85rem;
          font-weight: 500;
          text-decoration: none;
          width: fit-content;
          transition: color 0.2s;
        }
        .mobile-back-btn:hover {
          color: var(--green-dark);
        }
        .mobile-back-btn svg {
          flex-shrink: 0;
        }
      }

      /* ── PRODUCT DETAIL ── */
      .product-detail {
        padding: 116px 0 72px;
        background: var(--white);
      }
      @media (max-width: 768px) {
        .product-detail {
          padding: 0 0 72px;
        }
      }
      .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
      }

      /* ── LEFT: GALLERY ── */
      .gallery {
        position: sticky;
        top: 88px;
      }
      .main-image {
        position: relative;
        border-radius: var(--radius-card);
        overflow: hidden;
        aspect-ratio: 1/1;
        background: var(--ivory-dark);
        margin-bottom: 12px;
      }
      .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
      }
      .main-image:hover img {
        transform: scale(1.03);
      }
      .badge-terlaris {
        position: absolute;
        top: 14px;
        left: 14px;
        background: var(--green-dark);
        color: var(--ivory);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 2px;
        z-index: 2;
      }
      .wishlist-btn {
        position: absolute;
        bottom: 14px;
        right: 14px;
        width: 38px;
        height: 38px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        transition: all 0.2s;
        z-index: 2;
      }
      .wishlist-btn:hover {
        background: var(--ivory);
        transform: scale(1.05);
      }
      .wishlist-btn svg {
        width: 16px;
        height: 16px;
      }

      .thumbnails {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
      }
      .thumb {
        aspect-ratio: 1/1;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.2s;
        background: var(--ivory-dark);
      }
      .thumb.active {
        border-color: var(--green-dark);
      }
      .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
      }
      .thumb:hover img {
        transform: scale(1.05);
      }

      /* ── RIGHT: INFO ── */
      .product-info {
        padding-top: 4px;
      }

      .product-category {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 10px;
      }
      .product-title {
        font-family: "Cormorant Garamond", serif;
        font-size: clamp(2rem, 3.5vw, 2.8rem);
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.1;
        margin-bottom: 14px;
      }

      /* Rating */
      .rating-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
      }
      .stars {
        display: flex;
        gap: 2px;
      }
      .star {
        color: var(--gold);
        font-size: 0.9rem;
      }
      .star.half {
        opacity: 0.5;
      }
      .rating-text {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 300;
      }

      /* Price */
      .price-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
      }
      .price-main {
        font-family: "Cormorant Garamond", serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
      }
      .price-original {
        font-size: 1rem;
        color: var(--text-muted);
        text-decoration: line-through;
        font-weight: 300;
      }
      .price-badge {
        background: #fdecea;
        color: #c0392b;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 3px;
        letter-spacing: 0.05em;
      }

      /* Description */
      .product-desc {
        font-size: 0.9rem;
        color: var(--text-mid);
        line-height: 1.8;
        font-weight: 300;
        margin-bottom: 24px;
      }

      /* Parcel Contents */
      .contents-box {
        background: var(--ivory);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        padding: 20px 22px;
        margin-bottom: 28px;
      }
      .contents-title {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-dark);
        margin-bottom: 14px;
      }
      .contents-list {
        list-style: none;
      }
      .contents-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: var(--text-mid);
        font-weight: 300;
        padding: 6px 0;
        border-bottom: 1px solid rgba(224, 217, 208, 0.5);
      }
      .contents-list li:last-child {
        border-bottom: none;
      }
      .contents-list li svg {
        width: 16px;
        height: 16px;
        color: var(--green-dark);
        flex-shrink: 0;
      }

      /* CTA Button */
      .btn-whatsapp {
        display: flex; align-items: center; justify-content: center;
        gap: 10px; width: 100%; padding: 16px 24px;
        background: #25D366; color: #fff;
        border-radius: 14px; font-size: 1rem; font-weight: 700;
        text-decoration: none; transition: background 0.2s, transform 0.15s;
        border: none; cursor: pointer;
      }
      .btn-whatsapp:hover { background: #1ebe5d; transform: translateY(-2px); }
      .btn-whatsapp svg { width: 20px; height: 20px; flex-shrink: 0; }
      .btn-instagram {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        gap: 10px;
        width: 100%;
        padding: 16px 24px;
        background: var(--green-dark);
        color: var(--ivory);
        border: none;
        font-size: 0.95rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.25s;
        margin-bottom: 10px;
      }
      .btn-instagram:hover {
        background: var(--green-mid);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(45, 74, 62, 0.2);
      }
      .btn-instagram svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
      }

      /* ── Tombol Keranjang & Checkout di detail produk ── */
      .detail-btn-row {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 10px;
        margin-top: 10px;
      }
      .btn-detail-cart {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: var(--green-dark);
        border: 1.5px solid var(--green-dark);
        border-radius: 14px;
        width: 48px;
        height: 48px;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
      }
      .btn-detail-cart:hover {
        background: var(--green-dark);
        color: var(--ivory);
      }
      .btn-detail-checkout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--green-dark);
        color: var(--ivory);
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background 0.2s, transform 0.15s;
        text-decoration: none;
        letter-spacing: 0.02em;
        padding: 14px 0;
      }
      .btn-detail-checkout:hover {
        background: var(--green-mid);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(45,74,62,0.2);
        color: var(--ivory);
      }

      .order-tip {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: #F0F7F4;
        border-left: 3px solid var(--green-dark);
        border-radius: 0 6px 6px 0;
        padding: 10px 14px;
        font-size: 0.78rem;
        color: var(--text-mid);
        font-weight: 400;
        line-height: 1.5;
        margin-top: 8px;
      }

      .order-tip a {
        color: var(--green-dark);
        text-decoration: none;
        font-weight: 500;
        border-bottom: 1px solid var(--green-dark);
      }

      /* Divider */
      .section-divider {
        height: 1px;
        background: var(--border);
        margin: 32px 0;
      }

      /* ── YOU MAY ALSO LIKE ── */
      .also-like {
        padding: 56px 0;
        background: var(--ivory);
      }
      .also-like-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
      }
      .also-like-title {
        font-family: "Cormorant Garamond", serif;
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 600;
        color: var(--text-dark);
      }
      .link-all {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--green-dark);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        border-bottom: 1px solid var(--green-dark);
        padding-bottom: 1px;
        transition: opacity 0.2s;
        white-space: nowrap;
      }
      .link-all:hover {
        opacity: 0.7;
      }

      .also-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
      }
      .also-card {
        background: var(--white);
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        text-decoration: none;
        transition:
          transform 0.25s,
          box-shadow 0.25s;
        display: block;
      }
      .also-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(45, 74, 62, 0.12);
      }
      .also-img {
        aspect-ratio: 4/5;
        overflow: hidden;
        background: var(--ivory-dark);
      }
      .also-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
      }
      .also-card:hover .also-img img {
        transform: scale(1.04);
      }
      .also-info {
        padding: 14px 16px 16px;
      }
      .also-name {
        font-family: "Cormorant Garamond", serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 2px;
        line-height: 1.3;
      }
      .also-type {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 400;
        margin-bottom: 10px;
        text-transform: capitalize;
      }
      .also-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .also-price {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-dark);
      }

      /* ── REVIEWS ── */
      .reviews-section {
        padding: 56px 0 72px;
        background: var(--white);
      }
      .reviews-title {
        font-family: "Cormorant Garamond", serif;
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 28px;
      }
      .reviews-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        align-items: start;
      }
      .review-card {
        padding: 24px;
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        background: var(--ivory);
        transition: box-shadow 0.2s;
      }
      .review-photo {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 14px;
        display: block;
      }
      .review-card:hover {
        box-shadow: var(--shadow-card);
      }
      .review-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
      }
      .review-stars {
        display: flex;
        gap: 2px;
      }
      .review-star {
        color: var(--gold);
        font-size: 0.8rem;
      }
      .reviewer-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
      }
      .review-text {
        font-size: 0.83rem;
        color: var(--text-mid);
        line-height: 1.7;
        font-weight: 300;
        font-style: italic;
      }

      /* ── REVIEW FORM ── */
      .review-photo-upload {
        width: 100%;
        aspect-ratio: 16/7;
        border: 2px dashed var(--border, #ddd);
        border-radius: 10px;
        background: var(--ivory, #f8f5f0);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--text-muted, #999);
        font-size: 0.8rem;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
        overflow: hidden;
      }
      .review-photo-upload:hover {
        border-color: var(--green-mid, #4a7c59);
        background: var(--ivory-dark, #f0ebe3);
      }
      #reviewPhotoPlaceholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
      }

      .review-form-section {
        padding: 0 0 72px;
        background: var(--white);
      }
      .review-form-wrap {
        max-width: 640px;
      }
      .review-form-title {
        font-family: "Cormorant Garamond", serif;
        font-size: clamp(1.4rem, 2.5vw, 1.8rem);
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
      }
      .review-form-subtitle {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 300;
        margin-bottom: 28px;
      }
      .review-form-card {
        background: var(--ivory);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        padding: 32px;
      }

      /* Star Rating Input */
      .star-rating-label {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-dark);
        margin-bottom: 10px;
        display: block;
      }
      .star-rating-input {
        display: flex;
        gap: 6px;
        margin-bottom: 24px;
        flex-direction: row-reverse;
        justify-content: flex-end;
      }
      .star-rating-input input {
        display: none;
      }
      .star-rating-input label {
        font-size: 1.8rem;
        color: var(--border);
        cursor: pointer;
        transition:
          color 0.15s,
          transform 0.15s;
        line-height: 1;
      }
      .star-rating-input label:hover,
      .star-rating-input label:hover ~ label,
      .star-rating-input input:checked ~ label {
        color: var(--gold);
      }
      .star-rating-input label:hover {
        transform: scale(1.15);
      }

      /* Form fields */
      .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
      }
      .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
      }
      .form-group:last-of-type {
        margin-bottom: 0;
      }
      .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-dark);
      }
      .form-input {
        width: 100%;
        padding: 11px 14px;
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-btn);
        font-size: 0.88rem;
        font-family: "DM Sans", sans-serif;
        color: var(--text-dark);
        font-weight: 400;
        outline: none;
        transition:
          border-color 0.2s,
          box-shadow 0.2s;
      }
      .form-input:focus {
        border-color: var(--green-dark);
        box-shadow: 0 0 0 3px rgba(45, 74, 62, 0.08);
      }
      .form-input::placeholder {
        color: var(--text-muted);
        font-weight: 300;
      }
      textarea.form-input {
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
      }
      .form-divider {
        height: 1px;
        background: var(--border);
        margin: 24px 0;
      }
      .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--green-dark);
        color: var(--ivory);
        padding: 13px 28px;
        border-radius: var(--radius-btn);
        font-size: 0.88rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        border: none;
        cursor: pointer;
        font-family: "DM Sans", sans-serif;
        transition: all 0.25s;
        margin-top: 20px;
      }
      .btn-submit:hover {
        background: var(--green-mid);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 74, 62, 0.2);
      }

      /* Success message */
      .success-msg {
        display: none;
        align-items: center;
        gap: 10px;
        background: var(--green-light);
        border: 1px solid rgba(45, 74, 62, 0.2);
        border-radius: var(--radius-btn);
        padding: 14px 18px;
        margin-top: 16px;
        font-size: 0.88rem;
        color: var(--green-dark);
        font-weight: 500;
      }
      .success-msg.show {
        display: flex;
      }
      .success-msg svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
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
        transition-delay: 0.12s;
      }
      .reveal-delay-3 {
        transition-delay: 0.19s;
      }
      .reveal-delay-4 {
        transition-delay: 0.26s;
      }

      /* ── RESPONSIVE ── */
      @media (max-width: 1024px) {
        nav {
          padding: 0 32px;
        }
        .container {
          padding: 0 32px;
        }
        .detail-grid {
          gap: 36px;
        }
        .also-grid {
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

        .detail-grid {
          grid-template-columns: 1fr;
          gap: 28px;
        }
        .gallery {
          position: static;
        }
        .reviews-grid {
          grid-template-columns: 1fr;
          gap: 14px;
        }
        .also-grid {
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
        .also-like-header {
          flex-direction: column;
          align-items: flex-start;
          gap: 8px;
        }
        .form-row {
          grid-template-columns: 1fr;
          gap: 0;
        }
        .review-form-card {
          padding: 22px 18px;
        }
      }

      @media (max-width: 480px) {
        .price-main {
          font-size: 1.6rem;
        }
        .also-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 10px;
        }
        .thumbnails {
          grid-template-columns: repeat(4, 1fr);
          gap: 6px;
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
      <a href="javascript:void(0)" class="btn-nav-mobile">Hubungi Kami</a>
    </div>

    <!-- BACK BUTTON (mobile only) -->
    <a href="{{ route('public.katalog') }}" class="mobile-back-btn" aria-label="Kembali ke Katalog">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
      Kembali
    </a>

    <!-- PRODUCT DETAIL -->
    <section class="product-detail">
      <div class="container">
        <div class="detail-grid">
          <!-- GALLERY -->
          <div class="gallery reveal">
            @php $mainImg = $product->mainImage->first() ?? $product->images->first(); @endphp
            <div class="main-image" id="mainImage">
              <img
                src="{{ $mainImg?->url ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=900&q=80' }}"
                alt="{{ $product->name }}"
                id="mainImg"
              />
              @if($product->badge)
                <span class="badge-terlaris">{{ $product->badge }}</span>
              @endif
            </div>
            <div class="thumbnails">
              @forelse($product->images as $i => $img)
              <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                   onclick="changeImage(this, '{{ $img->url }}')">
                <img src="{{ $img->url }}" alt="foto {{ $i+1 }}" />
              </div>
              @empty
              <div class="thumb active">
                <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=200&q=80" alt="foto" />
              </div>
              @endforelse
            </div>
          </div>

          <!-- PRODUCT INFO -->
          <div class="product-info reveal">
            <div class="product-category">{{ $product->category->name ?? 'TuquParcel' }}</div>
            <h1 class="product-title">{{ $product->name }}</h1>

            <div class="rating-row">
              <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                  <span class="star {{ $i > round($avgRating) ? 'half' : '' }}">★</span>
                @endfor
              </div>
              <span class="rating-text">{{ number_format($avgRating, 1) }} ({{ $product->approvedReviews->count() }} ulasan)</span>
            </div>

            <div class="price-row">
              @if($product->discount_percent > 0)
                <span class="price-main">{{ $product->formatted_final_price }}</span>
                <span class="price-original">{{ $product->formatted_price }}</span>
                <span class="price-badge">-{{ $product->discount_percent }}%</span>
              @else
                <span class="price-main">{{ $product->formatted_price }}</span>
              @endif
            </div>

            <p class="product-desc">{{ $product->description }}</p>

            <!-- Parcel Contents -->
            @if($product->contents->count() > 0)
            <div class="contents-box">
              <div class="contents-title">Isi Parcel</div>
              <ul class="contents-list">
                @foreach($product->contents as $content)
                <li>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg>
                  {{ $content->item }}
                </li>
                @endforeach
              </ul>
            </div>
            @endif

            <!-- CTA -->
            @if($product->stock > 0)
            @php
              $igUrl  = \App\Models\Setting::get('instagram_url', 'https://instagram.com/tuquparcel');
              $waNum  = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', ''));
              $pesan  = urlencode('Halo TuquParcel! Saya ingin memesan *' . $product->name . '* (SKU: ' . $product->sku . '). Apakah masih tersedia?');
              $igLink = 'https://www.instagram.com/direct/new/?text=' . $pesan;
              $waLink = $waNum ? 'https://wa.me/' . $waNum . '?text=' . $pesan : '';
            @endphp
            <a href="{{ $igLink }}" target="_blank" class="btn-instagram">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                <circle cx="12" cy="12" r="3.5" />
                <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
              </svg>
              Pesan via Instagram
            </a>

            @if($waLink)
            <a href="{{ $waLink }}" target="_blank" class="btn-whatsapp">
              <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
              Pesan via WhatsApp
            </a>
            @endif
            <p class="order-tip">
            Tips : Screenshot halaman ini dan kirim ke DM Instagram atau WA kami untuk memesan.
            </p>
            {{-- Pemisah --}}
            <div style="display:flex;align-items:center;gap:10px;margin:14px 0 4px;">
              <div style="flex:1;height:1px;background:var(--border);"></div>
              <span style="font-size:0.72rem;color:var(--text-muted);font-weight:500;letter-spacing:0.06em;text-transform:uppercase;">atau pesan langsung</span>
              <div style="flex:1;height:1px;background:var(--border);"></div>
            </div>

            {{-- Baris: icon keranjang + checkout --}}
            @auth('customer')
            <div class="detail-btn-row">
              {{-- Icon Keranjang (AJAX) --}}
              <button type="button" class="btn-detail-cart" title="Tambah ke Keranjang"
                onclick="detailAddCart(this, {{ $product->id }})">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </button>
              {{-- Checkout langsung --}}
              <form method="POST" action="{{ route('cart.add') }}" style="margin:0;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="redirect_checkout" value="1">
                <button type="submit" class="btn-detail-checkout" style="width:100%;">
                  Checkout Sekarang
                </button>
              </form>
            </div>
            @else
            <a href="{{ route('login') }}"
              style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;background:var(--green-dark);color:var(--ivory);border-radius:14px;font-size:1rem;font-weight:700;text-decoration:none;margin-top:4px;transition:background 0.2s;"
              onmouseover="this.style.background='var(--green-mid)'" onmouseout="this.style.background='var(--green-dark)'">
              Login untuk Memesan
            </a>
            @endauth


            @else
            <div style="width:100%;padding:16px;background:#f5f5f5;border-radius:4px;text-align:center;font-size:0.9rem;color:var(--text-muted);font-weight:500;">
              Stok Habis — Hubungi kami untuk info ketersediaan
            </div>
            @endif
          </div>
        </div>
      </div>
    </section>

    <!-- YOU MAY ALSO LIKE -->
    <section class="also-like">
      <div class="container">
        <div class="also-like-header reveal">
          <h2 class="also-like-title">Mungkin Kamu Suka</h2>
          <a href="{{ route('public.katalog') }}" class="link-all">Lihat Semua →</a>
        </div>
        <div class="also-grid">
          @forelse($related as $i => $rel)
          @php $relImg = $rel->images->first(); $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3','reveal-delay-4']; @endphp
          <a href="{{ route('public.produk', $rel->slug) }}" class="also-card reveal {{ $delays[$i] ?? '' }}">
            <div class="also-img">
              <img src="{{ $relImg?->url ?? 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?w=400&q=80' }}"
                   alt="{{ $rel->name }}" loading="lazy" />
            </div>
            <div class="also-info">
              <div class="also-name">{{ $rel->name }}</div>
              <div class="also-type">{{ $rel->category->name ?? '' }}</div>
              <div class="also-bottom">
                <span class="also-price">{{ $rel->formatted_final_price }}</span>
              </div>
            </div>
          </a>
          @empty
          <p style="grid-column:1/-1;color:var(--text-muted);font-size:0.9rem;">Belum ada produk terkait.</p>
          @endforelse
        </div>
      </div>
    </section>

    <!-- REVIEWS -->
    <section class="reviews-section">
      <div class="container">
        <h2 class="reviews-title reveal">Ulasan Pelanggan</h2>
        @if($product->approvedReviews->count() > 0)
        <div class="reviews-grid">
          @foreach($product->approvedReviews->take(6) as $i => $review)
          @php $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3','reveal-delay-1','reveal-delay-2','reveal-delay-3']; @endphp
          <div class="review-card reveal {{ $delays[$i] ?? '' }}">
            <div class="review-header">
              <div class="review-stars">
                @for($s = 1; $s <= 5; $s++)
                  <span class="review-star" style="{{ $s > $review->rating ? 'color:var(--border)' : '' }}">★</span>
                @endfor
              </div>
              <span class="reviewer-name">{{ $review->reviewer_name }}</span>
            </div>
            <p class="review-text">"{{ $review->comment }}"</p>
            @if($review->photo)
              <img src="{{ asset('storage/' . $review->photo) }}" alt="Foto ulasan" class="review-photo" loading="lazy" />
            @endif
          </div>
          @endforeach
        </div>
        @else
        <p style="color:var(--text-muted);font-size:0.9rem;">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
        @endif
      </div>
    </section>

    <!-- REVIEW FORM -->
    <section class="review-form-section">
      <div class="container">
        <div class="review-form-wrap reveal">
          <h2 class="review-form-title">Tulis Ulasan</h2>
          <p class="review-form-subtitle">
            Bagikan pengalamanmu dengan produk ini. Ulasan akan ditampilkan
            setelah diverifikasi.
          </p>
          <form method="POST" action="{{ route('public.review.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div class="review-form-card">
            <!-- Star Rating -->
            <span class="star-rating-label">Rating Kamu </span>
            <div class="star-rating-input">
              <input type="radio" id="star5" name="rating" value="5" />
              <label for="star5" title="5 bintang">★</label>
              <input type="radio" id="star4" name="rating" value="4" />
              <label for="star4" title="4 bintang">★</label>
              <input type="radio" id="star3" name="rating" value="3" />
              <label for="star3" title="3 bintang">★</label>
              <input type="radio" id="star2" name="rating" value="2" />
              <label for="star2" title="2 bintang">★</label>
              <input type="radio" id="star1" name="rating" value="1" />
              <label for="star1" title="1 bintang">★</label>
            </div>

            <div class="form-divider"></div>

            <!-- Nama & Email -->
            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="reviewer-name">Nama </label>
                <input type="text" id="reviewer-name" name="reviewer_name" class="form-input" placeholder="Nama kamu" required />
              </div>
              <div class="form-group">
                <label class="form-label" for="reviewer-email"
                  >Email
                  <span
                    style="
                      font-weight: 300;
                      text-transform: none;
                      letter-spacing: 0;
                    "
                    >(opsional)</span
                  ></label
                >
                <input type="email" id="reviewer-email" name="reviewer_email" class="form-input" placeholder="email@kamu.com" />
              </div>
            </div>

            <!-- Ulasan -->
            <div class="form-group">
              <label class="form-label" for="review-text">Ulasan </label>
              <textarea id="review-text" name="comment" class="form-input" placeholder="Ceritakan pengalamanmu dengan produk ini..." required></textarea>
            </div>

            <!-- Foto Opsional -->
            <div class="form-group">
              <label class="form-label" for="review-photo">
                Foto
                <span style="font-weight:300;text-transform:none;letter-spacing:0;">(opsional)</span>
              </label>
              <div class="review-photo-upload" id="reviewPhotoUpload" onclick="document.getElementById('review-photo').click()">
                <div id="reviewPhotoPlaceholder">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                  <span>Klik untuk tambah foto</span>
                  <span style="font-size:0.7rem;color:var(--text-muted,#999);">JPG, PNG, WEBP · Maks 3MB</span>
                </div>
                <img id="reviewPhotoPreview" src="" alt="Preview" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:10px;" />
                <button type="button" id="reviewPhotoRemove" onclick="removeReviewPhoto(event)" style="display:none;position:absolute;top:8px;right:8px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.55);color:#fff;border:none;cursor:pointer;font-size:16px;line-height:1;z-index:2;">×</button>
              </div>
              <input type="file" id="review-photo" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none" onchange="previewReviewPhoto(this)">
              @error('photo')<p style="color:#c00;font-size:0.78rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit">
              <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <line x1="22" y1="2" x2="11" y2="13" />
                <polygon points="22 2 15 22 11 13 2 9 22 2" />
              </svg>
              Kirim Ulasan
            </button>

          </div>
          </form>
        </div>
      </div>
    </section>

    <!-- FOOTER -->

<script>
  function previewReviewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('reviewPhotoPreview').src = e.target.result;
      document.getElementById('reviewPhotoPreview').style.display = 'block';
      document.getElementById('reviewPhotoPlaceholder').style.display = 'none';
      document.getElementById('reviewPhotoRemove').style.display = 'flex';
    };
    reader.readAsDataURL(input.files[0]);
  }

  function removeReviewPhoto(e) {
    e.stopPropagation();
    document.getElementById('review-photo').value = '';
    document.getElementById('reviewPhotoPreview').src = '';
    document.getElementById('reviewPhotoPreview').style.display = 'none';
    document.getElementById('reviewPhotoPlaceholder').style.display = 'flex';
    document.getElementById('reviewPhotoRemove').style.display = 'none';
  }

  // ── Thumbnail gallery ──────────────────────────────────────────
  function changeImage(thumbEl, url) {
    // Ganti foto utama
    var mainImg = document.getElementById('mainImg');
    if (mainImg) {
      mainImg.style.opacity = '0';
      setTimeout(function() {
        mainImg.src = url;
        mainImg.style.opacity = '1';
      }, 150);
    }

    // Update active state thumbnail
    document.querySelectorAll('.thumb').forEach(function(t) {
      t.classList.remove('active');
    });
    thumbEl.classList.add('active');
  }

  // Tambahkan transition CSS untuk efek fade
  (function() {
    var style = document.createElement('style');
    style.textContent = '#mainImg { transition: opacity 0.15s ease; }';
    document.head.appendChild(style);
  })();
</script>

@endsection

@push('scripts')
<script>
  // ── Toast Notification ───────────────────────────────────
  function showDetailToast(msg, type) {
    const existing = document.getElementById('detailToast');
    if (existing) existing.remove();

    const colors = {
      success: { bg: '#2D4A3E', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>' },
      error:   { bg: '#dc2626', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>' },
    };
    const c = colors[type] || colors.success;

    const toast = document.createElement('div');
    toast.id = 'detailToast';
    toast.style.cssText = [
      'position:fixed', 'bottom:28px', 'right:28px', 'z-index:99999',
      'background:' + c.bg, 'color:#F7F3EE',
      'padding:14px 20px', 'border-radius:10px',
      'font-size:0.875rem', 'font-weight:500',
      'font-family:DM Sans,sans-serif',
      'box-shadow:0 8px 32px rgba(0,0,0,0.18)',
      'display:flex', 'align-items:center', 'gap:10px',
      'opacity:0', 'transform:translateY(14px)',
      'transition:opacity 0.28s ease,transform 0.28s ease',
      'max-width:340px', 'pointer-events:none',
    ].join(';');

    toast.innerHTML =
      '<svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5" style="flex-shrink:0">' + c.icon + '</svg>' +
      '<span>' + msg + '</span>';

    document.body.appendChild(toast);
    requestAnimationFrame(() => {
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';
    });
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(14px)';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  // ── AJAX Tambah Keranjang (icon 🛒) ─────────────────────
  function detailAddCart(btn, productId) {
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
        // Update badge navbar
        const badge    = document.getElementById('navCartBadge');
        const badgeMob = document.getElementById('navCartBadgeMob');
        if (badge)    { badge.textContent = data.cart_count; badge.style.display = ''; }
        if (badgeMob) { badgeMob.textContent = data.cart_count; badgeMob.style.display = ''; }

        // Animasi tombol
        btn.style.background = '#2D4A3E';
        btn.style.color      = '#F7F3EE';
        setTimeout(() => {
          btn.style.background = '';
          btn.style.color      = '';
          btn.disabled         = false;
          btn.style.opacity    = '';
        }, 900);

        showDetailToast('Produk ditambahkan ke keranjang!', 'success');
      } else {
        showDetailToast(data.message || 'Gagal menambahkan produk.', 'error');
        btn.disabled      = false;
        btn.style.opacity = '';
      }
    })
    .catch(() => {
      showDetailToast('Terjadi kesalahan. Coba lagi.', 'error');
      btn.disabled      = false;
      btn.style.opacity = '';
    });
  }
</script>
@endpush
