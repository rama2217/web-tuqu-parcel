@extends('layouts.admin')
@section('title', 'Inventaris')

@push('styles')
<style>
  /* ===== TOPBAR ===== */
  .inv-topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; }
  .inv-title { font-size: 26px; font-weight: 800; color: var(--text); }
  .inv-actions { display: flex; align-items: center; gap: 10px; }

  .btn-export {
    display: flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--card-bg);
    font-size: 13px; font-weight: 600; color: var(--text); cursor: pointer;
    transition: all 0.2s; text-decoration: none;
  }
  .btn-export svg { width: 15px; height: 15px; }
  .btn-export:hover { border-color: var(--green-accent); color: var(--green-accent); }

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

  /* ===== TABS ===== */
  .inv-tabs { display: flex; gap: 4px; margin-bottom: 16px; border-bottom: 2px solid var(--border); }
  .inv-tab {
    padding: 9px 16px; border: none; background: none;
    font-size: 13px; font-weight: 600; color: var(--text-muted);
    cursor: pointer; border-bottom: 2px solid transparent; text-decoration: none;
    margin-bottom: -2px; transition: all 0.2s; border-radius: 6px 6px 0 0;
    display: flex; align-items: center; gap: 6px;
  }
  .inv-tab:hover { color: var(--text); background: rgba(0,0,0,0.03); }
  .inv-tab.active { color: var(--green-dark); border-bottom-color: var(--green-dark); }
  .tab-count { font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 20px; background: #eef0ed; color: var(--text-muted); }
  .tab-count.green  { background: #e8f5ee; color: #276749; }
  .tab-count.orange { background: #fff3e0; color: #c05621; }
  .tab-count.red    { background: #ffeaea; color: var(--red); }

  /* ===== TABLE CELLS ===== */
  .inv-thumb { width: 46px; height: 46px; border-radius: 10px; background: #f0f4f0; display: flex; align-items: center; justify-content: center; font-size: 22px; overflow: hidden; }
  .inv-thumb img { width: 46px; height: 46px; object-fit: cover; border-radius: 10px; }
  .inv-name  { font-weight: 600; font-size: 13.5px; color: var(--text); }
  .inv-sub   { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; font-weight: 400; }
  .price     { font-weight: 700; font-size: 13.5px; color: var(--text); }

  /* Badge — override default */
  .badge.available { background: #e8f5ee; color: #276749; }
  .badge.low       { background: #fff3e0; color: #c05621; }
  .badge.out       { background: #f0f0f0; color: #555; }

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
    font-size: 13px; font-weight: 600; color: var(--text);
    text-decoration: none; transition: all 0.2s;
  }
  .pagination-bar .pagination li > a:hover { border-color: var(--green-accent); color: var(--green-accent); }
  .pagination-bar .pagination li.active > span,
  .pagination-bar .pagination li > span[aria-current] {
    background: var(--green-dark); border-color: var(--green-dark); color: #fff;
  }
  .pagination-bar .pagination li.disabled > span { opacity: 0.4; }

  /* ===== FILTER BAR ===== */
  .inv-filter-bar {
    display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap;
  }
  .filter-select {
    padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 9px;
    font-size: 13px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; cursor: pointer;
    transition: border-color 0.2s; min-width: 140px;
  }
  .filter-select:focus { border-color: var(--green-accent); }
  .filter-reset {
    padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 9px;
    font-size: 13px; font-weight: 600; color: var(--text-muted);
    background: var(--card-bg); cursor: pointer; transition: all 0.2s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
  }
  .filter-reset:hover { border-color: var(--red); color: var(--red); }

  /* ===== SORT HEADER ===== */
  .sortable {
    cursor: pointer; user-select: none; white-space: nowrap;
  }
  .sortable:hover { color: var(--green-dark); }
  .sort-icon { display: inline-block; margin-left: 4px; font-size: 10px; opacity: 0.4; }
  .sort-icon.asc::after  { content: '▲'; }
  .sort-icon.desc::after { content: '▼'; }
  .sort-icon.active { opacity: 1; color: var(--green-dark); }

  /* ===== NUMBERING ===== */
  .row-num {
    font-size: 12px; font-weight: 700; color: var(--text-muted);
    width: 32px; text-align: center;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .inv-topbar { flex-direction: column; align-items: flex-start; }
    .inv-actions { width: 100%; }
    .btn-export, .btn-tambah { flex: 1; justify-content: center; }
    .inv-tabs { overflow-x: auto; }
    .pagination-bar { flex-direction: column; align-items: flex-start; }
    .inv-title { font-size: 20px; }
    .inv-filter-bar { gap: 8px; }
    .filter-select { min-width: 120px; }
  }
</style>
@endpush

@section('content')

{{-- Topbar --}}
<div class="inv-topbar">
  <h1 class="inv-title">Manajemen Inventaris</h1>
  <div class="inv-actions">
    <a href="{{ route('admin.inventaris.export', request()->query()) }}" class="btn-export">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/>
        <line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Export
    </a>
    <a href="{{ route('admin.inventaris.create') }}" class="btn-tambah">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Tambah Produk
    </a>
  </div>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.inventaris.index') }}" id="search-form">
  @if(request('status'))
    <input type="hidden" name="status" value="{{ request('status') }}">
  @endif
  <div class="inv-search-wrap">
    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input
      class="inv-search"
      type="text"
      name="search"
      id="search-input"
      value="{{ request('search') }}"
      placeholder="Cari produk berdasarkan nama, SKU, atau kategori"
      autocomplete="off"
    />
  </div>
</form>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.inventaris.index') }}" id="filter-form">
  @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
  @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
  <div class="inv-filter-bar">
    {{-- Filter Kategori --}}
    <select name="category" class="filter-select" onchange="document.getElementById('filter-form').submit()">
      <option value="">Semua Kategori</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
      @endforeach
    </select>

    {{-- Sort --}}
    <select name="sort" class="filter-select" onchange="document.getElementById('filter-form').submit()">
      <option value="">Urutkan</option>
      <option value="name_asc"   {{ request('sort')=='name_asc'   ? 'selected' : '' }}>Nama A–Z</option>
      <option value="name_desc"  {{ request('sort')=='name_desc'  ? 'selected' : '' }}>Nama Z–A</option>
      <option value="price_asc"  {{ request('sort')=='price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
      <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
      <option value="stock_asc"  {{ request('sort')=='stock_asc'  ? 'selected' : '' }}>Stok Terendah</option>
      <option value="stock_desc" {{ request('sort')=='stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
    </select>

    {{-- Reset --}}
    @if(request()->hasAny(['search','category','sort','status']))
      <a href="{{ route('admin.inventaris.index') }}" class="filter-reset">✕ Reset Filter</a>
    @endif
  </div>
</form>

{{-- Tabs --}}
<div class="inv-tabs">
  <a class="inv-tab {{ !request('status') ? 'active' : '' }}"
     href="{{ route('admin.inventaris.index', array_merge(request()->except('status','page'))) }}">
    Semua<span class="tab-count">{{ $totalAll }}</span>
  </a>
  <a class="inv-tab {{ request('status')=='available' ? 'active' : '' }}"
     href="{{ route('admin.inventaris.index', array_merge(request()->except('status','page'), ['status'=>'available'])) }}">
    Tersedia <span class="tab-count green">{{ $totalAvailable }}</span>
  </a>
  <a class="inv-tab {{ request('status')=='low' ? 'active' : '' }}"
     href="{{ route('admin.inventaris.index', array_merge(request()->except('status','page'), ['status'=>'low'])) }}">
    Stok Rendah <span class="tab-count orange">{{ $totalLow }}</span>
  </a>
  <a class="inv-tab {{ request('status')=='out' ? 'active' : '' }}"
     href="{{ route('admin.inventaris.index', array_merge(request()->except('status','page'), ['status'=>'out'])) }}">
    Stok Habis <span class="tab-count red">{{ $totalOut }}</span>
  </a>
</div>

{{-- Table --}}
<div class="section-card" style="padding:0; overflow:hidden;">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:40px; text-align:center; padding-left:12px;">#</th>
          <th>Foto</th>
          <th>
            <a href="{{ route('admin.inventaris.index', array_merge(request()->except('sort','page'), ['sort' => request('sort')=='name_asc' ? 'name_desc' : 'name_asc'])) }}" class="sortable" style="color:inherit;text-decoration:none;">
              Nama Produk
              <span class="sort-icon {{ str_starts_with(request('sort',''),'name') ? 'active ' . (request('sort')=='name_asc' ? 'asc' : 'desc') : 'asc' }}"></span>
            </a>
          </th>
          <th>SKU</th>
          <th>Kategori</th>
          <th>
            <a href="{{ route('admin.inventaris.index', array_merge(request()->except('sort','page'), ['sort' => request('sort')=='price_asc' ? 'price_desc' : 'price_asc'])) }}" class="sortable" style="color:inherit;text-decoration:none;">
              Harga
              <span class="sort-icon {{ str_starts_with(request('sort',''),'price') ? 'active ' . (request('sort')=='price_asc' ? 'asc' : 'desc') : 'asc' }}"></span>
            </a>
          </th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="inv-tbody">
        @forelse($products as $product)
        @php
          $status = $product->stock > 10 ? 'available' : ($product->stock > 0 ? 'low' : 'out');
          $statusLabel = match($status) {
            'available' => 'Tersedia',
            'low'       => 'Stok Rendah',
            default     => 'Habis',
          };
        @endphp
        <tr data-status="{{ $status }}">
          <td class="row-num">{{ $products->firstItem() + $loop->index }}</td>
          <td>
            <div class="inv-thumb">
              @php $img = $product->images->first(); @endphp
              @if($img)
                <img src="{{ $img->url }}" alt="{{ $product->name }}">
              @else
                🌺
              @endif
            </div>
          </td>
          <td>
            <div class="inv-name">
              {{ $product->name }}
              @if($product->badge)
                <span style="font-size:10px; background:#e8f0eb; color:#2d4a3e; border-radius:4px; padding:1px 7px; margin-left:6px; font-weight:600;">{{ $product->badge }}</span>
              @endif
              <div class="inv-sub">Stok: {{ $product->stock }}</div>
            </div>
          </td>
          <td><span class="sku">{{ $product->sku }}</span></td>
          <td><span class="category">{{ $product->category->name ?? '-' }}</span></td>
          <td><span class="price">{{ $product->formatted_price }}</span></td>
          <td><span class="badge {{ $status }}">{{ $statusLabel }}</span></td>
          <td>
            <div class="dots-btn" onclick="toggleMenu(this, event)">⋮
              <div class="dropdown-menu">
                <a href="{{ route('admin.inventaris.edit', $product) }}" class="dropdown-item">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.inventaris.destroy', $product) }}"
                      onsubmit="return confirm('Hapus produk ini?')" style="margin:0;">
                  @csrf @method('DELETE')
                  <button type="submit" class="dropdown-item red-item">🗑️ Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center; padding:48px 24px; color:var(--text-muted); font-size:14px;">
            Tidak ada produk ditemukan.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="pagination-bar">
    <span class="pag-info">
      @if($products->total() > 0)
        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
      @else
        Tidak ada hasil
      @endif
    </span>
    {{ $products->appends(request()->query())->links() }}
  </div>
</div>

@endsection

@push('scripts')
<script>
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

  // Auto-submit search setelah 600ms berhenti ketik
  let searchTimer;
  document.getElementById('search-input').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => document.getElementById('search-form').submit(), 600);
  });
</script>
@endpush
