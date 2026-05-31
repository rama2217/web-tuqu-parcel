@extends('layouts.public')

@section('title', 'Pembayaran — TuquParcel')

@push('styles')
<style>
  @media (max-width: 768px) {
    .container {
      padding-left: 16px !important;
      padding-right: 16px !important;
    }
    /* Rekening bank - tombol salin di bawah */
    [style*="display:flex;justify-content:space-between;align-items:center"] {
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 12px !important;
    }
    /* Nominal pembayaran font lebih kecil */
    [style*="font-size:2.4rem"] {
      font-size: 1.8rem !important;
    }
    /* Upload area padding lebih kecil */
    #uploadArea {
      padding: 20px 16px !important;
    }
  }
</style>
@endpush

@section('content')
<div style="min-height:100vh;background:var(--ivory);padding-top:88px;">
<div class="container" style="padding-top:40px;padding-bottom:60px;max-width:760px;">

  <div style="margin-bottom:28px;">
    <p style="font-size:0.75rem;font-weight:600;letter-spacing:0.18em;text-transform:uppercase;color:var(--gold);margin-bottom:6px;">Pembayaran</p>
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:var(--text-dark);">Selesaikan Pembayaran</h1>
    <p style="font-size:0.875rem;color:var(--text-muted);margin-top:4px;">No. Pesanan: <strong>{{ $order->order_number }}</strong></p>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:0.9rem;">✓ {{ session('success') }}</div>
  @endif

  {{-- Nominal yang harus dibayar --}}
  <div style="background:var(--green-dark);color:var(--ivory);border-radius:8px;padding:24px;margin-bottom:20px;text-align:center;">
    <p style="font-size:0.8rem;opacity:0.75;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:8px;">Total yang harus dibayar</p>
    <p style="font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
    <p style="font-size:0.8rem;opacity:0.6;margin-top:4px;">{{ $order->items->count() }} produk • {{ $order->recipient_name }}</p>
  </div>

  {{-- Rekening tujuan --}}
  <div style="background:white;border-radius:8px;padding:24px;border:1px solid var(--border);margin-bottom:20px;">
    <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:16px;">
      Transfer ke Rekening Berikut
    </h3>

    @forelse($bankAccounts as $bank)
    <div style="border:1.5px solid var(--border);border-radius:8px;padding:16px 20px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
      <div>
        <p style="font-size:0.75rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;">{{ $bank['bank'] }}</p>
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--text-dark);letter-spacing:0.04em;">{{ $bank['number'] }}</p>
        <p style="font-size:0.85rem;color:var(--text-muted);margin-top:2px;">a.n. {{ $bank['name'] }}</p>
      </div>
      <button onclick="copyText('{{ $bank['number'] }}')"
        style="background:var(--ivory);border:1px solid var(--border);border-radius:6px;padding:8px 14px;font-size:0.8rem;font-weight:500;color:var(--green-dark);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;"
        onmouseover="this.style.background='var(--green-light)'" onmouseout="this.style.background='var(--ivory)'">
        Salin
      </button>
    </div>
    @empty
    <p style="font-size:0.9rem;color:var(--text-muted);">Nomor rekening belum diatur. Hubungi kami via WhatsApp.</p>
    @endforelse

    <div style="background:var(--ivory);border-radius:6px;padding:12px 16px;margin-top:8px;">
      <p style="font-size:0.82rem;color:var(--text-muted);line-height:1.6;">
        ⚠️ Mohon transfer sesuai nominal yang tertera. Setelah transfer, upload bukti pembayaran di bawah.
      </p>
    </div>
  </div>

  {{-- Rincian pesanan --}}
  <div style="background:white;border-radius:8px;padding:24px;border:1px solid var(--border);margin-bottom:20px;">
    <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:16px;">Rincian Pesanan</h3>
    @foreach($order->items as $item)
    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.875rem;">
      <div>
        <p style="font-weight:600;color:var(--text-dark);">{{ $item->product_name }}</p>
        <p style="color:var(--text-muted);font-size:0.8rem;">x{{ $item->quantity }} × Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
      </div>
      <p style="font-weight:700;color:var(--green-dark);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
    </div>
    @endforeach
    <div style="display:flex;justify-content:space-between;padding-top:14px;font-weight:700;">
      <span>Total</span>
      <span style="color:var(--green-dark);">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
    </div>
  </div>

  {{-- Upload bukti bayar --}}
  <div style="background:white;border-radius:8px;padding:24px;border:1px solid var(--border);">
    <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:6px;">Upload Bukti Pembayaran</h3>
    <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:20px;">Format: JPG, PNG, atau PDF. Maksimal 5MB.</p>

    @if($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:0.875rem;">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('checkout.upload-proof', $order->id) }}" enctype="multipart/form-data">
      @csrf

      {{-- Upload area --}}
      <div id="uploadArea"
        style="border:2px dashed var(--border);border-radius:8px;padding:32px;text-align:center;cursor:pointer;transition:all 0.2s;margin-bottom:16px;"
        onclick="document.getElementById('proofInput').click()"
        ondragover="event.preventDefault();this.style.borderColor='var(--green-dark)';this.style.background='var(--green-light)'"
        ondragleave="this.style.borderColor='var(--border)';this.style.background='white'"
        ondrop="handleDrop(event)">
        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:var(--text-muted);margin:0 auto 12px;display:block;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <p id="uploadLabel" style="font-size:0.9rem;font-weight:500;color:var(--text-mid);margin-bottom:4px;">Klik atau drag & drop file di sini</p>
        <p style="font-size:0.8rem;color:var(--text-muted);">JPG, PNG, PDF — maks 5MB</p>
      </div>

      <input type="file" id="proofInput" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" style="display:none;"
        onchange="showPreview(this)">

      {{-- Preview --}}
      <div id="previewContainer" style="display:none;margin-bottom:16px;">
        <img id="previewImg" style="width:100%;border-radius:8px;border:1px solid var(--border);object-fit:contain;display:block;">
      </div>

      <button type="submit"
        style="width:100%;background:var(--green-dark);color:var(--ivory);border:none;padding:15px;border-radius:var(--radius-btn);font-size:1rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.2s;letter-spacing:0.01em;"
        onmouseover="this.style.background='var(--green-mid)'" onmouseout="this.style.background='var(--green-dark)'">
        Konfirmasi Pembayaran
      </button>
        <a href="javascript:history.back()"
          style="display:flex;align-items:center;justify-content:center;width:100%;margin-top:10px;padding:14px;border-radius:var(--radius-btn);border:1.5px solid var(--green-dark);color:var(--green-dark);font-size:1rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;transition:background 0.2s,color 0.2s;box-sizing:border-box;"
          onmouseover="this.style.background='var(--green-light)'" onmouseout="this.style.background='transparent'">
          Kembali ke Rincian Pesanan
        </a>
    </form>
  </div>

</div>
</div>

{{-- Toast copy --}}
<div id="copyToast" style="display:none;position:fixed;bottom:24px;right:24px;background:#1a1a1a;color:white;padding:12px 20px;border-radius:8px;font-size:0.875rem;z-index:999;">
  Nomor rekening disalin!
</div>

@endsection

@push('scripts')
<script>
  function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
      const toast = document.getElementById('copyToast');
      toast.style.display = 'block';
      setTimeout(() => toast.style.display = 'none', 2500);
    });
  }

  function showPreview(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      document.getElementById('uploadLabel').textContent = file.name;
      document.getElementById('uploadArea').style.borderColor = 'var(--green-dark)';
      document.getElementById('uploadArea').style.background = 'var(--green-light)';

      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('previewImg').src = e.target.result;
          document.getElementById('previewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    }
  }

  function handleDrop(e) {
    e.preventDefault();
    const input = document.getElementById('proofInput');
    input.files = e.dataTransfer.files;
    showPreview(input);
    document.getElementById('uploadArea').style.borderColor = 'var(--border)';
    document.getElementById('uploadArea').style.background = 'white';
  }
</script>
@endpush
