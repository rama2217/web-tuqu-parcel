@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div style="padding:32px;">

  {{-- Header --}}
  <div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#2D4A3E;">Manajemen Pesanan</h1>
    <p style="font-size:0.83rem;color:#6B6560;margin-top:4px;">Kelola dan approve pembayaran dari customer</p>
  </div>

  {{-- Flash --}}
  @if(session('error'))
    <div style="background:#FEE2E2;border:1px solid #FCA5A5;color:#991B1B;padding:12px 16px;border-radius:8px;font-size:0.85rem;margin-bottom:20px;">{{ session('error') }}</div>
  @endif

  {{-- Stats --}}
  <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;">
    @foreach([
      ['label'=>'Semua','key'=>'all','color'=>'#6b7280','bg'=>'#f9fafb'],
      ['label'=>'Menunggu Bayar','key'=>'pending','color'=>'#92400E','bg'=>'#FEF3C7'],
      ['label'=>'Menunggu Konfirmasi','key'=>'paid','color'=>'#1E40AF','bg'=>'#DBEAFE'],
      ['label'=>'Disetujui','key'=>'approved','color'=>'#065F46','bg'=>'#D1FAE5'],
      ['label'=>'Selesai','key'=>'completed','color'=>'#065F46','bg'=>'#D1FAE5'],
    ] as $s)
    <a href="{{ route('admin.orders.index', ['status' => $s['key']==='all' ? null : $s['key']]) }}"
      style="background:white;border:1px solid #E0D9D0;border-radius:10px;padding:16px;text-align:center;text-decoration:none;transition:box-shadow 0.2s;"
      onmouseover="this.style.boxShadow='0 4px 16px rgba(45,74,62,0.1)'" onmouseout="this.style.boxShadow='none'">
      <div style="font-size:1.8rem;font-weight:700;color:{{ $s['color'] }}">{{ $stats[$s['key']] }}</div>
      <div style="font-size:0.72rem;color:#6B6560;margin-top:4px;line-height:1.3;">{{ $s['label'] }}</div>
    </a>
    @endforeach
  </div>

  {{-- Filter --}}
  <div style="background:white;border:1px solid #E0D9D0;border-radius:10px;padding:16px;margin-bottom:16px;">
    <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;">
      <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Cari no. pesanan atau nama customer..."
        style="flex:1;min-width:220px;padding:9px 14px;border:1.5px solid #E0D9D0;border-radius:6px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;"
        onfocus="this.style.borderColor='#2D4A3E'" onblur="this.style.borderColor='#E0D9D0'">
      <select name="status"
        style="padding:9px 14px;border:1.5px solid #E0D9D0;border-radius:6px;font-size:0.875rem;font-family:'DM Sans',sans-serif;outline:none;background:white;">
        <option value="">Semua Status</option>
        @foreach(['pending'=>'Menunggu Bayar','paid'=>'Menunggu Konfirmasi','approved'=>'Disetujui','cancelled'=>'Dibatalkan'] as $v=>$l)
          <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
        @endforeach
      </select>
      <button type="submit"
        style="background:#2D4A3E;color:#F7F3EE;border:none;padding:9px 20px;border-radius:6px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">
        Cari
      </button>
      <a href="{{ route('admin.orders.index') }}"
        style="padding:9px 16px;border:1.5px solid #E0D9D0;border-radius:6px;font-size:0.875rem;color:#6B6560;text-decoration:none;">
        Reset
      </a>
    </form>
  </div>

  {{-- Tabel --}}
  <div style="background:white;border:1px solid #E0D9D0;border-radius:10px;overflow:hidden;">
    <div style="overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        <thead>
          <tr style="background:#F7F3EE;border-bottom:1px solid #E0D9D0;">
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">No. Pesanan</th>
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">Customer</th>
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">Produk</th>
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">Total</th>
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">Status</th>
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">Tanggal</th>
            <th style="text-align:left;padding:12px 16px;font-size:0.72rem;font-weight:700;color:#6B6560;letter-spacing:0.08em;text-transform:uppercase;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
          <tr style="border-bottom:1px solid #F0EBE4;" onmouseover="this.style.background='#FDFCFB'" onmouseout="this.style.background='white'">
            <td style="padding:12px 16px;">
              <span style="font-family:monospace;font-size:0.78rem;font-weight:700;color:#2D4A3E;">{{ $order->order_number }}</span>
            </td>
            <td style="padding:12px 16px;">
              <div style="font-weight:600;color:#1A1A1A;">{{ $order->customer->name }}</div>
              <div style="font-size:0.75rem;color:#6B6560;">{{ $order->customer->email }}</div>
            </td>
            <td style="padding:12px 16px;">
              <div style="color:#1A1A1A;">{{ $order->items->first()->product_name ?? '-' }}</div>
              @if($order->items->count() > 1)
                <div style="font-size:0.75rem;color:#6B6560;">+{{ $order->items->count()-1 }} lainnya</div>
              @endif
            </td>
            <td style="padding:12px 16px;font-weight:700;color:#2D4A3E;">
              Rp {{ number_format($order->total, 0, ',', '.') }}
            </td>
            <td style="padding:12px 16px;">
              @php
                $colors = ['pending'=>['#FEF3C7','#92400E'],'paid'=>['#DBEAFE','#1E40AF'],'approved'=>['#D1FAE5','#065F46'],'cancelled'=>['#FEE2E2','#991B1B']];
                $c = $colors[$order->status] ?? ['#F3F4F6','#374151'];
              @endphp
              <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;">
                {{ $order->status_label }}
              </span>
            </td>
            <td style="padding:12px 16px;font-size:0.78rem;color:#6B6560;">
              {{ $order->created_at->format('d M Y') }}<br>
              {{ $order->created_at->format('H:i') }}
            </td>
            <td style="padding:12px 16px;">
              <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">

                {{-- Bukti bayar --}}
                @if($order->payment_proof)
                  <a href="{{ route('admin.orders.view-proof', $order->id) }}" target="_blank"
                    style="padding:5px 12px;font-size:0.75rem;font-weight:600;color:#1E40AF;border:1px solid #BFDBFE;border-radius:5px;text-decoration:none;background:#EFF6FF;">
                    Bukti
                  </a>
                @endif

                {{-- Approve --}}
                @if($order->status === 'paid')
                  <form method="POST" action="{{ route('admin.orders.approve', $order->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit"
                      style="padding:5px 12px;font-size:0.75rem;font-weight:700;color:white;background:#22c55e;border:none;border-radius:5px;cursor:pointer;font-family:'DM Sans',sans-serif;"
                      onclick="return confirm('Approve pesanan {{ $order->order_number }}?')">
                      ✓ Approve
                    </button>
                  </form>
                @endif

                {{-- Ubah status --}}
                <button onclick="openStatusPopup(this, '{{ $order->id }}')"
                  style="padding:5px 12px;font-size:0.75rem;font-weight:600;color:#4A4A4A;border:1px solid #E0D9D0;border-radius:5px;background:white;cursor:pointer;font-family:'DM Sans',sans-serif;">
                  Status ▾
                </button>

              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="padding:48px;text-align:center;color:#6B6560;font-size:0.875rem;">Belum ada pesanan.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($orders->hasPages())
    <div style="padding:16px;border-top:1px solid #E0D9D0;">
      {{ $orders->links() }}
    </div>
    @endif
  </div>

</div>

{{-- Popup Status (shared, satu untuk semua row) --}}
<div id="statusPopup" style="display:none;position:fixed;z-index:9999;background:white;border:1px solid #E0D9D0;border-radius:8px;box-shadow:0 8px 32px rgba(0,0,0,0.15);width:200px;overflow:hidden;">
  <div style="padding:10px 16px;background:#F7F3EE;border-bottom:1px solid #E0D9D0;font-size:0.75rem;font-weight:700;color:#6B6560;letter-spacing:0.06em;text-transform:uppercase;">
    Ubah Status
  </div>
  <div id="statusPopupItems"></div>
</div>

{{-- Data status per order untuk JS --}}
<script>
  const orderStatuses = {
    @foreach($orders as $order)
    '{{ $order->id }}': '{{ $order->status }}',
    @endforeach
  };

  const statusOptions = {
    'pending':   'Menunggu Bayar',
    'paid':      'Menunggu Konfirmasi',
    'approved':  'Disetujui',
    'cancelled': 'Dibatalkan',
  };

  const updateRoutes = {
    @foreach($orders as $order)
    '{{ $order->id }}': '{{ route('admin.orders.update-status', $order->id) }}',
    @endforeach
  };

  const csrfToken = '{{ csrf_token() }}';

  function openStatusPopup(btn, orderId) {
    const popup = document.getElementById('statusPopup');
    const items = document.getElementById('statusPopupItems');
    const currentStatus = orderStatuses[orderId];

    // Build items
    items.innerHTML = '';
    Object.entries(statusOptions).forEach(([val, label]) => {
      const isActive = val === currentStatus;
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = updateRoutes[orderId];
      form.style.margin = '0';
      form.innerHTML = `
        <input type="hidden" name="_token" value="${csrfToken}">
        <input type="hidden" name="status" value="${val}">
        <button type="submit"
          style="width:100%;text-align:left;padding:9px 16px;font-size:0.82rem;border:none;
                 background:${isActive ? '#F7F3EE' : 'white'};
                 color:${isActive ? '#2D4A3E' : '#4A4A4A'};
                 font-weight:${isActive ? '700' : '400'};
                 cursor:pointer;font-family:'DM Sans',sans-serif;display:flex;align-items:center;gap:8px;"
          onmouseover="this.style.background='#F7F3EE'"
          onmouseout="this.style.background='${isActive ? '#F7F3EE' : 'white'}'">
          ${isActive ? '<span style="color:#2D4A3E">✓</span>' : '<span style="opacity:0">✓</span>'}
          ${label}
        </button>
      `;
      items.appendChild(form);
    });

    // Hitung posisi popup agar tidak terpotong
    const rect = btn.getBoundingClientRect();
    const popupHeight = 190;
    const spaceBelow = window.innerHeight - rect.bottom;
    const spaceAbove = rect.top;

    popup.style.display = 'block';

    if (spaceBelow < popupHeight && spaceAbove > popupHeight) {
      // Muncul ke atas
      popup.style.top = (rect.top + window.scrollY - popupHeight) + 'px';
    } else {
      // Muncul ke bawah
      popup.style.top = (rect.bottom + window.scrollY + 4) + 'px';
    }

    // Posisi horizontal - jangan sampai keluar kanan layar
    let left = rect.right + window.scrollX - 200;
    if (left < 8) left = 8;
    popup.style.left = left + 'px';

    // Tutup jika klik di luar
    setTimeout(() => {
      document.addEventListener('click', closePopupOutside);
    }, 10);
  }

  function closePopupOutside(e) {
    const popup = document.getElementById('statusPopup');
    if (!popup.contains(e.target)) {
      popup.style.display = 'none';
      document.removeEventListener('click', closePopupOutside);
    }
  }
</script>

@endsection
