@extends('layouts.public')

@section('title', 'Keranjang — TuquParcel')


@push('styles')
<style>
  /* Override btn-primary khusus halaman keranjang */
  .btn-primary {
    background: transparent !important;
    color: var(--green-dark) !important;
    border: 1.5px solid var(--green-dark) !important;
  }
  .btn-primary:hover {
    background: var(--green-dark) !important;
    color: var(--ivory) !important;
    box-shadow: none !important;
    transform: none !important;
  }

  @media (max-width: 768px) {
    .cart-top { padding-top: 60px !important; }
    /* Grid keranjang jadi single column */
    [style*="grid-template-columns:1fr 340px"] {
      display: block !important;
    }

    /* Ringkasan pesanan di mobile */
    [style*="position:sticky;top:88px"] {
      position: static !important;
      margin-top: 20px;
    }

    /* Item keranjang mobile */
    .cart-item {
      flex-wrap: wrap;
      gap: 10px !important;
      padding: 12px !important;
    }

    /* Foto lebih kecil di mobile */
    .cart-item img,
    .cart-item > a > img {
      width: 64px !important;
      height: 64px !important;
    }

    /* Subtotal dan hapus di baris bawah */
    .cart-item > div:last-of-type {
      min-width: unset !important;
    }

    /* Container padding */
    .container {
      padding-left: 16px !important;
      padding-right: 16px !important;
    }

    /* Header keranjang */
    [style*="padding-top:40px"] {
      padding-top: 20px !important;
    }
  }
</style>
@endpush

@section('content')
<div style="min-height:100vh;background:var(--ivory);padding-top:88px;" class="cart-top">
<div class="container" style="padding-top:40px;padding-bottom:60px;">

  {{-- Header --}}
  <div style="margin-bottom:32px;">
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:700;color:var(--text-dark);margin-top:20px;">Keranjang Saya</h1>
  </div>

  {{-- Flash via Toast (ditampilkan lewat JS di bawah) --}}
  @if(session('success'))
    <span id="flashSuccess" data-msg="{{ session('success') }}" style="display:none;"></span>
  @endif
  @if(session('error'))
    <span id="flashError" data-msg="{{ session('error') }}" style="display:none;"></span>
  @endif


  @if($cartItems->isEmpty())
    {{-- Keranjang kosong --}}
    <div style="text-align:center;padding:80px 20px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--border);margin:0 auto 20px;display:block;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <p style="font-size:1.1rem;color:var(--text-muted);margin-bottom:24px;">Keranjang kamu masih kosong</p>
      <a href="{{ route('public.katalog') }}" class="btn-primary" style="display:inline-flex;">Mulai Belanja</a>
    </div>

  @else
    <div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;">

      {{-- Kiri: Daftar produk --}}
      <div>
        {{-- Pilih semua --}}
        <div style="background:white;border-radius:8px;padding:14px 20px;margin-bottom:12px;display:flex;align-items:center;gap:12px;border:1px solid var(--border);">
          <input type="checkbox" id="selectAll" style="width:18px;height:18px;accent-color:var(--green-dark);cursor:pointer;"
            onchange="toggleSelectAll(this)">
          <label for="selectAll" style="font-size:0.875rem;font-weight:500;color:var(--text-mid);cursor:pointer;">
            Pilih Semua ({{ $cartItems->count() }} produk)
          </label>
          <form method="POST" action="{{ route('cart.clear') }}" style="margin-left:auto;">
            @csrf @method('DELETE')
            <button type="submit" style="font-size:0.8rem;color:#dc2626;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;"
              onclick="return showConfirm('Hapus semua produk dari keranjang?', this.closest('form'))">
              Hapus Semua
            </button>
          </form>
        </div>

        {{-- Item list --}}
        @foreach($cartItems as $item)
        <div class="cart-item" data-id="{{ $item->id }}" data-product-id="{{ $item->product->id }}" data-price="{{ $item->product->price }}"
          style="background:white;border-radius:8px;padding:16px 20px;margin-bottom:10px;border:1px solid var(--border);display:flex;align-items:center;gap:16px;">

          {{-- Checkbox --}}
          <input type="checkbox" class="item-checkbox" value="{{ $item->id }}"
            style="width:18px;height:18px;accent-color:var(--green-dark);cursor:pointer;flex-shrink:0;"
            onchange="updateSummary()">

          {{-- Foto produk --}}
          <a href="{{ route('public.produk', $item->product->slug) }}" style="flex-shrink:0;">
            @php $img = $item->product->images->first(); @endphp
            @if($img)
              <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $item->product->name }}"
                style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid var(--border);">
            @else
              <div style="width:80px;height:80px;background:var(--ivory-dark);border-radius:6px;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--border);">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            @endif
          </a>

          {{-- Info produk --}}
          <div style="flex:1;min-width:0;">
            <a href="{{ route('public.produk', $item->product->slug) }}"
              style="font-size:0.95rem;font-weight:600;color:var(--text-dark);text-decoration:none;display:block;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              {{ $item->product->name }}
            </a>
            <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:8px;">
              {{ $item->product->category->name ?? '-' }}
            </p>
            <p style="font-size:1rem;font-weight:700;color:var(--green-dark);">
              Rp {{ number_format($item->product->price, 0, ',', '.') }}
            </p>
          </div>

          {{-- Quantity control --}}
          <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <button onclick="changeQty({{ $item->id }}, -1)"
              style="width:30px;height:30px;border:1.5px solid var(--border);background:white;border-radius:6px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;color:var(--text-mid);">−</button>
            <span id="qty-{{ $item->id }}" style="font-size:0.95rem;font-weight:600;min-width:24px;text-align:center;">{{ $item->quantity }}</span>
            <button onclick="changeQty({{ $item->id }}, 1)"
              style="width:30px;height:30px;border:1.5px solid var(--border);background:white;border-radius:6px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;color:var(--text-mid);">+</button>
          </div>

          {{-- Subtotal item --}}
          <div style="text-align:right;flex-shrink:0;min-width:110px;">
            <p style="font-size:0.75rem;color:var(--text-muted);margin-bottom:2px;">Subtotal</p>
            <p id="subtotal-{{ $item->id }}" style="font-size:0.95rem;font-weight:700;color:var(--text-dark);">
              Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
            </p>
          </div>

          {{-- Hapus --}}
          <form method="POST" action="{{ route('cart.remove', $item->id) }}" style="flex-shrink:0;">
            @csrf @method('DELETE')
            <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:4px;" title="Hapus"
              onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='var(--text-muted)'">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </form>
        </div>
        @endforeach
      </div>

      {{-- Kanan: Ringkasan --}}
      <div style="background:white;border-radius:8px;padding:24px;border:1px solid var(--border);position:sticky;top:88px;">
        <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;color:var(--text-dark);margin-bottom:20px;">Ringkasan Pesanan</h3>

        <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Produk dipilih</span>
          <span id="selectedCount" style="font-weight:600;">0 item</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:16px;font-size:0.875rem;">
          <span style="color:var(--text-muted);">Subtotal</span>
          <span id="summarySubtotal" style="font-weight:600;">Rp 0</span>
        </div>
        <div style="border-top:1px solid var(--border);padding-top:16px;margin-bottom:20px;display:flex;justify-content:space-between;">
          <span style="font-weight:700;font-size:1rem;">Total</span>
          <span id="summaryTotal" style="font-weight:700;font-size:1.1rem;color:var(--green-dark);">Rp 0</span>
        </div>

        <button onclick="goCheckout()"
          style="width:100%;background:var(--green-dark);color:var(--ivory);border:none;padding:14px;border-radius:var(--radius-btn);font-size:0.95rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.2s;"
          onmouseover="this.style.background='var(--green-mid)'" onmouseout="this.style.background='var(--green-dark)'">
          Checkout Sekarang
        </button>

        <a href="{{ route('public.katalog') }}"
          style="display:flex;align-items:center;justify-content:center;width:100%;margin-top:10px;padding:14px;border-radius:var(--radius-btn);border:1.5px solid var(--green-dark);color:var(--green-dark);font-size:1rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;transition:background 0.2s,color 0.2s;box-sizing:border-box;"
          onmouseover="this.style.background='var(--green-light)'" onmouseout="this.style.background='transparent'">
          Kembali Ke Katalog
        </a>
      </div>

    </div>
  @endif

</div>
</div>

{{-- Hidden form untuk checkout dengan item terpilih --}}
<form id="checkoutForm" method="GET" action="{{ route('checkout.index') }}" style="display:none;">
  <input type="hidden" name="items" id="checkoutItems">
</form>


{{-- Modal Alert --}}
<div id="modalAlert" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:32px 28px;max-width:360px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#C9A96E" stroke-width="1.5" style="margin:0 auto 16px;display:block;">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
    </svg>
    <p id="modalAlertMsg" style="font-size:0.95rem;color:#1a1a1a;margin-bottom:24px;line-height:1.5;"></p>
    <button onclick="closeAlert()"
      style="background:#2D4A3E;color:#F7F3EE;border:none;padding:10px 32px;border-radius:6px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">
      OK
    </button>
  </div>
</div>

{{-- Modal Confirm --}}
<div id="modalConfirm" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:32px 28px;max-width:360px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="1.5" style="margin:0 auto 16px;display:block;">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>
    <p id="modalConfirmMsg" style="font-size:0.95rem;color:#1a1a1a;margin-bottom:24px;line-height:1.5;"></p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <button onclick="closeConfirm()"
        style="background:transparent;color:#2D4A3E;border:1.5px solid #2D4A3E;padding:10px 24px;border-radius:6px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">
        Batal
      </button>
      <button onclick="doConfirm()"
        style="background:#dc2626;color:#fff;border:none;padding:10px 24px;border-radius:6px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">
        Hapus
      </button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Data harga per item
  const prices = {
    @foreach($cartItems as $item)
      {{ $item->id }}: {{ $item->product->price }},
    @endforeach
  };
  const qtys = {
    @foreach($cartItems as $item)
      {{ $item->id }}: {{ $item->quantity }},
    @endforeach
  };

  function fmt(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
  }

  function updateSummary() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    let total = 0;
    checked.forEach(cb => {
      const id = parseInt(cb.value);
      total += prices[id] * qtys[id];
    });
    document.getElementById('selectedCount').textContent = checked.length + ' item';
    document.getElementById('summarySubtotal').textContent = fmt(total);
    document.getElementById('summaryTotal').textContent = fmt(total);
  }

  function toggleSelectAll(el) {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = el.checked);
    updateSummary();
  }

  function changeQty(id, delta) {
    const current = qtys[id];
    const newQty  = Math.max(1, current + delta);
    if (newQty === current) return;

    qtys[id] = newQty;
    document.getElementById('qty-' + id).textContent = newQty;
    document.getElementById('subtotal-' + id).textContent = fmt(prices[id] * newQty);
    updateSummary();

    // Kirim update ke server
    fetch(`/keranjang/${id}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ quantity: newQty }),
    });
  }

  // ── Toast Notification ──────────────────────────────────
  function showToast(msg, type) {
    const existing = document.getElementById('cartToast');
    if (existing) existing.remove();

    const colors = {
      success: { bg: '#2D4A3E', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>' },
      error:   { bg: '#dc2626', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>' },
      warning: { bg: '#C9A96E', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>' },
    };
    const c = colors[type] || colors.success;

    const toast = document.createElement('div');
    toast.id = 'cartToast';
    toast.style.cssText = [
      'position:fixed',
      'bottom:28px',
      'right:28px',
      'z-index:99999',
      'background:' + c.bg,
      'color:#F7F3EE',
      'padding:14px 20px',
      'border-radius:10px',
      'font-size:0.875rem',
      'font-weight:500',
      'font-family:DM Sans,sans-serif',
      'box-shadow:0 8px 32px rgba(0,0,0,0.18)',
      'display:flex',
      'align-items:center',
      'gap:10px',
      'opacity:0',
      'transform:translateY(14px)',
      'transition:opacity 0.28s ease,transform 0.28s ease',
      'max-width:340px',
      'pointer-events:none',
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

  // Auto-show flash dari session saat halaman load
  document.addEventListener('DOMContentLoaded', function () {
    const s = document.getElementById('flashSuccess');
    const e = document.getElementById('flashError');
    if (s) showToast(s.dataset.msg, 'success');
    if (e) showToast(e.dataset.msg, 'error');
  });

  function goCheckout() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    if (checked.length === 0) {
      showAlert('Pilih minimal 1 produk untuk checkout.');
      return;
    }
    const ids = Array.from(checked).map(cb => cb.value).join(',');
    document.getElementById('checkoutItems').value = ids;
    document.getElementById('checkoutForm').submit();
  }

  // ── Custom Modal Alert ────────────────────────────────
  function showAlert(msg) {
    document.getElementById('modalAlertMsg').textContent = msg;
    document.getElementById('modalAlert').style.display = 'flex';
  }
  function closeAlert() {
    document.getElementById('modalAlert').style.display = 'none';
  }

  // ── Custom Modal Confirm ──────────────────────────────
  let _confirmForm = null;
  function showConfirm(msg, form) {
    _confirmForm = form;
    document.getElementById('modalConfirmMsg').textContent = msg;
    document.getElementById('modalConfirm').style.display = 'flex';
    return false;
  }
  function closeConfirm() {
    document.getElementById('modalConfirm').style.display = 'none';
    _confirmForm = null;
  }
  function doConfirm() {
    document.getElementById('modalConfirm').style.display = 'none';
    if (_confirmForm) _confirmForm.submit();
  }
</script>
@endpush
