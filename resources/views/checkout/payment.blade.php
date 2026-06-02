@extends('layouts.public')

@section('title', 'Pembayaran — TuquParcel')

@push('styles')
<style>
  @media (max-width: 768px) {
    .container {
      padding-left: 16px !important;
      padding-right: 16px !important;
    }
    [style*="font-size:2.4rem"] {
      font-size: 1.8rem !important;
    }
    #uploadArea {
      padding: 20px 16px !important;
    }
  }

  /* Tab styles */
  .pay-tabs { display:flex; gap:0; border:1.5px solid var(--border); border-radius:8px; overflow:hidden; margin-bottom:20px; }
  .pay-tab  { flex:1; padding:13px 0; text-align:center; font-size:0.9rem; font-weight:600; cursor:pointer;
              background:white; color:var(--text-muted); border:none; font-family:'DM Sans',sans-serif;
              transition:background 0.18s,color 0.18s; }
  .pay-tab.active  { background:var(--green-dark); color:var(--ivory); }
  .pay-tab:first-child { border-radius:6px 0 0 6px; }
  .pay-tab:last-child  { border-radius:0 6px 6px 0; }

  .pay-panel { display:none; }
  .pay-panel.active { display:block; }

  /* Bank select dropdown */
  .bank-select {
    width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:8px;
    font-size:0.9rem; font-family:'DM Sans',sans-serif; color:var(--text-dark);
    background:white; outline:none; cursor:pointer; appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24' stroke='%23888' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 12px center;
    padding-right:36px; transition:border-color 0.2s; margin-bottom:16px;
  }
  .bank-select:focus { border-color:var(--green-dark); }

  /* Bank detail card */
  .bank-detail-card {
    border:1.5px solid var(--green-dark); border-radius:8px; padding:16px 20px;
    background:#f7fbf8; display:none; margin-bottom:12px;
    animation: fadeSlideIn 0.2s ease;
  }
  .bank-detail-card.visible { display:block; }
  @keyframes fadeSlideIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .bank-detail-number {
    font-family:'Cormorant Garamond',serif; font-size:1.7rem; font-weight:700;
    color:var(--text-dark); letter-spacing:0.04em; margin:4px 0 2px;
  }

  /* Bank accordion (unused, kept for compat) */
  .bank-accordion { border:1.5px solid var(--border); border-radius:8px; margin-bottom:10px; overflow:hidden; }
  .bank-accordion-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 18px; cursor:pointer; background:white;
    transition:background 0.15s;
    user-select:none;
  }
  .bank-accordion-header:hover { background:var(--ivory); }
  .bank-accordion-header.open  { background:var(--ivory); border-bottom:1.5px solid var(--border); }
  .bank-accordion-label { display:flex; align-items:center; gap:10px; }
  .bank-accordion-badge {
    font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;
    background:var(--green-dark); color:var(--ivory);
    padding:3px 9px; border-radius:20px;
  }
  .bank-accordion-name { font-size:0.9rem; font-weight:600; color:var(--text-dark); }
  .bank-accordion-chevron { transition:transform 0.22s; color:var(--text-muted); flex-shrink:0; }
  .bank-accordion-chevron.open { transform:rotate(180deg); }
  .bank-accordion-body {
    max-height:0; overflow:hidden;
    transition:max-height 0.28s ease, padding 0.2s;
    padding:0 18px;
  }
  .bank-accordion-body.open { max-height:120px; padding:16px 18px; }

  /* QRIS image */
  .qris-wrap { display:flex; flex-direction:column; align-items:center; padding:24px 0; gap:14px; }
  .qris-wrap img { width:220px; height:220px; object-fit:contain; border:1.5px solid var(--border); border-radius:10px; padding:8px; background:white; }
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

  {{-- Nominal --}}
  <div style="background:var(--green-dark);color:var(--ivory);border-radius:8px;padding:24px;margin-bottom:20px;text-align:center;">
    <p style="font-size:0.8rem;opacity:0.75;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:8px;">Total yang harus dibayar</p>
    <p style="font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
    <p style="font-size:0.8rem;opacity:0.6;margin-top:4px;">{{ $order->items->count() }} produk • {{ $order->recipient_name }}</p>
  </div>

  {{-- Pilih Metode Pembayaran --}}
  <div style="background:white;border-radius:8px;padding:24px;border:1px solid var(--border);margin-bottom:20px;">
    <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:16px;">Pilih Metode Pembayaran</h3>

    <div class="pay-tabs">
      <button class="pay-tab active" onclick="switchTab('transfer', this)" type="button">
        Transfer Bank
      </button>
      <button class="pay-tab" onclick="switchTab('qris', this)" type="button">
        QRIS
      </button>
    </div>

    {{-- Panel: Transfer Bank --}}
    <div id="panel-transfer" class="pay-panel active">
      @if(count($bankAccounts) > 0)

        {{-- Encode data bank ke JSON untuk JS --}}
        @php
          $banksJson = json_encode(array_values($bankAccounts));
        @endphp

        <label style="display:block;font-size:0.85rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;">Pilih Bank Tujuan</label>
        <select class="bank-select" id="bankSelect" onchange="showBankDetail(this)">
          <option value="">Pilih bank</option>
          @foreach(array_values($bankAccounts) as $idx => $bank)
            <option value="{{ $idx }}">{{ $bank['bank'] }} — a.n. {{ $bank['name'] }}</option>
          @endforeach
        </select>

        {{-- Detail rekening yang muncul setelah pilih --}}
        <div class="bank-detail-card" id="bankDetailCard">
          <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-muted);" id="detailBankName"></p>
          <p class="bank-detail-number" id="detailNumber"></p>
          <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:6px;">
            <p style="font-size:0.85rem;color:var(--text-muted);" id="detailHolder"></p>
            <button type="button" onclick="copyText(document.getElementById('detailNumber').textContent)"
              style="background:white;border:1px solid var(--border);border-radius:6px;padding:7px 14px;font-size:0.8rem;font-weight:500;color:var(--green-dark);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;flex-shrink:0;"
              onmouseover="this.style.background='var(--green-light)'" onmouseout="this.style.background='white'">
              Salin
            </button>
          </div>
        </div>

        <script>
          const bankData = {!! $banksJson !!};
          function showBankDetail(select) {
            const card = document.getElementById('bankDetailCard');
            const idx  = select.value;
            if (idx === '') { card.classList.remove('visible'); return; }
            const b = bankData[parseInt(idx)];
            document.getElementById('detailBankName').textContent = b.bank;
            document.getElementById('detailNumber').textContent   = b.number;
            document.getElementById('detailHolder').textContent   = 'a.n. ' + b.name;
            card.classList.add('visible');
          }
        </script>

      @else
        <p style="font-size:0.9rem;color:var(--text-muted);">Nomor rekening belum diatur. Hubungi kami via WhatsApp.</p>
      @endif

      <div style="background:var(--ivory);border-radius:6px;padding:12px 16px;margin-top:8px;">
        <p style="font-size:0.82rem;color:var(--text-muted);line-height:1.6;">
          ⚠️ Mohon transfer sesuai nominal yang tertera. Setelah transfer, upload bukti pembayaran di bawah.
        </p>
      </div>
    </div>

    {{-- Panel: QRIS --}}
    <div id="panel-qris" class="pay-panel">
      @if($qrisImage)
        <div class="qris-wrap">
          <img src="{{ asset('storage/' . $qrisImage) }}" alt="QRIS TuquParcel">
          <p style="font-size:0.85rem;color:var(--text-muted);text-align:center;max-width:280px;line-height:1.6;">
            Scan kode QRIS di atas menggunakan aplikasi mobile banking atau dompet digital (GoPay, OVO, Dana, dll).
          </p>
          <div style="background:var(--ivory);border-radius:6px;padding:12px 16px;width:100%;box-sizing:border-box;">
            <p style="font-size:0.82rem;color:var(--text-muted);line-height:1.6;">
              ⚠️ Pastikan nominal yang dibayarkan sesuai. Setelah pembayaran, upload bukti di bawah.
            </p>
          </div>
        </div>
      @else
        <div style="text-align:center;padding:32px 0;color:var(--text-muted);font-size:0.9rem;">
          <p style="margin-bottom:8px;font-size:1.5rem;">QRIS</p>
          Kode QRIS belum tersedia. Hubungi kami via WhatsApp.
        </div>
      @endif
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
      {{-- Kirim metode pembayaran yang dipilih --}}
      <input type="hidden" name="payment_method" id="selectedMethod" value="transfer">

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
  // Accordion bank
  function toggleAccordion(idx) {
    const body  = document.getElementById('body-' + idx);
    const chev  = document.getElementById('chev-' + idx);
    const hdr   = body.previousElementSibling;
    const isOpen = body.classList.contains('open');

    // Tutup semua dulu
    document.querySelectorAll('.bank-accordion-body').forEach(b => b.classList.remove('open'));
    document.querySelectorAll('.bank-accordion-chevron').forEach(c => c.classList.remove('open'));
    document.querySelectorAll('.bank-accordion-header').forEach(h => h.classList.remove('open'));

    if (!isOpen) {
      body.classList.add('open');
      chev.classList.add('open');
      hdr.classList.add('open');
    }
  }

  // Tab switching
  function switchTab(tab, btn) {
    document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + tab).classList.add('active');
    document.getElementById('selectedMethod').value = tab;
  }

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
