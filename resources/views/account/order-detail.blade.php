@extends('layouts.public')

@section('title', 'Detail Pesanan ' . $order->order_number . ' — TuquParcel')

@push('styles')
<style>
  .account-layout {
    max-width: 1000px;
    margin: 0 auto;
    padding: 120px 24px 80px;
    display: block;
  }

  .account-page-title { font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; font-weight: 700; color: #2D4A3E; margin-bottom: 4px; }
  .account-page-sub { font-size: 0.83rem; color: #6B6560; margin-bottom: 28px; }

  .detail-card { background: #fff; border: 1px solid #E0D9D0; border-radius: 10px; overflow: hidden; margin-bottom: 20px; }
  .detail-card-header { padding: 14px 24px; background: #F7F3EE; border-bottom: 1px solid #E0D9D0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
  .detail-card-header h2 { font-family: 'Cormorant Garamond', serif; font-size: 1rem; font-weight: 700; color: #2D4A3E; }
  .detail-card-body { padding: 20px 24px; }

  .order-badge { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; }
  .badge-pending    { background: #FEF3C7; color: #92400E; }
  .badge-paid       { background: #DBEAFE; color: #1E40AF; }
  .badge-approved  { background: #D1FAE5; color: #065F46; }
  .badge-processing { background: #EDE9FE; color: #5B21B6; }
  .badge-shipped    { background: #CFFAFE; color: #155E75; }
  .badge-completed  { background: #D1FAE5; color: #065F46; }
  .badge-cancelled  { background: #FEE2E2; color: #991B1B; }

  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .info-item label { font-size: 0.72rem; font-weight: 600; color: #6B6560; letter-spacing: 0.1em; text-transform: uppercase; display: block; margin-bottom: 4px; }
  .info-item span { font-size: 0.875rem; color: #1A1A1A; font-weight: 500; }

  .order-item-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #F0EBE4; }
  .order-item-row:last-child { border-bottom: none; }
  .order-item-img { width: 56px; height: 56px; border-radius: 8px; object-fit: cover; border: 1px solid #E0D9D0; background: #F7F3EE; flex-shrink: 0; }
  .order-item-name { font-size: 0.875rem; font-weight: 500; color: #1A1A1A; flex: 1; }
  .order-item-qty { font-size: 0.78rem; color: #6B6560; margin-top: 2px; }
  .order-item-subtotal { font-size: 0.875rem; font-weight: 700; color: #2D4A3E; white-space: nowrap; }

  .total-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.875rem; color: #4A4A4A; }
  .total-row.grand { font-weight: 700; font-size: 1rem; color: #2D4A3E; border-top: 1.5px solid #E0D9D0; padding-top: 12px; margin-top: 4px; }

  .btn-back { display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 600; color: #2D4A3E; text-decoration: none; margin-bottom: 20px; }
  .btn-back:hover { opacity: 0.7; }

  .proof-img { max-width: 300px; border-radius: 8px; border: 1px solid #E0D9D0; margin-top: 10px; }

  .upload-form { margin-top: 16px; padding: 16px; background: #FFFBF5; border: 1px dashed #C9A96E; border-radius: 8px; }
  .upload-form label { font-size: 0.8rem; font-weight: 600; color: #2D4A3E; display: block; margin-bottom: 10px; }
  .upload-form input[type="file"] { font-size: 0.82rem; margin-bottom: 12px; display: block; }
  .btn-upload { background: var(--green-dark); color: var(--ivory); border: none; padding: 9px 24px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.2s; }
  .btn-upload:hover { background: var(--green-mid); }

  .flash-success { background: #D1FAE5; border: 1px solid #6EE7B7; color: #065F46; padding: 12px 16px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; }

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
  .btn-modal-cancel-confirm { background: #dc2626; color: #fff; }
  .btn-modal-cancel-confirm:hover { background: #b91c1c; }
  .btn-modal-back { background: #F7F3EE; color: #2D4A3E; border: 1.5px solid #E0D9D0 !important; }
  .btn-modal-back:hover { background: #E0D9D0; }


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
    .account-page-title { font-size: 1.4rem !important; }
    .form-row { grid-template-columns: 1fr !important; }
    .info-grid { grid-template-columns: 1fr !important; }
    .detail-card-body { padding: 14px 16px !important; }
    .account-card-body { padding: 16px !important; }
    .proof-img { max-width: 100% !important; }
    .btn-back {
        padding: 0 !important;
        margin-top:80px !important;
    }
    .order-card-header,
    .order-card-body,
    .order-card-footer { padding-left: 14px !important; padding-right: 14px !important; }
  }

</style>
@endpush

@section('content')
<div class="account-layout">

  {{-- Main --}}
  <main>
    <a href="{{ route('account.orders') }}" class="btn-back">
      Kembali ke Pesanan Saya
    </a>

    <h1 class="account-page-title">Detail Pesanan</h1>
    <p class="account-page-sub">{{ $order->order_number }} &middot; {{ $order->created_at->format('d M Y, H:i') }}</p>

    @if(session('success'))
      <div class="flash-success">✓ {{ session('success') }}</div>
    @endif

    {{-- Status & Info --}}
    <div class="detail-card">
      <div class="detail-card-header">
        <h2>Informasi Pesanan</h2>
        @php
          $statusLabels = ['pending'=>'Menunggu Bayar','paid'=>'Sudah Bayar','approved'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
        @endphp
        <span class="order-badge badge-{{ $order->status }}">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</span>
      </div>
      <div class="detail-card-body">
        <div class="info-grid">
          <div class="info-item">
            <label>No. Pesanan</label>
            <span>{{ $order->order_number }}</span>
          </div>
          <div class="info-item">
            <label>Tanggal Pesan</label>
            <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
          </div>
          <div class="info-item">
            <label>Nama Penerima</label>
            <span>{{ $order->recipient_name }}</span>
          </div>
          <div class="info-item">
            <label>No. Telepon</label>
            <span>{{ $order->recipient_phone }}</span>
          </div>
          <div class="info-item" style="grid-column: 1 / -1;">
            <label>Alamat Pengiriman</label>
            <span>{{ $order->recipient_address }}, {{ $order->recipient_city }}</span>
          </div>
          @if($order->notes)
          <div class="info-item" style="grid-column: 1 / -1;">
            <label>Catatan</label>
            <span>{{ $order->notes }}</span>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Produk --}}
    <div class="detail-card">
      <div class="detail-card-header">
        <h2>Produk Dipesan</h2>
      </div>
      <div class="detail-card-body">
        @foreach($order->items as $item)
        <div class="order-item-row">
          @php $img = $item->product?->images?->first(); @endphp
          @if($img)
            <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $item->product_name }}" class="order-item-img">
          @else
            <div class="order-item-img" style="display:flex;align-items:center;justify-content:center;">
              <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#C9A96E" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
            </div>
          @endif
          <div style="flex:1">
            <div class="order-item-name">{{ $item->product_name }}</div>
            <div class="order-item-qty">{{ $item->quantity }} x Rp {{ number_format($item->product_price, 0, ',', '.') }}</div>
          </div>
          <div class="order-item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
        </div>
        @endforeach

        <div style="margin-top: 16px; padding-top: 4px;">
          <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
          </div>
          <div class="total-row">
            <span>Ongkos Kirim</span>
            <span>{{ $order->shipping_cost > 0 ? 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') : 'Gratis' }}</span>
          </div>
          <div class="total-row grand">
            <span>Total Pembayaran</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Bukti Pembayaran --}}
    <div class="detail-card">
      <div class="detail-card-header">
        <h2>Bukti Pembayaran</h2>
      </div>
      <div class="detail-card-body">
        @if($order->payment_proof)
          <p style="font-size:0.83rem;color:#065F46;font-weight:500;margin-bottom:8px;">✓ Bukti pembayaran sudah diupload.</p>
          @if(str_ends_with(strtolower($order->payment_proof), '.pdf'))
            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" style="font-size:0.83rem;color:#2D4A3E;font-weight:600;">Lihat PDF →</a>
          @else
            <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="proof-img">
          @endif
          @if($order->paid_at)
            <p style="font-size:0.75rem;color:#6B6560;margin-top:8px;">Diupload: {{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }}</p>
          @endif
        @elseif($order->status === 'pending')
          <p style="font-size:0.83rem;color:#92400E;margin-bottom:4px;">⚠ Kamu belum mengupload bukti pembayaran.</p>
          <p style="font-size:0.78rem;color:#6B6560;margin-bottom:12px;">Upload bukti transfer untuk mempercepat proses verifikasi.</p>
          <form method="POST" action="{{ route('checkout.upload-proof', $order->id) }}" enctype="multipart/form-data">
            @csrf
            @error('payment_proof')<div style="font-size:0.75rem;color:#dc2626;margin-bottom:12px;">{{ $message }}</div>@enderror

            <div id="uploadArea"
              style="border:2px dashed #C9A96E;border-radius:8px;padding:28px 16px;text-align:center;cursor:pointer;transition:all 0.2s;margin-bottom:14px;background:#FFFBF5;"
              onclick="document.getElementById('proofInput').click()"
              ondragover="event.preventDefault();this.style.borderColor='#2D4A3E';this.style.background='#F0F7F4';"
              ondragleave="this.style.borderColor='#C9A96E';this.style.background='#FFFBF5';"
              ondrop="handleDropDetail(event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:#C9A96E;margin:0 auto 10px;display:block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
              </svg>
              <p id="uploadLabelDetail" style="font-size:0.85rem;font-weight:500;color:#4A4A4A;margin-bottom:4px;">Klik atau drag & drop file di sini</p>
              <p style="font-size:0.75rem;color:#6B6560;">JPG, PNG, PDF — maks 5MB</p>
            </div>

            <input type="file" id="proofInput" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="showPreviewDetail(this)">

            <div id="previewContainerDetail" style="display:none;margin-bottom:14px;">
              <img id="previewImgDetail" style="width:100%;border-radius:8px;border:1px solid #E0D9D0;object-fit:contain;display:block;">
            </div>

            <button type="submit" class="btn-upload" style="width:100%;padding:11px;">Upload Bukti Pembayaran</button>
          </form>
        @else
          <p style="font-size:0.83rem;color:#6B6560;">Tidak ada bukti pembayaran.</p>
        @endif
      </div>
    </div>

    {{-- Tombol aksi jika masih pending --}}
    @if($order->status === 'pending' && !$order->payment_proof)
    <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
      <button
        type="button"
        onclick="document.getElementById('cancelModalBackdrop').classList.add('open');document.body.style.overflow='hidden';"
        style="display:inline-flex;align-items:center;gap:8px;background:transparent;color:#dc2626;border:1.5px solid #dc2626;padding:11px 24px;border-radius:6px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.2s;"
        onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Batalkan Pesanan
      </button>
      <a href="{{ route('checkout.payment', $order->id) }}" style="display:inline-flex;align-items:center;gap:8px;background:var(--green-dark);color:var(--ivory);padding:11px 28px;border-radius:6px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='var(--green-mid)'" onmouseout="this.style.background='var(--green-dark)'">
        Lihat Halaman Pembayaran
      </a>
    </div>
    @endif
  </main>
</div>

{{-- Modal Konfirmasi Cancel --}}
<div class="cancel-modal-backdrop" id="cancelModalBackdrop" onclick="closeCancelModalDetail(event)">
  <div class="cancel-modal">
    <div class="cancel-modal-icon">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      </svg>
    </div>
    <h3>Batalkan Pesanan?</h3>
    <p>Pesanan <strong>{{ $order->order_number }}</strong> akan dibatalkan dan stok produk akan dikembalikan. Tindakan ini tidak dapat diurungkan.</p>
    <div class="cancel-modal-actions">
      <button type="button" class="btn-modal-back" onclick="closeCancelModalDetail()">Kembali</button>
      <form method="POST" action="{{ route('account.order.cancel', $order->id) }}" style="flex:1;margin:0;">
        @csrf
        <button type="submit" class="btn-modal-cancel-confirm" style="width:100%;">Ya, Batalkan</button>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function showPreviewDetail(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      document.getElementById('uploadLabelDetail').textContent = file.name;
      document.getElementById('uploadArea').style.borderColor = '#2D4A3E';
      document.getElementById('uploadArea').style.background = '#F0F7F4';

      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('previewImgDetail').src = e.target.result;
          document.getElementById('previewContainerDetail').style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    }
  }

  function handleDropDetail(e) {
    e.preventDefault();
    const input = document.getElementById('proofInput');
    input.files = e.dataTransfer.files;
    showPreviewDetail(input);
    document.getElementById('uploadArea').style.borderColor = '#C9A96E';
    document.getElementById('uploadArea').style.background = '#FFFBF5';
  }

  function closeCancelModalDetail(e) {
    if (e && e.target !== document.getElementById('cancelModalBackdrop')) return;
    document.getElementById('cancelModalBackdrop').classList.remove('open');
    document.body.style.overflow = '';
  }
</script>
@endpush
