@extends('layouts.public')

@section('title', 'Pengaturan Akun — TuquParcel')

@push('styles')
<style>
  .account-layout {
    max-width: 1000px;
    margin: 0 auto;
    padding: 120px 24px 80px;
    display: block;
  }

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
    margin-bottom: 20px;
  }

  .account-card {
    background: #fff;
    border: 1px solid #E0D9D0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
  }
  .account-card-header {
    padding: 16px 24px;
    border-bottom: 1px solid #E0D9D0;
    background: #F7F3EE;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .account-card-header h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #2D4A3E;
  }
  .account-card-body { padding: 24px; }

  .form-group { margin-bottom: 18px; }
  .form-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #2D4A3E;
    margin-bottom: 6px;
    letter-spacing: 0.02em;
  }
  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #E0D9D0;
    border-radius: 6px;
    font-size: 0.875rem;
    font-family: 'DM Sans', sans-serif;
    color: #1A1A1A;
    background: #FDFCFB;
    outline: none;
    transition: border-color 0.2s;
  }
  .form-group input:focus,
  .form-group textarea:focus { border-color: #2D4A3E; }
  .form-group textarea { resize: vertical; min-height: 80px; }
  .form-group .hint { font-size: 0.75rem; color: #6B6560; margin-top: 4px; }

  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  .btn-save {
    background: #2D4A3E;
    color: #F7F3EE;
    border: none;
    padding: 10px 28px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.2s;
  }
  .btn-save:hover { background: #3D6B59; }

  /* Toast handled via JS */
  .field-error { font-size: 0.75rem; color: #dc2626; margin-top: 4px; }


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

  {{-- Main --}}
  <main>
    <h1 class="account-page-title">Pengaturan Akun</h1>
    <p class="account-page-sub">Kelola informasi profil dan keamanan akunmu.</p>

    @if(session('success'))
      <span id="flashSuccess" data-msg="{{ session('success') }}" style="display:none;"></span>
    @endif
    @if(session('error'))
      <span id="flashError" data-msg="{{ session('error') }}" style="display:none;"></span>
    @endif

    {{-- Form Profil --}}
    <div class="account-card">
      <div class="account-card-header">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C9A96E" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <h2>Informasi Pribadi</h2>
      </div>
      <div class="account-card-body">
        <form method="POST" action="{{ route('account.update') }}">
          @csrf
          @method('PUT')
          <div class="form-row">
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input type="text" name="name" value="{{ old('name', $customer->name) }}" required>
              @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" value="{{ old('email', $customer->email) }}" required>
              @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>No. HP <span style="font-weight:400;color:#6B6560;">(opsional)</span></label>
              <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
              <label>Alamat <span style="font-weight:400;color:#6B6560;">(opsional)</span></label>
              <input type="text" name="address" value="{{ old('address', $customer->address) }}" placeholder="Alamat lengkap">
            </div>
          </div>
          <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
      </div>
    </div>

    {{-- Form Password --}}
    <div class="account-card">
      <div class="account-card-header">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C9A96E" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <h2>Ubah Password</h2>
      </div>
      <div class="account-card-body">
        <form method="POST" action="{{ route('account.update-password') }}">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label>Password Saat Ini</label>
            <input type="password" name="current_password" placeholder="Masukkan password saat ini">
            @error('current_password')<div class="field-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Password Baru</label>
              <input type="password" name="password" placeholder="Minimal 8 karakter">
              @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
            </div>
          </div>
          <button type="submit" class="btn-save">Ubah Password</button>
        </form>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function showToast(msg, type) {
    const existing = document.getElementById('profileToast');
    if (existing) existing.remove();

    const colors = {
      success: { bg: '#2D4A3E', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>' },
      error:   { bg: '#dc2626', icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>' },
    };
    const c = colors[type] || colors.success;

    const toast = document.createElement('div');
    toast.id = 'profileToast';
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

  document.addEventListener('DOMContentLoaded', function () {
    const s = document.getElementById('flashSuccess');
    const e = document.getElementById('flashError');
    if (s) showToast(s.dataset.msg, 'success');
    if (e) showToast(e.dataset.msg, 'error');
  });
</script>
@endpush
