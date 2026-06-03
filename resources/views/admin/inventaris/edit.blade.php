@extends('layouts.admin')
@section('title', 'Edit Produk')

@push('styles')
<style>

  /* ===== BREADCRUMB ===== */
  .breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--text-muted); margin-bottom: 16px;
  }
  .breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
  .breadcrumb a:hover { color: var(--green-accent); }
  .breadcrumb span { color: var(--text); font-weight: 500; }
  .breadcrumb-sep { opacity: 0.4; }

  /* ===== PAGE TITLE BAR ===== */
  .title-bar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
  }
  .title-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .title-left h1 { font-size: 24px; font-weight: 800; color: var(--text); }
  .badge-tersedia {
    background: #e8f5ee; color: #276749;
    font-size: 12px; font-weight: 700;
    padding: 4px 12px; border-radius: 20px;
  }
  .title-right { display: flex; align-items: center; gap: 8px; }
  .btn-lihat {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px;
    border: 1.5px solid var(--border); background: var(--card-bg);
    font-size: 13px; font-weight: 600; color: var(--text);
    cursor: pointer; transition: all 0.2s; text-decoration: none;
  }
  .btn-lihat svg { width: 14px; height: 14px; }
  .btn-lihat:hover { border-color: var(--green-accent); color: var(--green-accent); }
  .btn-dots {
    width: 36px; height: 36px; border-radius: 9px;
    border: 1.5px solid var(--border); background: var(--card-bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; cursor: pointer; transition: all 0.2s; color: var(--text-muted);
  }
  .btn-dots:hover { background: #f0f4f0; }

  /* ===== EDIT GRID ===== */
  .edit-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
  }

  /* ===== CARD ===== */
  .edit-card {
    background: var(--card-bg); border-radius: 16px;
    border: 1px solid var(--border);
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    padding: 24px;
    margin-bottom: 20px;
  }
  .edit-card:last-child { margin-bottom: 0; }

  .card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 15px; font-weight: 700; color: var(--text);
    margin-bottom: 20px;
  }
  .card-title svg { width: 17px; height: 17px; color: var(--green-accent); }

  /* ===== FORM ===== */
  .form-group { margin-bottom: 18px; }
  .form-group:last-child { margin-bottom: 0; }
  .form-label {
    display: block; font-size: 13px; font-weight: 600;
    color: var(--text); margin-bottom: 8px;
  }
  .form-input {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; transition: border-color 0.2s;
  }
  .form-input:focus { border-color: var(--green-accent); }
  .form-input::placeholder { color: var(--text-muted); }

  /* Rich text toolbar */
  .rte-wrap { border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden; transition: border-color 0.2s; }
  .rte-wrap:focus-within { border-color: var(--green-accent); }
  .rte-toolbar {
    display: flex; align-items: center; gap: 2px;
    padding: 8px 10px; border-bottom: 1px solid var(--border);
    background: #fafcfa;
  }
  .rte-btn {
    width: 30px; height: 30px; border-radius: 6px; border: none;
    background: none; cursor: pointer; display: flex; align-items: center;
    justify-content: center; font-size: 13px; font-weight: 700;
    color: var(--text-muted); transition: all 0.15s;
  }
  .rte-btn:hover { background: #e8f0e8; color: var(--text); }
  .rte-btn svg { width: 14px; height: 14px; }
  .rte-area {
    width: 100%; min-height: 110px; max-height: 400px; padding: 12px 14px;
    border: none; outline: none; overflow-y: auto;
    font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg); line-height: 1.6; box-sizing: border-box;
  }
  .rte-area:empty::before {
    content: attr(data-placeholder);
    color: var(--text-muted);
    pointer-events: none;
  }
  .rte-btn.active { background: #e8f0e8; color: var(--green-accent); }
  .rte-area ul { padding-left: 20px; margin: 6px 0; }
  .rte-area a { color: var(--green-accent); text-decoration: underline; }

  /* Tags / Isi Parcel */
  .tags-wrap {
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 10px 12px; transition: border-color 0.2s; background: var(--card-bg);
  }
  .tags-wrap:focus-within { border-color: var(--green-accent); }
  .tags-list { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }
  .tag-item {
    display: flex; align-items: center; gap: 5px;
    background: #e8f5ee; color: #276749;
    font-size: 12px; font-weight: 600;
    padding: 4px 10px; border-radius: 20px;
  }
  .tag-remove {
    cursor: pointer; font-size: 13px; line-height: 1;
    opacity: 0.6; transition: opacity 0.15s;
  }
  .tag-remove:hover { opacity: 1; }
  .tag-input {
    border: none; outline: none; font-size: 13px;
    font-family: inherit; color: var(--text); width: 100%;
    background: transparent;
  }
  .tag-input::placeholder { color: var(--text-muted); }

  /* Photo upload */
  .photos-grid {
    display: flex; gap: 12px; flex-wrap: wrap;
  }
  .photo-item {
    width: 110px; height: 110px; border-radius: 10px;
    overflow: hidden; position: relative; flex-shrink: 0;
    border: 1.5px solid var(--border);
  }
  .photo-item img {
    width: 100%; height: 100%; object-fit: cover; display: block;
  }
  .photo-primary-badge {
    position: absolute; top: 6px; left: 6px;
    background: rgba(0,0,0,0.55); color: #fff;
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 6px; backdrop-filter: blur(4px);
  }
  .photo-remove {
    position: absolute; top: 5px; right: 5px;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(0,0,0,0.5); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; cursor: pointer; opacity: 0;
    transition: opacity 0.2s;
  }
  .photo-item:hover .photo-remove { opacity: 1; }
  .photo-add {
    width: 110px; height: 110px; border-radius: 10px;
    border: 1.5px dashed var(--border); background: #fafcfa;
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 6px; cursor: pointer;
    transition: all 0.2s; flex-shrink: 0;
  }
  .photo-add:hover { border-color: var(--green-accent); background: #f0faf4; }
  .photo-add svg { width: 22px; height: 22px; color: var(--text-muted); }
  .photo-add span { font-size: 11.5px; font-weight: 600; color: var(--text-muted); }

  /* ===== RIGHT PANEL ===== */
  .right-col { display: flex; flex-direction: column; gap: 20px; }

  /* Toggle switch */
  .toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 0;
  }
  .toggle-row + .toggle-row { border-top: 1px solid var(--border); }
  .toggle-info .toggle-label { font-size: 13.5px; font-weight: 600; color: var(--text); }
  .toggle-info .toggle-sub { font-size: 11.5px; color: var(--green-accent); margin-top: 2px; }
  .toggle-info .toggle-sub.off { color: var(--text-muted); }

  /* Switch */
  .switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
  .switch input { opacity: 0; width: 0; height: 0; }
  .switch-track {
    position: absolute; inset: 0; border-radius: 12px;
    background: #d0d8d0; cursor: pointer; transition: background 0.25s;
  }
  .switch-track::after {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%;
    background: #fff; top: 3px; left: 3px;
    transition: transform 0.25s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
  }
  .switch input:checked + .switch-track { background: var(--green-light); }
  .switch input:checked + .switch-track::after { transform: translateX(20px); }

  /* Harga & Inventaris */
  .price-input-wrap { position: relative; }
  .price-prefix {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    font-size: 13px; font-weight: 600; color: var(--text-muted);
  }
  .price-input { padding-left: 34px !important; }

  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  .sku-wrap { position: relative; }
  .sku-input { padding-right: 40px !important; }
  .sku-refresh {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    width: 28px; height: 28px; border-radius: 7px; border: none;
    background: #f0f4f0; cursor: pointer; display: flex; align-items: center;
    justify-content: center; color: var(--text-muted); transition: all 0.2s;
  }
  .sku-refresh:hover { background: #e8f5ee; color: var(--green-accent); }
  .sku-refresh svg { width: 14px; height: 14px; }

  /* Kategori */
  .form-select {
    width: 100%; padding: 10px 36px 10px 14px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7f72' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
    -webkit-appearance: none; appearance: none;
    outline: none; cursor: pointer; transition: border-color 0.2s;
  }
  .form-select:focus { border-color: var(--green-accent); }

  /* Tags input */
  .tags-input-wrap {
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 10px 12px; transition: border-color 0.2s;
  }
  .tags-input-wrap:focus-within { border-color: var(--green-accent); }
  .tags-input {
    border: none; outline: none; font-size: 13px;
    font-family: inherit; color: var(--text); width: 100%; background: transparent;
  }
  .tags-input::placeholder { color: var(--text-muted); }

  /* ===== BOTTOM BAR ===== */
  .bottom-bar {
    position: fixed; bottom: 0; left: var(--sidebar-w); right: 0;
    background: var(--card-bg); border-top: 1px solid var(--border);
    padding: 14px 36px;
    display: flex; align-items: center; justify-content: space-between;
    z-index: 50;
  }
  .btn-buang {
    font-size: 13px; font-weight: 600; color: var(--red);
    background: none; border: none; cursor: pointer; transition: opacity 0.2s;
  }
  .btn-buang:hover { opacity: 0.7; }
  .bottom-right { display: flex; align-items: center; gap: 16px; }
  .last-saved { font-size: 12.5px; color: var(--text-muted); }
  .btn-simpan {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 10px;
    border: none; background: var(--green-dark);
    font-size: 13.5px; font-weight: 700; color: #fff;
    cursor: pointer; transition: background 0.2s;
  }
  .btn-simpan svg { width: 15px; height: 15px; }
  .btn-simpan:hover { background: var(--green-accent); }

  /* Extra bottom padding so content isn't hidden behind bottom bar */
  .content { padding-bottom: 80px !important; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1024px) {
    .edit-grid { grid-template-columns: 1fr; }
    .right-col { flex-direction: row; flex-wrap: wrap; }
    .right-col .edit-card { flex: 1; min-width: 260px; margin-bottom: 0; }
  }

  @media (max-width: 768px) {
    .title-left h1 { font-size: 18px; }
    .bottom-bar { left: 0; padding: 12px 16px; }
    .last-saved { display: none; }
    .photos-grid { gap: 8px; }
    .photo-item, .photo-add { width: 85px; height: 85px; }
    .two-col { grid-template-columns: 1fr 1fr; }
    .right-col { flex-direction: column; }
    .right-col .edit-card { min-width: unset; }
    .title-bar { flex-direction: column; align-items: flex-start; }
    .title-right { width: 100%; justify-content: flex-end; }
  }

  @media (max-width: 480px) {
    .two-col { grid-template-columns: 1fr; }
    .photo-item, .photo-add { width: 75px; height: 75px; }
  }

</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.inventaris.update', $product) }}" enctype="multipart/form-data" id="edit-produk-form">
  @csrf
  @method('PUT')

  @if($errors->any())
    <div style="background:#fff0f0;border:1px solid #f5c6c0;border-radius:10px;padding:14px 18px;margin-bottom:18px;font-size:13.5px;color:#c0392b;">
      <ul style="list-style:disc;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

      <!-- Breadcrumb -->
      <div class="breadcrumb">
        <a href="{{ route('admin.inventaris.index') }}">Inventaris</a>
        <span class="breadcrumb-sep">/</span>
        <span>Edit Produk</span>
      </div>

      <!-- Title bar -->
      <div class="title-bar">
        <div class="title-left">
          <h1 id="page-title">{{ $product->name }}</h1>
          <span class="badge-tersedia">Tersedia</span>
        </div>
        <div class="title-right">
          <a class="btn-lihat" href="{{ route('public.produk', $product->slug) }}" target="_blank">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            Lihat di Situs
          </a>
          <button class="btn-dots">⋯</button>
        </div>
      </div>

      <!-- Edit Grid -->
      <div class="edit-grid">

        <!-- LEFT COLUMN -->
        <div class="left-col">

          <!-- Informasi Umum -->
          <div class="edit-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Informasi Umum
            </div>

            <div class="form-group">
              <label class="form-label">Nama Produk</label>
              <input type="text" class="form-input" name="name" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
              <label class="form-label">Deskripsi</label>
              <div class="rte-wrap" id="rte-wrap">
                <div class="rte-toolbar">
                  <button type="button" class="rte-btn" id="btn-bold" title="Bold" onmousedown="event.preventDefault(); rteCommand('bold')"><strong>B</strong></button>
                  <button type="button" class="rte-btn" id="btn-italic" title="Italic" onmousedown="event.preventDefault(); rteCommand('italic')"><em>I</em></button>
                  <button type="button" class="rte-btn" id="btn-list" title="Bullet List" onmousedown="event.preventDefault(); rteCommand('insertUnorderedList')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                  </button>
                  <button type="button" class="rte-btn" id="btn-link" title="Insert Link" onmousedown="event.preventDefault(); rteInsertLink()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                  </button>
                </div>
                {{-- contenteditable div sebagai editor --}}
                <div
                  class="rte-area"
                  id="rte-editor"
                  contenteditable="true"
                  data-placeholder="Tulis deskripsi produk..."
                >{{ old('description', $product->description) }}</div>
                {{-- hidden input yang dikirim ke server --}}
                <input type="hidden" name="description" id="rte-hidden">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Isi Parcel</label>
              <div class="tags-wrap" id="parcel-wrap">
                @foreach($product->contents as $content)
                  <span class="tag-item" data-tag-id="existing-{{ $content->id }}">
                    {{ $content->item }}
                    <span class="tag-remove" onclick="removeTag(this)">×</span>
                    <input type="hidden" name="contents[]" value="{{ $content->item }}" id="hidden-existing-{{ $content->id }}">
                  </span>
                @endforeach
                <input class="tag-input" type="text" placeholder="Tambahkan item (tekan enter)" onkeydown="addTag(event, this)">
              </div>
              <div id="parcel-hidden"></div>
            </div>
          </div>

          <!-- Foto Produk -->
          <div class="edit-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              Foto Produk
            </div>
            <div class="photos-grid" id="photos-grid">
              @foreach($product->images as $i => $img)
              <div class="photo-item">
                <img src="{{ $img->url }}" alt="foto {{ $i+1 }}">
                @if($i === 0)<span class="photo-primary-badge">Utama</span>@endif
                <input type="hidden" name="existing_images[]" value="{{ $img->id }}">
                <span class="photo-remove" onclick="removeExistingPhoto(this, {{ $img->id }})">×</span>
              </div>
              @endforeach
              <div class="photo-add" onclick="document.getElementById('photo-upload').click()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                <span>Tambah Foto</span>
              </div>
              <input type="file" id="photo-upload" name="new_images[]" accept="image/*" multiple style="display:none" onchange="previewPhoto(this)">
            </div>
          </div>

        </div><!-- /left-col -->

        <!-- RIGHT COLUMN -->
        <div class="right-col">

          <!-- Visibilitas -->
          <div class="edit-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              Visibilitas
            </div>

            <div class="toggle-row">
              <div class="toggle-info">
                <div class="toggle-label">Status Produk</div>
                <div class="toggle-sub" id="status-sub">Aktifkan untuk menampilkan</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="is_published" value="1" {{ $product->is_published ? 'checked' : '' }}
                  onchange="updateToggleSub(this, 'status-sub', 'Aktifkan untuk menampilkan', 'Produk disembunyikan')">
                <span class="switch-track"></span>
              </label>
            </div>

            <div class="toggle-row">
              <div class="toggle-info">
                <div class="toggle-label">Terlaris</div>
                <div class="toggle-sub off" id="featured-sub">Tampilkan di halaman utama</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}
                  onchange="updateToggleSub(this, 'featured-sub', 'Tampilkan di halaman utama', 'Tidak ditampilkan di utama')">
                <span class="switch-track"></span>
              </label>
            </div>
          </div>

          <!-- Harga & Inventaris -->
          <div class="edit-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
              Harga & Inventaris
            </div>

            <div class="form-group">
              <label class="form-label">Harga (IDR)</label>
              <div class="price-input-wrap">
                <span class="price-prefix">Rp</span>
                <input type="number" class="form-input price-input" name="price" value="{{ old('price', $product->price) }}" step="1000" min="0" required>
              </div>
            </div>

            <div class="form-group two-col">
              <div>
                <label class="form-label">Diskon (%)</label>
                <input type="number" class="form-input" name="discount_percent" value="{{ old('discount_percent', $product->discount_percent) }}" min="0" max="100">
              </div>
              <div>
                <label class="form-label">Stok</label>
                <input type="number" class="form-input" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">SKU</label>
              <div class="sku-wrap">
                <input type="text" class="form-input sku-input" name="sku" value="{{ old('sku', $product->sku) }}" id="sku-field" required>
                <button class="sku-refresh" onclick="generateSKU()" title="Generate SKU baru">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Kategori -->
          <div class="edit-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
              Kategori
            </div>

            <div class="form-group">
              <select class="form-select" name="category_id" required>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Badge <span style="font-weight:400;color:var(--text-muted)">(opsional)</span></label>
              <input type="text" class="form-input" name="badge" value="{{ old('badge', $product->badge) }}" placeholder="cth. Bestseller">
            </div>
          </div>

        </div><!-- /right-col -->
      </div><!-- /edit-grid -->

</form>

@endsection

@section('bottom_bar')
  <div class="bottom-bar">
    <button type="button" class="btn-buang" onclick="if(confirm('Buang semua perubahan?')) window.history.back()">Buang Perubahan</button>
    <div class="bottom-right">
      <span class="last-saved">Terakhir disimpan: {{ $product->updated_at->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
      <button type="submit" form="edit-produk-form" class="btn-simpan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Simpan Perubahan
      </button>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Live title update saat nama produk diketik
  document.querySelector('input[name="name"]').addEventListener('input', function() {
    document.getElementById('page-title').textContent = this.value || 'Edit Produk';
  });

  // ===== RICH TEXT EDITOR =====
  const rteEditor = document.getElementById('rte-editor');
  const rteHidden = document.getElementById('rte-hidden');

  // Init: sinkronisasi konten awal ke hidden input
  rteHidden.value = rteEditor.innerHTML;

  // Sinkronisasi editor → hidden input setiap kali isi berubah
  rteEditor.addEventListener('input', function() {
    rteHidden.value = rteEditor.innerHTML;
    updateToolbarState();
  });

  // Sync juga saat form di-submit (safety net)
  document.getElementById('edit-produk-form').addEventListener('submit', function() {
    rteHidden.value = rteEditor.innerHTML;
  });

  // Eksekusi perintah formatting
  function rteCommand(cmd) {
    rteEditor.focus();
    document.execCommand(cmd, false, null);
    rteHidden.value = rteEditor.innerHTML;
    updateToolbarState();
  }

  // Insert link
  function rteInsertLink() {
    const url = prompt('Masukkan URL link:');
    if (url) {
      rteEditor.focus();
      document.execCommand('createLink', false, url);
      rteEditor.querySelectorAll('a:not([target])').forEach(a => a.setAttribute('target', '_blank'));
      rteHidden.value = rteEditor.innerHTML;
    }
  }

  // Update active state tombol toolbar sesuai posisi kursor
  function updateToolbarState() {
    document.getElementById('btn-bold').classList.toggle('active', document.queryCommandState('bold'));
    document.getElementById('btn-italic').classList.toggle('active', document.queryCommandState('italic'));
    document.getElementById('btn-list').classList.toggle('active', document.queryCommandState('insertUnorderedList'));
  }

  rteEditor.addEventListener('keyup', updateToolbarState);
  rteEditor.addEventListener('mouseup', updateToolbarState);

  // Toggle sub text
  function updateToggleSub(cb, subId, onText, offText) {
    const sub = document.getElementById(subId);
    sub.textContent = cb.checked ? onText : offText;
    sub.className = 'toggle-sub' + (cb.checked ? '' : ' off');
  }

  // Generate SKU
  function generateSKU() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const rand = (n) => Array.from({length: n}, () => chars[Math.floor(Math.random() * chars.length)]).join('');
    document.getElementById('sku-field').value = `BQ-${rand(3)}-${rand(4)}`;
  }

  // Photo remove
  function removePhoto(el) {
    el.parentElement.remove();
  }

  // Preview foto baru
  function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const files = Array.from(input.files);
    const grid = document.getElementById('photos-grid');
    const addBtn = grid.querySelector('.photo-add');
    files.forEach(file => {
      const url = URL.createObjectURL(file);
      const div = document.createElement('div');
      div.className = 'photo-item';
      div.innerHTML = `<img src="${url}" alt="foto baru"><span class="photo-remove" onclick="removePhoto(this)">×</span>`;
      grid.insertBefore(div, addBtn);
    });
  }

  // Hapus foto yang sudah ada
  function removeExistingPhoto(el, imgId) {
    el.parentElement.remove();
    // Remove the existing_images hidden input for this id
    const inputs = document.querySelectorAll('input[name="existing_images[]"]');
    inputs.forEach(i => { if (i.value == imgId) i.remove(); });
  }

  // Hapus foto baru (preview)
  function removePhoto(el) {
    el.parentElement.remove();
  }

  // Tag parcel - edit page (preserves existing hidden inputs)
  function addTag(e, input) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    const val = input.value.trim();
    if (!val) return;

    const wrap = document.getElementById('parcel-wrap');
    const tagId = 'tag-' + Date.now();

    const tag = document.createElement('span');
    tag.className = 'tag-item';
    tag.dataset.tagId = tagId;
    tag.innerHTML = val + ' <span class="tag-remove" onclick="removeTag(this)">×</span>';
    wrap.insertBefore(tag, input);

    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'contents[]';
    hidden.value = val;
    hidden.id = 'hidden-' + tagId;
    document.getElementById('parcel-hidden').appendChild(hidden);

    input.value = '';
  }

  function removeTag(el) {
    const tag = el.parentElement;
    const tagId = tag.dataset.tagId;
    if (tagId) {
      const h = document.getElementById('hidden-' + tagId);
      if (h) h.remove();
    }
    // Also remove the corresponding hidden input by matching value for existing items
    const val = tag.textContent.replace('×', '').trim();
    const allHidden = document.querySelectorAll('input[name="contents[]"]');
    allHidden.forEach(h => { if (h.value === val) h.remove(); });
    tag.remove();
  }
</script>
@endpush
