@extends('layouts.public')

@section('title', 'Pesanan Saya — TuquParcel')

@push('styles')
<style>
  .account-layout {
    max-width: 1000px;
    margin: 0 auto;
    padding: 120px 24px 80px;
    display: block;
  }

  /* ── MAIN ── */
  .account-main {}
  .account-page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: #2D4A3E;
    margin-bottom: 4px;
  }
  .account-page-sub {
    font-size: 0.83rem;
    color: #6B6560;
    margin-bottom: 28px;
  }

  /* ── ORDER CARD ── */
  .order-card {
    background: #fff;
    border: 1px solid #E0D9D0;
    border-radius: 10px;
    margin-bottom: 16px;
    overflow: hidden;
    transition: box-shadow 0.2s;
  }
  .order-card:hover { box-shadow: 0 4px 20px rgba(45,74,62,0.09); }

  .order-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: #F7F3EE;
    border-bottom: 1px solid #E0D9D0;
    flex-wrap: wrap;
    gap: 8px;
  }
  .order-number {
    font-size: 0.78rem;
    font-weight: 700;
    color: #2D4A3E;
    letter-spacing: 0.05em;
  }
  .order-date {
    font-size: 0.75rem;
    color: #6B6560;
    margin-top: 2px;
  }
  .order-badge {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
  }
  .badge-pending   { background: #FEF3C7; color: #92400E; }
  .badge-paid      { background: #DBEAFE; color: #1E40AF; }
  .badge-approved  { background: #D1FAE5; color: #065F46; }
  .badge-processing{ background: #EDE9FE; color: #5B21B6; }
  .badge-shipped   { background: #CFFAFE; color: #155E75; }
  .badge-completed { background: #D1FAE5; color: #065F46; }
  .badge-cancelled { background: #FEE2E2; color: #991B1B; }

  .order-card-body {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
  }
  .order-items-preview {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
    flex: 1;
  }
  .order-item-thumb {
    width: 48px;
    height: 48px;
    border-radius: 6px;
    object-fit: cover;
    border: 1px solid #E0D9D0;
    background: #F7F3EE;
  }
  .order-item-info {
    flex: 1;
  }
  .order-item-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #1A1A1A;
    line-height: 1.3;
  }
  .order-item-qty {
    font-size: 0.78rem;
    color: #6B6560;
    margin-top: 2px;
  }
  .order-more-items {
    font-size: 0.75rem;
    color: #6B6560;
    white-space: nowrap;
  }

  .order-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 1px solid #F0EBE4;
    flex-wrap: wrap;
    gap: 10px;
  }
  .order-total {
    font-size: 0.9rem;
    font-weight: 700;
    color: #2D4A3E;
  }
  .order-total span {
    font-size: 0.75rem;
    font-weight: 400;
    color: #6B6560;
    margin-right: 4px;
  }
  .order-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .btn-order {
    font-size: 0.78rem;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    font-family: 'DM Sans', sans-serif;
  }
  .btn-order-primary {
    background: #2D4A3E;
    color: #F7F3EE;
  }
  .btn-order-primary:hover { background: #3D6B59; color: #F7F3EE; }
  .btn-order-outline {
    background: transparent;
    color: #2D4A3E;
    border: 1.5px solid #2D4A3E;
  }
  .btn-order-outline:hover { background: #2D4A3E; color: #F7F3EE; }
  .btn-order-bayar {
    background: var(--green-dark);
    color: var(--ivory);
  }
  .btn-order-bayar:hover { background: var(--green-mid); }
  .btn-order-cancel {
    background: transparent;
    color: #dc2626;
    border: 1.5px solid #dc2626;
  }
  .btn-order-cancel:hover { background: #fef2f2; }

  /* Modal Cancel */
  .cancel-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 999;
    background: rgba(0,0,0,0.45);
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .cancel-modal-backdrop.open { display: flex; }
  .cancel-modal {
    background: #fff;
    border-radius: 12px;
    max-width: 400px;
    width: 100%;
    padding: 28px 24px 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  }
  .cancel-modal-icon {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: #FEE2E2;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
  }
  .cancel-modal h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1A1A1A;
    text-align: center;
    margin-bottom: 8px;
  }
  .cancel-modal p {
    font-size: 0.85rem;
    color: #6B6560;
    text-align: center;
    line-height: 1.6;
    margin-bottom: 24px;
  }
  .cancel-modal-actions {
    display: flex;
    gap: 10px;
  }
  .cancel-modal-actions button,
  .cancel-modal-actions a {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    border: none;
    transition: all 0.2s;
  }
  .btn-modal-cancel-confirm {
    background: #dc2626;
    color: #fff;
  }
  .btn-modal-cancel-confirm:hover { background: #b91c1c; }
  .btn-modal-back {
    background: #F7F3EE;
    color: #2D4A3E;
    border: 1.5px solid #E0D9D0 !important;
  }
  .btn-modal-back:hover { background: #E0D9D0; }

  /* upload proof inline */
  .upload-proof-form {
    width: 100%;
    padding: 12px 20px;
    background: #FFFBF5;
    border-top: 1px solid #E0D9D0;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .upload-proof-form label {
    font-size: 0.78rem;
    color: #6B6560;
  }
  .upload-proof-form input[type="file"] {
    font-size: 0.78rem;
    flex: 1;
    min-width: 200px;
  }

  /* empty state */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border: 1px solid #E0D9D0;
    border-radius: 10px;
  }
  .empty-state svg { color: #C9A96E; margin-bottom: 16px; }
  .empty-state h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    color: #2D4A3E;
    margin-bottom: 8px;
  }
  .empty-state p { font-size: 0.85rem; color: #6B6560; margin-bottom: 20px; }


  /* flash */
  .flash-success {
    background: #D1FAE5;
    border: 1px solid #6EE7B7;
    color: #065F46;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 0.85rem;
    margin-bottom: 20px;
  }

  @media (max-width: 768px) {
    .account-layout {
      display: block !important;
      padding: 0 !important;
      gap: 0 !important;
    }
    .account-main, main {
      padding: 20px 16px 60px !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }
    .account-page-title {
        font-size: 1.4rem !important;
        margin-top: 80px !important;
    }
    .form-row { grid-template-columns: 1fr !important; }
    .info-grid { grid-template-columns: 1fr !important; }
    .detail-card-body { padding: 14px 16px !important; }
    .account-card-body { padding: 16px !important; }
    .proof-img { max-width: 100% !important; }
    .btn-back { padding: 0 !important; }
    .order-card-header,
    .order-card-body,
    .order-card-footer { padding-left: 14px !important; padding-right: 14px !important; }
  }

</style>
@endpush

@section('content')
<div class="account-layout">

  {{-- Main Content --}}
  <main class="account-main">
    <h1 class="account-page-title">Pesanan Saya</h1>
    <p class="account-page-sub">Riwayat semua pesanan kamu di TuquParcel.</p>

    @if(session('success'))
      <div class="flash-success">✓ {{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
    @php
      $statusLabels = [
        'pending'    => 'Menunggu Bayar',
        'paid'       => 'Sudah Bayar',
        'approved'   => 'Dikonfirmasi',
        'processing' => 'Diproses',
        'shipped'    => 'Dikirim',
        'completed'  => 'Selesai',
        'cancelled'  => 'Dibatalkan',
      ];
      $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
      $firstItem = $order->items->first();
      $extraCount = $order->items->count() - 1;
    @endphp
    <div class="order-card">
      {{-- Header --}}
      <div class="order-card-header">
        <div>
          <div class="order-number">{{ $order->order_number }}</div>
          <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        <span class="order-badge badge-{{ $order->status }}">{{ $statusLabel }}</span>
      </div>

      {{-- Body --}}
      <div class="order-card-body">
        <div class="order-items-preview">
          @if($firstItem)
            @php $img = $firstItem->product?->images?->first(); @endphp
            @if($img)
              <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $firstItem->product_name }}" class="order-item-thumb">
            @else
              <div class="order-item-thumb" style="display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#C9A96E" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
              </div>
            @endif
            <div class="order-item-info">
              <div class="order-item-name">{{ $firstItem->product_name }}</div>
              <div class="order-item-qty">{{ $firstItem->quantity }}x &middot; Rp {{ number_format($firstItem->product_price, 0, ',', '.') }}</div>
            </div>
            @if($extraCount > 0)
              <span class="order-more-items">+{{ $extraCount }} produk lain</span>
            @endif
          @endif
        </div>
      </div>

      {{-- Footer --}}
      <div class="order-card-footer">
        <div class="order-total">
          <span>Total</span>Rp {{ number_format($order->total, 0, ',', '.') }}
        </div>
        <div class="order-actions">
          @if($order->status === 'pending')
            <a href="{{ route('checkout.payment', $order->id) }}" class="btn-order btn-order-bayar">Bayar Sekarang</a>
            <button
              type="button"
              class="btn-order btn-order-cancel"
              onclick="openCancelModal('{{ $order->id }}', '{{ $order->order_number }}')">
              Batalkan
            </button>
          @endif
          <a href="{{ route('account.order-detail', $order->id) }}" class="btn-order btn-order-outline">Lihat Detail</a>
        </div>
      </div>
    </div>
    @empty
    <div class="empty-state">
      <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <h3>Belum ada pesanan</h3>
      <p>Kamu belum pernah melakukan pemesanan. Yuk mulai belanja!</p>
      <a href="{{ route('public.katalog') }}" class="btn-order btn-order-primary" style="display:inline-block;">Lihat Katalog</a>
    </div>
    @endforelse
      </div>
  </main>
</div>

{{-- Modal Konfirmasi Cancel --}}
<div class="cancel-modal-backdrop" id="cancelModalBackdrop" onclick="closeCancelModal(event)">
  <div class="cancel-modal">
    <div class="cancel-modal-icon">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      </svg>
    </div>
    <h3>Batalkan Pesanan?</h3>
    <p>Pesanan <strong id="cancelOrderNumber"></strong> akan dibatalkan dan stok produk akan dikembalikan. Tindakan ini tidak dapat diurungkan.</p>
    <div class="cancel-modal-actions">
      <button type="button" class="btn-modal-back" onclick="closeCancelModal()">Kembali</button>
      <form id="cancelForm" method="POST" style="flex:1;margin:0;">
        @csrf
        <button type="submit" class="btn-modal-cancel-confirm" style="width:100%;">Ya, Batalkan</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openCancelModal(orderId, orderNumber) {
  document.getElementById('cancelOrderNumber').textContent = '#' + orderNumber;
  document.getElementById('cancelForm').action = '/akun/pesanan/' + orderId + '/cancel';
  document.getElementById('cancelModalBackdrop').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeCancelModal(e) {
  if (e && e.target !== document.getElementById('cancelModalBackdrop')) return;
  document.getElementById('cancelModalBackdrop').classList.remove('open');
  document.body.style.overflow = '';
}
</script>
@endpush
@endsection
