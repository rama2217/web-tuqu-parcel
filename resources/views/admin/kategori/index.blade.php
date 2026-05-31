@extends('layouts.admin')
@section('title', 'Kategori')

@push('styles')
<style>
  /* ===== TOPBAR ===== */
  .inv-topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; }
  .inv-title   { font-size: 26px; font-weight: 800; color: var(--text); }
  .inv-actions { display: flex; align-items: center; gap: 10px; }

  .btn-tambah {
    display: flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 10px;
    border: none; background: var(--green-dark); font-size: 13px; font-weight: 700;
    color: #fff; cursor: pointer; transition: background 0.2s; text-decoration: none;
  }
  .btn-tambah svg { width: 14px; height: 14px; }
  .btn-tambah:hover { background: var(--green-accent); color: #fff; }

  /* ===== SEARCH ===== */
  .inv-search-wrap {
    position: relative; margin-bottom: 18px; background: var(--card-bg);
    border: 1.5px solid var(--border); border-radius: 12px;
    display: flex; align-items: center; transition: border-color 0.2s;
  }
  .inv-search-wrap:focus-within { border-color: var(--green-accent); }
  .search-icon { width: 17px; height: 17px; color: var(--text-muted); margin-left: 16px; flex-shrink: 0; }
  .inv-search {
    flex: 1; padding: 12px 16px; border: none; background: transparent;
    font-size: 13.5px; font-family: inherit; color: var(--text); outline: none;
  }
  .inv-search::placeholder { color: var(--text-muted); }

  /* ===== FILTER BAR ===== */
  .inv-filter-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
  .filter-select {
    padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 9px;
    font-size: 13px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; cursor: pointer;
    transition: border-color 0.2s; min-width: 160px;
  }
  .filter-select:focus { border-color: var(--green-accent); }
  .filter-reset {
    padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 9px;
    font-size: 13px; font-weight: 600; color: var(--text-muted);
    background: var(--card-bg); cursor: pointer; transition: all 0.2s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
  }
  .filter-reset:hover { border-color: var(--red); color: var(--red); }

  /* ===== TABLE ===== */
  .kat-name { font-weight: 600; font-size: 13.5px; color: var(--text); }
  .kat-sub  { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
  .row-num  { font-size: 12px; font-weight: 700; color: var(--text-muted); width: 32px; text-align: center; }

  .sortable { cursor: pointer; user-select: none; white-space: nowrap; }
  .sortable:hover { color: var(--green-dark); }
  .sort-icon { display: inline-block; margin-left: 4px; font-size: 10px; opacity: 0.4; }
  .sort-icon.asc::after  { content: '▲'; }
  .sort-icon.desc::after { content: '▼'; }
  .sort-icon.active { opacity: 1; color: var(--green-dark); }

  /* ===== DROPDOWN ===== */
  .dots-btn {
    width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center;
    justify-content: center; font-size: 18px; cursor: pointer; color: var(--text-muted);
    position: relative; transition: background 0.2s; user-select: none;
  }
  .dots-btn:hover { background: #f0f4f0; color: var(--text); }
  .dropdown-menu {
    display: none; position: absolute; right: 0; top: 36px;
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 10px; min-width: 140px; z-index: 50;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12); overflow: hidden;
  }
  .dropdown-menu.open { display: block; }
  .dropdown-item {
    padding: 10px 14px; font-size: 13px; font-weight: 500;
    cursor: pointer; transition: background 0.15s; text-decoration: none;
    display: block; color: var(--text); border: none; width: 100%;
    text-align: left; background: none; font-family: inherit;
  }
  .dropdown-item:hover { background: #f8faf8; }
  .dropdown-item.red-item { color: var(--red); }
  .dropdown-item.red-item:hover { background: #ffeaea; }

  /* ===== MODAL ===== */
  .modal-overlay {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35);
    z-index: 200; align-items: center; justify-content: center;
  }
  .modal-overlay.open { display: flex; }
  .modal-box {
    background: var(--card-bg); border-radius: 16px; padding: 28px 32px;
    width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.18);
  }
  .modal-title { font-size: 18px; font-weight: 800; color: var(--text); margin-bottom: 20px; }
  .form-group { margin-bottom: 16px; }
  .form-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
  .form-input {
    width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
    border-radius: 9px; font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; transition: border-color 0.2s; box-sizing: border-box;
  }
  .form-input:focus { border-color: var(--green-accent); }
  .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 22px; }
  .btn-cancel {
    padding: 9px 18px; border-radius: 10px; border: 1.5px solid var(--border);
    background: var(--card-bg); font-size: 13px; font-weight: 600; color: var(--text-muted);
    cursor: pointer; transition: all 0.2s;
  }
  .btn-cancel:hover { border-color: var(--red); color: var(--red); }
  .btn-save {
    padding: 9px 18px; border-radius: 10px; border: none;
    background: var(--green-dark); font-size: 13px; font-weight: 700;
    color: #fff; cursor: pointer; transition: background 0.2s;
  }
  .btn-save:hover { background: var(--green-accent); }

  /* ===== ALERT ===== */
  .alert {
    padding: 12px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 500;
    margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
  }
  .alert-success { background: #e8f5ee; color: #276749; border: 1px solid #c3e6cb; }
  .alert-error   { background: #ffeaea; color: var(--red); border: 1px solid #f5c6cb; }

  /* ===== PAGINATION ===== */
  .pagination-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 24px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 10px;
  }
  .pag-info { font-size: 12.5px; color: var(--text-muted); }
  .pagination-bar nav { display: flex; }
  .pagination-bar .pagination { display: flex; gap: 4px; list-style: none; margin: 0; padding: 0; }
  .pagination-bar .pagination li > * {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 34px; height: 34px; padding: 0 8px; border-radius: 8px;
    border: 1.5px solid var(--border); background: var(--card-bg);
    font-size: 13px; font-weight: 600; color: var(--text); text-decoration: none; transition: all 0.2s;
  }
  .pagination-bar .pagination li > a:hover { border-color: var(--green-accent); color: var(--green-accent); }
  .pagination-bar .pagination li.active > span,
  .pagination-bar .pagination li > span[aria-current] { background: var(--green-dark); border-color: var(--green-dark); color: #fff; }
  .pagination-bar .pagination li.disabled > span { opacity: 0.4; }

  @media (max-width: 768px) {
    .inv-topbar { flex-direction: column; align-items: flex-start; }
    .inv-actions { width: 100%; }
    .btn-tambah { flex: 1; justify-content: center; }
    .pagination-bar { flex-direction: column; align-items: flex-start; }
    .inv-title { font-size: 20px; }
    .modal-box { margin: 16px; }
  }

  /* ===== TOAST NOTIF ===== */
  .toast-wrap {
    position: fixed; top: 24px; right: 24px; z-index: 999;
    display: flex; flex-direction: column; gap: 10px; pointer-events: none;
  }
  .toast {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: 12px; min-width: 280px; max-width: 360px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12); pointer-events: all;
    animation: toastIn 0.3s ease; font-size: 13.5px; font-weight: 500;
  }
  .toast-success { background: #e8f5ee; color: #1a5c38; border: 1px solid #b7dfc8; }
  .toast-error   { background: #ffeaea; color: #8b1a1a; border: 1px solid #f5b8b8; }
  .toast-icon { font-size: 18px; flex-shrink: 0; }
  .toast-close { margin-left: auto; background: none; border: none; cursor: pointer; font-size: 16px; color: inherit; opacity: 0.5; padding: 0 0 0 8px; line-height: 1; }
  .toast-close:hover { opacity: 1; }
  .toast.hiding { animation: toastOut 0.3s ease forwards; }
  @keyframes toastIn  { from { opacity:0; transform: translateX(40px); } to { opacity:1; transform: translateX(0); } }
  @keyframes toastOut { from { opacity:1; transform: translateX(0); }     to { opacity:0; transform: translateX(40px); } }
  .confirm-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 300; align-items: center; justify-content: center; }
  .confirm-overlay.open { display: flex; }
  .confirm-box { background: var(--card-bg); border-radius: 16px; padding: 28px 32px; width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: toastIn 0.2s ease; }
  .confirm-icon { font-size: 36px; margin-bottom: 12px; }
  .confirm-title { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
  .confirm-desc  { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 24px; }
  .confirm-actions { display: flex; gap: 10px; justify-content: flex-end; }
  .btn-confirm-cancel { padding: 9px 20px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--card-bg); font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: all 0.2s; }
  .btn-confirm-cancel:hover { border-color: var(--text-muted); color: var(--text); }
  .btn-confirm-delete { padding: 9px 20px; border-radius: 10px; border: none; background: #dc3545; font-size: 13px; font-weight: 700; color: #fff; cursor: pointer; transition: background 0.2s; }
  .btn-confirm-delete:hover { background: #b02a37; }
</style>
@endpush

@section('content')

{{-- Toast Container --}}
<div class="toast-wrap" id="toast-wrap"></div>

{{-- Modal Konfirmasi Hapus --}}
<div class="confirm-overlay" id="confirm-overlay">
  <div class="confirm-box">
    <div class="confirm-icon">🗑️</div>
    <div class="confirm-title" id="confirm-title">Hapus Kategori?</div>
    <div class="confirm-desc" id="confirm-desc">Aksi ini tidak bisa dibatalkan.</div>
    <div class="confirm-actions">
      <button class="btn-confirm-cancel" onclick="closeConfirm()">Batal</button>
      <button class="btn-confirm-delete" id="confirm-btn-delete" onclick="doDelete()">Ya, Hapus</button>
    </div>
  </div>
</div>

{{-- Topbar --}}
<div class="inv-topbar">
  <h1 class="inv-title">Manajemen Kategori</h1>
  <div class="inv-actions">
    <button class="btn-tambah" onclick="openModal('modal-tambah')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Kategori
    </button>
  </div>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.kategori.index') }}" id="search-form">
  @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
  <div class="inv-search-wrap">
    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input
      id="search-input"
      class="inv-search"
      type="text"
      name="search"
      placeholder="Cari nama kategori..."
      value="{{ request('search') }}"
      autocomplete="off"
    />
  </div>
</form>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.kategori.index') }}" id="filter-form">
  @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
  <div class="inv-filter-bar">
    <select name="sort" class="filter-select" onchange="document.getElementById('filter-form').submit()">
      <option value="">Urutkan</option>
      <option value="name_asc"     {{ request('sort')=='name_asc'     ? 'selected' : '' }}>Nama A–Z</option>
      <option value="name_desc"    {{ request('sort')=='name_desc'    ? 'selected' : '' }}>Nama Z–A</option>
      <option value="product_desc" {{ request('sort')=='product_desc' ? 'selected' : '' }}>Produk Terbanyak</option>
      <option value="product_asc"  {{ request('sort')=='product_asc'  ? 'selected' : '' }}>Produk Tersedikit</option>
    </select>
    @if(request()->hasAny(['search','sort']))
      <a href="{{ route('admin.kategori.index') }}" class="filter-reset">✕ Reset Filter</a>
    @endif
  </div>
</form>

{{-- Table --}}
<div class="section-card" style="padding:0; overflow:hidden;">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:40px; text-align:center; padding-left:12px;">#</th>
          <th>
            <a href="{{ route('admin.kategori.index', array_merge(request()->except('sort','page'), ['sort' => request('sort')=='name_asc' ? 'name_desc' : 'name_asc'])) }}" class="sortable" style="color:inherit;text-decoration:none;">
              Nama Kategori
              <span class="sort-icon {{ str_starts_with(request('sort',''),'name') ? 'active ' . (request('sort')=='name_asc' ? 'asc' : 'desc') : 'asc' }}"></span>
            </a>
          </th>
          <th>Deskripsi</th>
          <th>
            <a href="{{ route('admin.kategori.index', array_merge(request()->except('sort','page'), ['sort' => request('sort')=='product_desc' ? 'product_asc' : 'product_desc'])) }}" class="sortable" style="color:inherit;text-decoration:none;">
              Jumlah Produk
              <span class="sort-icon {{ str_starts_with(request('sort',''),'product') ? 'active ' . (request('sort')=='product_asc' ? 'asc' : 'desc') : 'desc' }}"></span>
            </a>
          </th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
        <tr>
          <td class="row-num">{{ $categories->firstItem() + $loop->index }}</td>
          <td>
            <div class="kat-name">{{ $cat->name }}</div>
            <div class="kat-sub">Slug: {{ $cat->slug }}</div>
          </td>
          <td>
            <span style="font-size:13px; color:var(--text-muted);">
              {{ $cat->description ? Str::limit($cat->description, 60) : '—' }}
            </span>
          </td>
          <td>
            <span class="badge" style="background:#e8f0eb; color:#2d4a3e;">
              {{ $cat->products_count }} Produk
            </span>
          </td>
          <td>
            <div class="dots-btn" onclick="toggleMenu(this, event)">⋮
              <div class="dropdown-menu">
                <button
                  type="button"
                  class="dropdown-item"
                  onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}'); event.stopPropagation();">
                  ✏️ Edit
                </button>
                <button
                  type="button"
                  class="dropdown-item red-item"
                  onclick="openConfirm('delete-form-{{ $cat->id }}', '{{ addslashes($cat->name) }}'); event.stopPropagation();">
                  🗑️ Hapus
                </button>
              </div>
            </div>
            <form id="delete-form-{{ $cat->id }}" method="POST" action="{{ route('admin.kategori.destroy', $cat->id) }}" style="display:none;">
              @csrf @method('DELETE')
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center; padding:48px 24px; color:var(--text-muted); font-size:14px;">
            Belum ada kategori. Tambahkan kategori pertama Anda!
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="pagination-bar">
    <span class="pag-info">
      @if($categories->total() > 0)
        Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
      @else
        Tidak ada hasil
      @endif
    </span>
    {{ $categories->appends(request()->query())->links() }}
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="modal-tambah" onclick="closeModalOutside(event, 'modal-tambah')">
  <div class="modal-box">
    <div class="modal-title">Tambah Kategori</div>
    <form method="POST" action="{{ route('admin.kategori.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Kategori <span style="color:var(--red)">*</span></label>
        <input type="text" name="name" class="form-input" placeholder="cth. Buket Bunga" required autofocus>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-input" rows="3" placeholder="Deskripsi singkat kategori (opsional)" style="resize:vertical;"></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeModal('modal-tambah')">Batal</button>
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="modal-edit" onclick="closeModalOutside(event, 'modal-edit')">
  <div class="modal-box">
    <div class="modal-title">Edit Kategori</div>
    <form method="POST" id="edit-form" action="">
      @csrf @method('PUT')
      <div class="form-group">
        <label class="form-label">Nama Kategori <span style="color:var(--red)">*</span></label>
        <input type="text" name="name" id="edit-name" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" id="edit-description" class="form-input" rows="3" style="resize:vertical;"></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeModal('modal-edit')">Batal</button>
        <button type="submit" class="btn-save">Perbarui</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Dropdown toggle
  function toggleMenu(el, event) {
    const menu = el.querySelector('.dropdown-menu');
    document.querySelectorAll('.dropdown-menu.open').forEach(m => {
      if (m !== menu) m.classList.remove('open');
    });
    menu.classList.toggle('open');
    event.stopPropagation();
  }
  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
  });

  // Modal tambah/edit
  function openModal(id) { document.getElementById(id).classList.add('open'); }
  function closeModal(id) { document.getElementById(id).classList.remove('open'); }
  function closeModalOutside(event, id) {
    if (event.target === document.getElementById(id)) closeModal(id);
  }

  // Buka modal edit & isi data
  function openEdit(id, name, description) {
    const base = "{{ rtrim(route('admin.kategori.index'), '/') }}/";
    document.getElementById('edit-form').action = base + id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = description;
    openModal('modal-edit');
  }

  // ===== MODAL KONFIRMASI HAPUS =====
  let _deleteFormId = null;
  function openConfirm(formId, name) {
    _deleteFormId = formId;
    document.getElementById('confirm-title').textContent = 'Hapus "' + name + '"?';
    document.getElementById('confirm-desc').textContent = 'Kategori ini akan dihapus permanen. Pastikan tidak ada produk yang menggunakannya.';
    document.getElementById('confirm-overlay').classList.add('open');
  }
  function closeConfirm() {
    _deleteFormId = null;
    document.getElementById('confirm-overlay').classList.remove('open');
  }
  function doDelete() {
    if (_deleteFormId) document.getElementById(_deleteFormId).submit();
  }
  document.getElementById('confirm-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
  });

  // Buka modal tambah otomatis jika ada error validasi
  @if($errors->any())
    openModal('modal-tambah');
  @endif
</script>
@endpush
