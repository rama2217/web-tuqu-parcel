@extends('layouts.admin')
@section('title', 'Review Pelanggan')

@push('styles')
<style>

  .review-summary { display: flex; gap: 12px; margin-bottom: 22px; flex-wrap: wrap; }
  .review-stat-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; padding: 14px 20px; flex: 1; min-width: 120px; }
  .review-stat-card .rsv { font-size: 26px; font-weight: 800; }
  .review-stat-card .rsl { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

  .reviews-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
  .review-card { background: var(--card-bg); border-radius: 14px; border: 1px solid var(--border); padding: 18px 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); transition: box-shadow 0.2s; }
  .review-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
  .review-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
  .reviewer-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--green-light), var(--green-accent)); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
  .reviewer-name { font-weight: 700; font-size: 13.5px; }
  .review-date { font-size: 11px; color: var(--text-muted); }
  .stars { color: #f6ad55; font-size: 14px; letter-spacing: 1px; margin-bottom: 8px; }
  .review-product { font-size: 11.5px; font-weight: 600; color: var(--green-accent); margin-bottom: 8px; }
  .review-text { font-size: 13px; color: #3d5446; line-height: 1.6; }
  .review-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--border); }
  .review-status-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
  .review-status-badge.published { background: #e8f5ee; color: #276749; }
  .review-status-badge.pending { background: #fff3e0; color: #c05621; }
  .btn-sm { padding: 5px 12px; border-radius: 7px; font-size: 11.5px; font-weight: 600; cursor: pointer; border: 1.5px solid var(--border); background: #fff; color: var(--text); transition: all 0.2s; }
  .btn-sm:hover { border-color: var(--green-accent); color: var(--green-accent); }

</style>
@endpush

@php use App\Models\Review; @endphp

@section('content')

      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
          <h1>Review Pelanggan</h1>
          <p>Ulasan bersih otomatis dipublikasikan. Yang mengandung kata tidak pantas masuk "Menunggu".</p>
        </div>
        @if(Review::pending()->count() > 0)
        <form method="POST" action="{{ route('admin.review.approveAll') }}" class="form-setujui-semua">
          @csrf
          <button style="padding:9px 18px;border-radius:10px;background:var(--green-dark);color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;">
            ✓ Setujui Semua ({{ Review::pending()->count() }})
          </button>
        </form>
        @endif
      </div>

      <div class="review-summary">
        <div class="review-stat-card"><div class="rsv" style="color:var(--green-accent);">{{ number_format($avgRating, 1) }}</div><div class="rsl"><svg width="11" height="11" viewBox="0 0 24 24" fill="#f6ad55" stroke="#f6ad55" stroke-width="1" style="display:inline;vertical-align:middle;margin-right:3px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Rating Rata-rata</div></div>
        <div class="review-stat-card"><div class="rsv">{{ $totalAll }}</div><div class="rsl">Total Review</div></div>
        <div class="review-stat-card"><div class="rsv" style="color:#c05621;">{{ $totalPending }}</div><div class="rsl">Menunggu Persetujuan</div></div>
        <div class="review-stat-card"><div class="rsv" style="color:#276749;">{{ $totalApproved }}</div><div class="rsl">Dipublikasikan</div></div>
      </div>

      {{-- Filter tabs --}}
      <div style="display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap;align-items:center;justify-content:space-between;">
        <div style="display:flex;gap:6px;">
          <a href="{{ route('admin.review.index') }}"
             style="padding:7px 16px;border-radius:8px;font-size:13px;font-weight:600;border:1.5px solid {{ !request('filter') ? 'var(--green-dark)' : 'var(--border)' }};background:{{ !request('filter') ? 'var(--green-dark)' : 'var(--card-bg)' }};color:{{ !request('filter') ? '#fff' : 'var(--text)' }};text-decoration:none;">
            Semua
          </a>
          <a href="{{ route('admin.review.index', ['filter'=>'pending']) }}"
             style="padding:7px 16px;border-radius:8px;font-size:13px;font-weight:600;border:1.5px solid {{ request('filter')=='pending' ? '#c05621' : 'var(--border)' }};background:{{ request('filter')=='pending' ? '#fff3e0' : 'var(--card-bg)' }};color:{{ request('filter')=='pending' ? '#c05621' : 'var(--text)' }};text-decoration:none;">
            Menunggu
          </a>
          <a href="{{ route('admin.review.index', ['filter'=>'approved']) }}"
             style="padding:7px 16px;border-radius:8px;font-size:13px;font-weight:600;border:1.5px solid {{ request('filter')=='approved' ? 'var(--green-accent)' : 'var(--border)' }};background:{{ request('filter')=='approved' ? '#e8f5ee' : 'var(--card-bg)' }};color:{{ request('filter')=='approved' ? '#276749' : 'var(--text)' }};text-decoration:none;">
            Disetujui
          </a>
        </div>
        <span style="font-size:12.5px;color:var(--text-muted);">{{ $reviews->total() }} ulasan ditemukan</span>
      </div>

      <div class="reviews-grid">
        @forelse($reviews as $review)
        @php
          $initials = collect(explode(' ', $review->reviewer_name))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
          $colors = ['linear-gradient(135deg,var(--green-light),var(--green-accent))','linear-gradient(135deg,#f6ad55,#ed8936)','linear-gradient(135deg,#9f7aea,#6b46c1)','linear-gradient(135deg,#fc8181,#e53e3e)','linear-gradient(135deg,#68d391,#276749)','linear-gradient(135deg,#4fd1c5,#2c7a7b)'];
          $color = $colors[crc32($review->reviewer_name) % count($colors)];
          $stars = str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating);
        @endphp
        <div class="review-card">
          <div class="review-header">
            <div class="reviewer-avatar" style="background:{{ $color }}">{{ $initials }}</div>
            <div>
              <div class="reviewer-name">{{ $review->reviewer_name }}</div>
              <div class="review-date">{{ $review->created_at->translatedFormat('d M Y') }}</div>
            </div>
          </div>
          <div class="stars">{{ $stars }}</div>
          <div class="review-product"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M21 10V7a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 7v10a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0022 17v-3"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg> {{ $review->product->name ?? '-' }}</div>
          <div class="review-text">{{ $review->comment }}</div>
          {{-- Indikator tampil di landing --}}
          @if($review->is_approved)
          <div style="margin-top:10px;padding:8px 12px;border-radius:8px;background:{{ $review->show_on_landing ? '#e8f5ee' : '#f5f5f5' }};display:flex;align-items:center;justify-content:space-between;gap:8px;">
            <span style="font-size:11.5px;font-weight:600;color:{{ $review->show_on_landing ? '#276749' : '#888' }};">
              {!! $review->show_on_landing
                ? '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;vertical-align:middle;margin-right:4px;"><path d=\"M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z\"/><polyline points=\"9 22 9 12 15 12 15 22\"/></svg> Tampil di landing page'
                : '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;vertical-align:middle;margin-right:4px;"><path d=\"M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24\"/><line x1=\"1\" y1=\"1\" x2=\"23\" y2=\"23\"/></svg> Tidak tampil di landing'
              !!}
            </span>
            <form method="POST" action="{{ route('admin.review.toggleLanding', $review) }}" style="margin:0;">
              @csrf
              <button class="btn-sm" style="{{ $review->show_on_landing ? 'background:#276749;color:#fff;border-color:#276749;' : 'background:var(--green-dark);color:#fff;border-color:var(--green-dark);' }}">
                {{ $review->show_on_landing ? 'Sembunyikan' : 'Tampilkan' }}
              </button>
            </form>
          </div>
          @endif
          <div class="review-footer">
            @if($review->is_approved)
              <span class="review-status-badge published">Disetujui</span>
              <div style="display:flex;gap:6px;">
                <form method="POST" action="{{ route('admin.review.destroy', $review) }}"
                      style="margin:0;" class="form-hapus">
                  @csrf @method('DELETE')
                  <button class="btn-sm" style="color:var(--red);border-color:var(--red);">Hapus</button>
                </form>
              </div>
            @else
              <span class="review-status-badge pending">Menunggu</span>
              <div style="display:flex;gap:6px;">
                <form method="POST" action="{{ route('admin.review.approve', $review) }}" style="margin:0;">
                  @csrf
                  <button class="btn-sm" style="background:var(--green-dark);color:#fff;border-color:var(--green-dark);">✓ Setujui</button>
                </form>
                <form method="POST" action="{{ route('admin.review.destroy', $review) }}"
                      style="margin:0;" class="form-hapus">
                  @csrf @method('DELETE')
                  <button class="btn-sm" style="color:var(--red);border-color:var(--red);">Hapus</button>
                </form>
              </div>
            @endif
          </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--text-muted);font-size:14px;">
          Tidak ada review ditemukan.
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($reviews->hasPages())
      <div style="margin-top:20px;">
        {{ $reviews->appends(request()->query())->links() }}
      </div>
      @endif


{{-- Modal Konfirmasi Hapus --}}
<div id="confirmModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;padding:32px 28px;max-width:360px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
    <div id="confirmIcon" style="width:52px;height:52px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
    </div>
    <h3 id="confirmTitle" style="font-size:16px;font-weight:700;margin-bottom:8px;color:#111;">Hapus Review?</h3>
    <p id="confirmMessage" style="font-size:13.5px;color:#666;margin-bottom:24px;line-height:1.5;">Tindakan ini tidak dapat dibatalkan.</p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <button onclick="closeConfirm()" style="padding:9px 22px;border-radius:10px;border:1.5px solid #ddd;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:#555;">Batal</button>
      <button id="confirmOkBtn" style="padding:9px 22px;border-radius:10px;border:none;background:#dc2626;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Hapus</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  var _confirmForm = null;

  function showConfirm(form, title, message, btnLabel, btnColor) {
    _confirmForm = form;
    document.getElementById('confirmTitle').textContent = title || 'Konfirmasi';
    document.getElementById('confirmMessage').textContent = message || 'Yakin melanjutkan?';
    var btn = document.getElementById('confirmOkBtn');
    btn.textContent = btnLabel || 'OK';
    btn.style.background = btnColor || '#dc2626';
    document.getElementById('confirmModal').style.display = 'flex';
  }

  function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
    _confirmForm = null;
  }

  document.getElementById('confirmOkBtn').addEventListener('click', function() {
    if (_confirmForm) {
      var form = _confirmForm;
      closeConfirm();
      form.submit();
    }
  });

  document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
  });

  // Attach ke semua form hapus
  document.querySelectorAll('.form-hapus').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      // Reset icon ke merah
      document.getElementById('confirmIcon').style.background = '#fee2e2';
      document.getElementById('confirmIcon').innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>';
      document.getElementById('confirmOkBtn').textContent = 'Hapus';
      document.getElementById('confirmOkBtn').style.background = '#dc2626';
      _confirmForm = form;
      document.getElementById('confirmTitle').textContent = 'Hapus Review?';
      document.getElementById('confirmMessage').textContent = 'Review ini akan dihapus permanen dan tidak bisa dikembalikan.';
      document.getElementById('confirmModal').style.display = 'flex';
    });
  });

  // Attach ke form setujui semua
  var formSetujui = document.querySelector('.form-setujui-semua');
  if (formSetujui) {
    formSetujui.addEventListener('submit', function(e) {
      e.preventDefault();
      document.getElementById('confirmIcon').style.background = '#e8f5ee';
      document.getElementById('confirmIcon').innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#276749" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
      document.getElementById('confirmOkBtn').style.background = '#276749';
      showConfirm(formSetujui, 'Setujui Semua?', 'Semua review yang menunggu akan langsung dipublikasikan.', 'Setujui', '#276749');
    });
  }
</script>
@endpush

@endsection
