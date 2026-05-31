@extends('layouts.public')

@section('title', 'Checkout — TuquParcel')

@push('styles')
<style>
  @media (max-width: 768px) {
    .checkout-top { padding-top: 20px !important; }
    .checkout-grid {
      grid-template-columns: 1fr !important;
    }
    .form-grid-2 {
      grid-template-columns: 1fr !important;
    }
    .container {
      padding-left: 16px !important;
      padding-right: 16px !important;
    }
  }
</style>
@endpush

@section('content')
<div style="min-height:100vh;background:var(--ivory);padding-top:88px;" class="checkout-top">
<div class="container" style="padding-top:40px;padding-bottom:60px;">

  <div style="margin-bottom:32px;">
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:700;color:var(--text-dark);margin-bottom:6px;margin-top: 20px;">Checkout</h1>
    <p style="font-size:0.875rem;color:var(--text-muted);">Isi data pengiriman dan periksa pesananmu</p>
  </div>

  @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:14px 18px;border-radius:8px;margin-bottom:24px;font-size:0.9rem;">
      <ul style="margin:0;padding-left:16px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('checkout.store') }}">
    @csrf
    {{-- Kirim cart IDs --}}
    <input type="hidden" name="cart_ids" value="{{ $cartItems->pluck('id')->join(',') }}">

    <div style="display:grid;grid-template-columns:1fr 360px;gap:28px;align-items:start;" class="checkout-grid">

      {{-- Kiri: Form data penerima --}}
      <div style="background:white;border-radius:8px;padding:28px;border:1px solid var(--border);">
        <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;color:var(--text-dark);margin-bottom:22px;">Data Penerima</h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;" class="form-grid-2">
          <div>
            <label style="display:block;font-size:0.85rem;font-weight:500;color:var(--green-dark);margin-bottom:6px;">Nama Penerima *</label>
            <input type="text" name="recipient_name" value="{{ old('recipient_name', Auth::guard('customer')->user()->name) }}" required
              style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;"
              onfocus="this.style.borderColor='var(--green-dark)'" onblur="this.style.borderColor='var(--border)'">
          </div>
          <div>
            <label style="display:block;font-size:0.85rem;font-weight:500;color:var(--green-dark);margin-bottom:6px;">No. Telepon *</label>
            <input type="tel" name="recipient_phone" value="{{ old('recipient_phone', Auth::guard('customer')->user()->phone) }}" required
              style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;"
              onfocus="this.style.borderColor='var(--green-dark)'" onblur="this.style.borderColor='var(--border)'">
          </div>
        </div>

        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:0.85rem;font-weight:500;color:var(--green-dark);margin-bottom:6px;">Kota / Kabupaten *</label>
          <input type="text" name="recipient_city" value="{{ old('recipient_city') }}" required placeholder="contoh: Surabaya"
            style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;"
            onfocus="this.style.borderColor='var(--green-dark)'" onblur="this.style.borderColor='var(--border)'">
        </div>

        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:0.85rem;font-weight:500;color:var(--green-dark);margin-bottom:6px;">Alamat Lengkap *</label>
          <textarea name="recipient_address" required rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..."
            style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;resize:vertical;"
            onfocus="this.style.borderColor='var(--green-dark)'" onblur="this.style.borderColor='var(--border)'">{{ old('recipient_address', Auth::guard('customer')->user()->address) }}</textarea>
        </div>

        <div>
          <label style="display:block;font-size:0.85rem;font-weight:500;color:var(--green-dark);margin-bottom:6px;">Catatan <span style="color:var(--text-muted);font-weight:400;">(opsional)</span></label>
          <textarea name="notes" rows="2" placeholder="Pesan khusus, warna pilihan, dll..."
            style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;resize:vertical;"
            onfocus="this.style.borderColor='var(--green-dark)'" onblur="this.style.borderColor='var(--border)'">{{ old('notes') }}</textarea>
        </div>
      </div>

      {{-- Kanan: Ringkasan produk --}}
      <div>
        <div style="background:white;border-radius:8px;padding:24px;border:1px solid var(--border);margin-bottom:16px;">
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:700;color:var(--text-dark);margin-bottom:16px;">
            Produk
          </h3>

          @foreach($cartItems as $item)
          <div style="display:flex;gap:12px;align-items:center;padding-bottom:12px;margin-bottom:12px;border-bottom:1px solid var(--border);">
            @php $img = $item->product->images->first(); @endphp
            @if($img)
              <img src="{{ asset('storage/'.$img->image_path) }}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;flex-shrink:0;">
            @else
              <div style="width:56px;height:56px;background:var(--ivory-dark);border-radius:6px;flex-shrink:0;"></div>
            @endif
            <div style="flex:1;min-width:0;">
              <p style="font-size:0.875rem;font-weight:600;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->product->name }}</p>
              <p style="font-size:0.8rem;color:var(--text-muted);">x{{ $item->quantity }}</p>
            </div>
            <p style="font-size:0.875rem;font-weight:700;color:var(--green-dark);flex-shrink:0;">
              Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
            </p>
          </div>
          @endforeach

          <div style="display:flex;justify-content:space-between;font-size:0.875rem;margin-bottom:16px;">
            <span style="color:var(--text-muted);">Subtotal</span>
            <span style="font-weight:600;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>
          <div style="border-top:1px solid var(--border);padding-top:14px;display:flex;justify-content:space-between;">
            <span style="font-weight:700;">Total</span>
            <span style="font-weight:700;font-size:1.1rem;color:var(--green-dark);">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>
        </div>

        <button type="submit"
          style="width:100%;background:var(--green-dark);color:var(--ivory);border:none;padding:15px;border-radius:var(--radius-btn);font-size:1rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.2s;letter-spacing:0.01em;"
          onmouseover="this.style.background='var(--green-mid)'" onmouseout="this.style.background='var(--green-dark)'">
          Lanjut ke Pembayaran
        </button>
        <a href="{{ route('cart.index') }}"
          style="display:flex;align-items:center;justify-content:center;width:100%;margin-top:10px;padding:14px;border-radius:var(--radius-btn);border:1.5px solid var(--green-dark);color:var(--green-dark);font-size:1rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;transition:background 0.2s,color 0.2s;box-sizing:border-box;"
          onmouseover="this.style.background='var(--green-light)'" onmouseout="this.style.background='transparent'">
          Kembali ke Keranjang
        </a>
      </div>

    </div>
  </form>

</div>
</div>
@endsection
