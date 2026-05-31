@extends('layouts.admin')
@section('title', 'Tambah Produk')

@push('styles')
<style>

  /* ===== BREADCRUMB ===== */
  .breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--text-muted); margin-bottom: 14px;
  }
  .breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
  .breadcrumb a:hover { color: var(--green-accent); }
  .breadcrumb span { color: var(--text); font-weight: 500; }
  .breadcrumb-sep { opacity: 0.4; }

  /* ===== PAGE HEADER ===== */
  .page-title { font-size: 26px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
  .page-sub { font-size: 13.5px; color: var(--text-muted); margin-bottom: 28px; }

  /* ===== MAIN GRID ===== */
  .add-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px;
    align-items: start;
  }

  /* ===== CARD ===== */
  .add-card {
    background: var(--card-bg); border-radius: 16px;
    border: 1px solid var(--border);
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
    margin-bottom: 20px;
  }
  .add-card:last-child { margin-bottom: 0; }

  /* Informasi Produk card — green header */
  .card-hero {
    height: 130px; position: relative; overflow: hidden;
    background: linear-gradient(135deg, #1e3a2f 0%, #2d5a40 50%, #3d7a55 100%);
  }
  .card-hero-overlay {
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1490750967868-88df5691166a?w=800&h=200&fit=crop') center/cover;
    opacity: 0.25;
  }
  .card-hero-title {
    position: relative; z-index: 1;
    display: flex; align-items: center; gap: 10px;
    padding: 0 28px; height: 100%;
    font-size: 17px; font-weight: 700; color: #fff;
  }
  .card-hero-title svg { width: 18px; height: 18px; opacity: 0.85; }

  .card-body { padding: 24px 28px; }

  .card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 15px; font-weight: 700; color: var(--text);
    padding: 20px 24px 16px;
    border-bottom: 1px solid var(--border);
  }
  .card-title svg { width: 16px; height: 16px; color: var(--text-muted); }

  /* ===== FORM ===== */
  .form-group { margin-bottom: 20px; }
  .form-group:last-child { margin-bottom: 0; }
  .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }

  .form-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; transition: border-color 0.2s;
  }
  .form-input:focus { border-color: var(--green-accent); }
  .form-input::placeholder { color: #b0bdb5; }

  textarea.form-input {
    resize: vertical; min-height: 110px; line-height: 1.6;
  }

  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

  /* Select */
  .form-select {
    width: 100%; padding: 11px 36px 11px 14px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7f72' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
    -webkit-appearance: none; appearance: none;
    outline: none; cursor: pointer; transition: border-color 0.2s;
  }
  .form-select:focus { border-color: var(--green-accent); }
  .form-select.placeholder { color: #b0bdb5; }

  /* SKU prefix */
  .sku-wrap { position: relative; }
  .sku-prefix {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    font-size: 13.5px; font-weight: 700; color: var(--text-muted);
    pointer-events: none;
  }
  .sku-input { padding-left: 48px !important; font-family: monospace; }

  /* Parcel tags */
  .tags-wrap {
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 10px 12px; transition: border-color 0.2s; background: var(--card-bg);
    display: flex; flex-wrap: wrap; align-items: center; gap: 7px; cursor: text;
  }
  .tags-wrap:focus-within { border-color: var(--green-accent); }
  .tag-item {
    display: inline-flex; align-items: center; gap: 5px;
    background: #e8f5ee; color: #276749;
    font-size: 12.5px; font-weight: 600;
    padding: 4px 10px; border-radius: 20px;
    white-space: nowrap;
  }
  .tag-remove { cursor: pointer; font-size: 14px; line-height: 1; opacity: 0.6; transition: opacity 0.15s; }
  .tag-remove:hover { opacity: 1; }
  .tag-input {
    border: none; outline: none; font-size: 13px;
    font-family: inherit; color: var(--text); min-width: 160px; flex: 1;
    background: transparent; padding: 2px 0;
  }
  .tag-input::placeholder { color: #b0bdb5; }

  /* ===== PRICING CARD ===== */
  .pricing-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }

  .price-wrap { position: relative; }
  .price-prefix {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    font-size: 13.5px; font-weight: 600; color: var(--text-muted);
    pointer-events: none;
  }
  .price-input { padding-left: 30px !important; }

  .suffix-wrap { position: relative; }
  .input-suffix {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    font-size: 13px; font-weight: 600; color: var(--text-muted); pointer-events: none;
  }
  .suffix-input { padding-right: 32px !important; }

  /* ===== MEDIA CARD ===== */
  .media-card-body { padding: 20px 24px; }

  .upload-zone {
    border: 2px dashed var(--border); border-radius: 12px;
    padding: 32px 20px; text-align: center; cursor: pointer;
    transition: all 0.2s; margin-bottom: 16px;
    background: #fafcfa;
  }
  .upload-zone:hover { border-color: var(--green-accent); background: #f0faf4; }
  .upload-zone.dragging { border-color: var(--green-accent); background: #e8f5ee; }
  .upload-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #e8f5ee; display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px;
  }
  .upload-icon svg { width: 22px; height: 22px; color: var(--green-accent); }
  .upload-text { font-size: 13.5px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
  .upload-sub { font-size: 11.5px; color: var(--text-muted); }

  /* Photo slots grid */
  .photo-slots { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
  .photo-slot {
    aspect-ratio: 1; border-radius: 10px; overflow: hidden;
    border: 1.5px solid var(--border); position: relative;
    background: #f8faf8; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: border-color 0.2s;
  }
  .photo-slot:hover { border-color: var(--green-accent); }
  .photo-slot img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .photo-slot-empty svg { width: 28px; height: 28px; color: #c8d4c8; }
  .photo-main-badge {
    position: absolute; top: 6px; left: 6px;
    background: rgba(0,0,0,0.55); color: #fff;
    font-size: 9px; font-weight: 800; padding: 2px 7px;
    border-radius: 5px; letter-spacing: 0.5px;
    backdrop-filter: blur(4px);
  }
  .photo-slot-remove {
    position: absolute; top: 5px; right: 5px;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(0,0,0,0.5); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; cursor: pointer; opacity: 0; transition: opacity 0.2s;
  }
  .photo-slot:hover .photo-slot-remove { opacity: 1; }

  /* ===== VISIBILITY CARD ===== */
  .visibility-body { padding: 4px 24px 20px; }
  .toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 0;
  }
  .toggle-row + .toggle-row { border-top: 1px solid var(--border); }
  .toggle-label { font-size: 13.5px; font-weight: 600; color: var(--text); margin-bottom: 3px; }
  .toggle-sub { font-size: 12px; color: var(--text-muted); }

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

  /* ===== BOTTOM BAR ===== */
  .bottom-bar {
    position: fixed; bottom: 0; left: var(--sidebar-w); right: 0;
    background: var(--card-bg); border-top: 1px solid var(--border);
    padding: 14px 36px;
    display: flex; align-items: center; justify-content: flex-end;
    gap: 10px; z-index: 50;
  }
  .btn-cancel {
    padding: 10px 22px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--card-bg);
    font-size: 13.5px; font-weight: 600; color: var(--text);
    cursor: pointer; transition: all 0.2s;
  }
  .btn-cancel:hover { background: #f0f4f0; }
  .btn-save {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 10px;
    border: none; background: var(--green-dark);
    font-size: 13.5px; font-weight: 700; color: #fff;
    cursor: pointer; transition: background 0.2s;
  }
  .btn-save svg { width: 15px; height: 15px; }
  .btn-save:hover { background: var(--green-accent); }

  .content { padding-bottom: 80px !important; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1100px) {
    .add-grid { grid-template-columns: 1fr 320px; }
  }

  @media (max-width: 900px) {
    .add-grid { grid-template-columns: 1fr; }
    .pricing-grid { grid-template-columns: 1fr 1fr; }
    .right-col-add { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .right-col-add .add-card { margin-bottom: 0; }
  }

  @media (max-width: 768px) {
    .page-title { font-size: 20px; }
    .page-sub { font-size: 12.5px; }
    .bottom-bar { left: 0; padding: 12px 16px; }
    .card-body { padding: 18px 18px; }
    .media-card-body { padding: 16px 18px; }
    .card-title { padding: 16px 18px 14px; }
    .visibility-body { padding: 4px 18px 16px; }
    .two-col { grid-template-columns: 1fr 1fr; }
    .right-col-add { grid-template-columns: 1fr; }
  }

  @media (max-width: 520px) {
    .two-col { grid-template-columns: 1fr; }
    .pricing-grid { grid-template-columns: 1fr; }
    .photo-slots { grid-template-columns: repeat(3, 1fr); }
  }

</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.inventaris.store') }}" enctype="multipart/form-data" id="tambah-produk-form">
  @csrf
  @if($errors->any())
    <div style="background:#fff0f0;border:1px solid #f5c6c0;border-radius:10px;padding:14px 18px;margin-bottom:18px;font-size:13.5px;color:#c0392b;">
      <ul style="list-style:disc;padding-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif



      <!-- Breadcrumb -->
      <div class="breadcrumb">
        <a href="{{ route('admin.inventaris.index') }}">
          <svg style="width:13px;height:13px;vertical-align:-2px;margin-right:3px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
          Inventory
        </a>
        <span class="breadcrumb-sep">/</span>
        <span>Tambah Produk Baru</span>
      </div>

      <!-- Page header -->
      <h1 class="page-title">Tambah Produk Baru</h1>
      <p class="page-sub">Buat listing rangkaian bunga baru untuk katalog TuquParcel.</p>

      <!-- Grid -->
      <div class="add-grid">

        <!-- LEFT: Product Info + Pricing -->
        <div class="left-col-add">

          <!-- Informasi Produk -->
          <div class="add-card">
            <div class="card-hero">
              <div class="card-hero-overlay"></div>
              <div class="card-hero-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Informasi Produk
              </div>
            </div>

            <div class="card-body">
              <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" class="form-input" name="name" value="{{ old('name') }}" placeholder="cth. Buket Mawar Premium" required>
              </div>

              <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" name="description" placeholder="Deskripsikan rangkaian bunga, kecocokan acara, dan cara perawatan...">{{ old('description') }}</textarea>
              </div>

              <div class="form-group two-col">
                <div>
                  <label class="form-label">Kategori</label>
                  <select class="form-select" name="category_id" id="category-select" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label class="form-label">SKU</label>
                  <div class="sku-wrap">
                    <span class="sku-prefix">TP-</span>
                    <input type="text" class="form-input sku-input" id="sku-field" name="sku_suffix" value="{{ old('sku_suffix', '0001') }}" placeholder="0001">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Isi Parcel</label>
                <div class="tags-wrap" id="parcel-wrap" onclick="focusTagInput()">
                  <div id="parcel-hidden"></div>
                  <input class="tag-input" type="text" id="tag-input-field" placeholder="+ Tambah item lalu tekan Enter" onkeydown="addTag(event, this)">
                </div>
              </div>
            </div>
          </div>

          <!-- Pricing & Stock -->
          <div class="add-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
              Pricing & Stock
            </div>
            <div class="card-body">
              <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <div class="price-wrap">
                  <span class="price-prefix">Rp</span>
                  <input type="number" class="form-input price-input" name="price" value="{{ old('price') }}" placeholder="150000" step="1000" min="0" required>
                </div>
              </div>

              <div class="pricing-grid">
                <div>
                  <label class="form-label">Diskon (%)</label>
                  <div class="suffix-wrap">
                    <input type="number" class="form-input suffix-input" name="discount_percent" value="{{ old('discount_percent', 0) }}" placeholder="0" min="0" max="100">
                    <span class="input-suffix">%</span>
                  </div>
                </div>
                <div>
                  <label class="form-label">Stok</label>
                  <input type="number" class="form-input" name="stock" value="{{ old('stock', 0) }}" placeholder="0" min="0" required>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /left-col-add -->

        <!-- RIGHT: Media + Visibility -->
        <div class="right-col-add">

          <!-- Media -->
          <div class="add-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              Media
            </div>
            <div class="media-card-body">

              <!-- Drop zone -->
              <div class="upload-zone"
                id="drop-zone"
                onclick="document.getElementById('file-upload').click()"
                ondragover="handleDragOver(event)"
                ondragleave="handleDragLeave(event)"
                ondrop="handleDrop(event)">
                <div class="upload-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                </div>
                <div class="upload-text">Klik untuk upload atau drag & drop</div>
                <div class="upload-sub">SVG, PNG, JPG atau GIF (maks. 800×400px)</div>
                <input type="file" id="file-upload" name="images[]" accept="image/*" multiple style="display:none" onchange="handleFileInput(this)">
              </div>

              <!-- Photo slots -->
              <div class="photo-slots" id="photo-slots">
                <!-- Slot 1 — main (prefilled) -->
                <div class="photo-slot" id="slot-0">
                  <img src="https://images.unsplash.com/photo-1490750967868-88df5691166a?w=300&h=300&fit=crop" alt="main">
                  <span class="photo-main-badge">MAIN</span>
                  <span class="photo-slot-remove" onclick="clearSlot(0)">×</span>
                </div>
                <!-- Slot 2 -->
                <div class="photo-slot photo-slot-empty" id="slot-1" onclick="document.getElementById('file-upload').click()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <!-- Slot 3 -->
                <div class="photo-slot photo-slot-empty" id="slot-2" onclick="document.getElementById('file-upload').click()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
              </div>

            </div>
          </div>

          <!-- Visibility -->
          <div class="add-card">
            <div class="card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              Visibility
            </div>
            <div class="visibility-body">
              <div class="toggle-row">
                <div>
                  <div class="toggle-label">Publikasikan Produk</div>
                  <div class="toggle-sub">Tampilkan produk di toko</div>
                </div>
                <label class="switch">
                  <input type="checkbox" name="is_published" value="1" checked>
                  <span class="switch-track"></span>
                </label>
              </div>
              <div class="toggle-row">
                <div>
                  <div class="toggle-label">Unggulan</div>
                  <div class="toggle-sub">Tampilkan di bagian "Unggulan"</div>
                </div>
                <label class="switch">
                  <input type="checkbox" name="is_featured" value="1">
                  <span class="switch-track"></span>
                </label>
              </div>
            </div>
          </div>

        </div><!-- /right-col-add -->
      </div><!-- /add-grid -->

</form>

@endsection

@section('bottom_bar')
  <div class="bottom-bar">
    <button type="button" class="btn-cancel" onclick="window.history.back()">Batal</button>
    <button type="submit" form="tambah-produk-form" class="btn-save">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Save Product
    </button>
  </div>
@endsection

@push('scripts')
<script>
  // ===== ISI PARCEL TAGS =====
  function focusTagInput() {
    document.getElementById('tag-input-field').focus();
  }

  function addTag(e, input) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    const val = input.value.trim();
    if (!val) return;

    const wrap = document.getElementById('parcel-wrap');
    const hidden = document.getElementById('parcel-hidden');

    // Visible tag chip
    const tag = document.createElement('span');
    tag.className = 'tag-item';
    const tagId = 'tag-' + Date.now();
    tag.dataset.tagId = tagId;
    tag.innerHTML = val + ' <span class="tag-remove" onclick="removeTag(this)">×</span>';
    wrap.insertBefore(tag, input);

    // Hidden input for form submission
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'contents[]';
    hiddenInput.value = val;
    hiddenInput.id = 'hidden-' + tagId;
    hidden.appendChild(hiddenInput);

    input.value = '';
  }

  function removeTag(el) {
    const tag = el.parentElement;
    const tagId = tag.dataset.tagId;
    // Remove hidden input
    const hiddenInput = document.getElementById('hidden-' + tagId);
    if (hiddenInput) hiddenInput.remove();
    tag.remove();
  }

  // ===== DRAG & DROP FOTO =====
  function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.add('dragging');
  }
  function handleDragLeave(e) {
    document.getElementById('drop-zone').classList.remove('dragging');
  }
  function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.remove('dragging');
    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    addFilesToInput(files);
  }

  // Store files for submission
  let selectedFiles = [];

  function handleFileInput(input) {
    const files = Array.from(input.files);
    files.forEach(f => selectedFiles.push(f));
    renderSlots();
  }

  function addFilesToInput(files) {
    files.forEach(f => selectedFiles.push(f));
    renderSlots();
  }

  function renderSlots() {
    // Update slot 0 (always main/first)
    for (let i = 0; i < 3; i++) {
      const slot = document.getElementById('slot-' + i);
      if (!slot) continue;
      if (selectedFiles[i]) {
        const url = URL.createObjectURL(selectedFiles[i]);
        slot.classList.remove('photo-slot-empty');
        slot.innerHTML = `<img src="${url}" alt="foto">`
          + (i === 0 ? '<span class="photo-main-badge">UTAMA</span>' : '')
          + `<span class="photo-slot-remove" onclick="clearSlot(${i})">×</span>`;
        slot.onclick = null;
      }
    }
    syncFilesToInput();
  }

  function clearSlot(index) {
    selectedFiles.splice(index, 1);
    renderSlots();
    // Reset empty slots
    for (let i = selectedFiles.length; i < 3; i++) {
      const slot = document.getElementById('slot-' + i);
      if (slot) {
        slot.classList.add('photo-slot-empty');
        slot.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>`;
        slot.onclick = () => document.getElementById('file-upload').click();
      }
    }
  }

  function syncFilesToInput() {
    // Create a new DataTransfer to sync selectedFiles back to file input
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('file-upload').files = dt.files;
  }
</script>
@endpush