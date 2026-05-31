@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
  /* ===== STAT CARDS ===== */
  .stats-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 28px;
  }
  .stat-card {
    background: var(--card-bg); border-radius: 16px; padding: 20px 22px;
    border: 1px solid var(--border); display: flex; flex-direction: column; gap: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04); transition: box-shadow 0.2s;
  }
  .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
  .stat-header { display: flex; align-items: center; justify-content: space-between; }
  .stat-label { font-size: 12.5px; font-weight: 500; color: var(--text-muted); }
  .stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .stat-icon.green { background: #e8f5ee; color: var(--green-accent); }
  .stat-icon.orange { background: #fff3e0; color: #f57c00; }
  .stat-icon.red { background: #ffeaea; color: var(--red); }
  .stat-icon.blue { background: #e8f0fe; color: #3b5bdb; }
  .stat-icon svg { width: 18px; height: 18px; }
  .stat-value { font-size: 30px; font-weight: 800; color: var(--text); line-height: 1; }
  .stat-trend { font-size: 12px; font-weight: 600; color: #38a169; }
  .stat-trend.neutral { color: var(--text-muted); }

  /* ===== TABLE ===== */
  .product-cell { display: flex; align-items: center; gap: 12px; }
  .product-thumb {
    width: 38px; height: 38px; border-radius: 10px; background: #e8f5ee;
    flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 18px;
  }
  .product-name { font-weight: 600; font-size: 13.5px; }
  .stock { font-weight: 600; }
  .btn-action {
    padding: 6px 16px; border-radius: 8px; border: 1.5px solid var(--border);
    background: var(--card-bg); font-size: 12.5px; font-weight: 600;
    color: var(--text); cursor: pointer; transition: all 0.2s; text-decoration: none;
  }
  .btn-action:hover { border-color: var(--green-accent); color: var(--green-accent); background: #f0faf4; }
  .btn-action.backorder { border-color: #c6d0c8; color: var(--text-muted); }
  .show-more {
    text-align: center; padding: 14px; font-size: 13px; font-weight: 600;
    color: var(--green-accent); cursor: pointer; display: flex;
    align-items: center; justify-content: center; gap: 6px;
    transition: background 0.2s; border-top: 1px solid var(--border);
  }
  .show-more:hover { background: #f8faf8; }

  @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } .stat-value { font-size: 24px; } }
  @media (max-width: 480px) { .stat-card { padding: 14px; } .stat-value { font-size: 22px; } }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1>Ringkasan Dashboard</h1>
  <p>Selamat Datang Kembali, {{ auth()->user()->name }}. Berikut Status produk anda hari ini.</p>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-header">
      <span class="stat-label">Total Produk</span>
      <div class="stat-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
    </div>
    <div class="stat-value">{{ number_format($totalProducts) }}</div>
    <div class="stat-trend">Produk Aktif</div>
  </div>
  <div class="stat-card">
    <div class="stat-header">
      <span class="stat-label">Stok Rendah</span>
      <div class="stat-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
    </div>
    <div class="stat-value">{{ $lowStock }}</div>
    <div class="stat-trend {{ $lowStock > 0 ? '' : 'neutral' }}">{{ $lowStock > 0 ? '⚠ Perlu Perhatian' : 'Aman' }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-header">
      <span class="stat-label">Stok Habis</span>
      <div class="stat-icon red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
    </div>
    <div class="stat-value">{{ $outOfStock }}</div>
    <div class="stat-trend neutral">Habis</div>
  </div>
  <div class="stat-card">
    <div class="stat-header">
      <span class="stat-label">Kategori</span>
      <div class="stat-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg></div>
    </div>
    <div class="stat-value">{{ $categories }}</div>
    <div class="stat-trend">Kategori</div>
  </div>
</div>

@if($pendingReviews > 0)
<div class="section-card" style="margin-bottom:20px;background:#fff8e1;border:1px solid #ffe082;border-radius:12px;padding:14px 20px;display:flex;align-items:center;gap:10px;">
  <span style="font-size:18px;">💬</span>
  <span style="font-size:13.5px;font-weight:600;color:#795548;">{{ $pendingReviews }} ulasan baru menunggu persetujuan.</span>
  <a href="{{ route('admin.review.index') }}" style="margin-left:auto;font-size:13px;font-weight:700;color:#276749;text-decoration:none;border:1.5px solid #276749;border-radius:8px;padding:6px 14px;">Lihat Ulasan</a>
</div>
@endif

{{-- Low Stock Alerts --}}
<div class="section-card">
  <div class="section-head">
    <div class="section-title">⚠️ Peringatan Stok Hampir Habis</div>
    <a class="view-all" href="{{ route('admin.inventaris.index', ['status'=>'low']) }}">Lihat Semua</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Produk</th><th>SKU</th><th>Kategori</th><th>Level Stok</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($lowStockProducts as $product)
        <tr>
          <td>
            <div class="product-cell">
              <div class="product-thumb">
                @if($product->images->first())
                  <img src="{{ $product->images->first()->url }}" alt="" style="width:38px;height:38px;border-radius:10px;object-fit:cover;">
                @else
                  🌸
                @endif
              </div>
              <span class="product-name">{{ $product->name }}</span>
            </div>
          </td>
          <td><span class="sku">{{ $product->sku }}</span></td>
          <td><span class="category">{{ $product->category->name ?? '-' }}</span></td>
          <td><span class="stock">{{ $product->stock }} unit</span></td>
          <td>
            @if($product->stock == 0)
              <span class="badge out">Stok Habis</span>
            @else
              <span class="badge {{ $product->stock <= 5 ? 'low' : 'low' }}">
                {{ $product->stock <= 5 ? 'Rendah' : 'Rendah' }}
              </span>
            @endif
          </td>
          <td>
            <a href="{{ route('admin.inventaris.edit', $product) }}" class="btn-action">Edit</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:30px;color:#6b7f72;">Semua stok dalam kondisi baik ✅</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
