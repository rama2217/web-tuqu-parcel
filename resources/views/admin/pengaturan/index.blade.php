@extends('layouts.admin')
@section('title', 'Pengaturan')

@push('styles')
<style>
.page-title { font-size: 28px; font-weight: 800; color: var(--text); margin-bottom: 6px; }
  .page-sub { font-size: 13.5px; color: var(--text-muted); margin-bottom: 28px; max-width: 520px; line-height: 1.6; }

  /* Tabs */
  .settings-tabs { display: flex; gap: 2px; margin-bottom: 24px; border-bottom: 2px solid var(--border); }
  .stab {
    padding: 9px 16px; border: none; background: none; font-family: inherit;
    font-size: 13px; font-weight: 600; color: var(--text-muted);
    cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px;
    transition: all 0.2s; display: flex; align-items: center; gap: 7px;
    border-radius: 6px 6px 0 0; white-space: nowrap;
  }
  .stab svg { width: 14px; height: 14px; }
  .stab:hover { color: var(--text); background: rgba(0,0,0,0.03); }
  .stab.active { color: var(--green-dark); border-bottom-color: var(--green-dark); }

  .tab-panel { display: none; }
  .tab-panel.active { display: block; }

  /* Cards */
  .settings-card {
    background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border);
    box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 28px 32px; margin-bottom: 20px;
  }
  .settings-card:last-child { margin-bottom: 0; }

  .section-heading {
    display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 700;
    color: var(--text); margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border);
  }
  .section-heading svg { width: 17px; height: 17px; color: var(--green-accent); }

  /* Form */
  .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .form-group { margin-bottom: 20px; }
  .form-group:last-child { margin-bottom: 0; }
  .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
  .form-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 6px; }

  .form-input {
    width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px;
    font-size: 13.5px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; transition: border-color 0.2s;
  }
  .form-input:focus { border-color: var(--green-accent); }
  .form-input::placeholder { color: #b0bdb5; }
  textarea.form-input { resize: vertical; min-height: 100px; line-height: 1.6; }

  .input-icon-wrap { position: relative; }
  .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted); pointer-events: none; }
  .input-icon-wrap .form-input { padding-left: 36px; }

  /* Logo upload */
  .logo-upload-row { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap; }
  .logo-preview {
    width: 80px; height: 80px; border-radius: 14px; border: 2px dashed var(--border);
    background: #f8faf8; display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0; cursor: pointer; transition: all 0.2s; position: relative;
  }
  .logo-preview:hover { border-color: var(--green-accent); background: #f0faf4; }
  .logo-preview svg { width: 28px; height: 28px; color: #c8d4c8; }
  .logo-preview img { width: 100%; height: 100%; object-fit: cover; display: none; }
  .logo-remove-btn {
    position: absolute; top: -6px; right: -6px; width: 18px; height: 18px;
    border-radius: 50%; background: var(--red); color: #fff; border: none;
    font-size: 11px; cursor: pointer; display: none; align-items: center; justify-content: center;
  }
  .btn-upload-logo {
    display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--card-bg); font-size: 13px; font-weight: 600;
    color: var(--text); cursor: pointer; transition: all 0.2s; margin-bottom: 8px; font-family: inherit;
  }
  .btn-upload-logo svg { width: 15px; height: 15px; }
  .btn-upload-logo:hover { border-color: var(--green-accent); color: var(--green-accent); background: #f0faf4; }
  .upload-hint { font-size: 12px; color: var(--text-muted); }

  /* Color */
  .color-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .color-swatch { width: 36px; height: 36px; border-radius: 8px; border: 2px solid var(--border); cursor: pointer; overflow: hidden; flex-shrink: 0; position: relative; }
  .color-swatch input[type=color] { position: absolute; inset: -4px; width: calc(100% + 8px); height: calc(100% + 8px); border: none; cursor: pointer; opacity: 0; }
  .color-hex { font-family: monospace; font-size: 13px; max-width: 120px !important; }
  .color-note { font-size: 12px; color: var(--text-muted); }

  /* Toggle */
  .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 15px 0; }
  .toggle-row + .toggle-row { border-top: 1px solid var(--border); }
  .toggle-info .toggle-label { font-size: 13.5px; font-weight: 600; color: var(--text); }
  .toggle-info .toggle-sub { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  .switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
  .switch input { opacity: 0; width: 0; height: 0; }
  .switch-track { position: absolute; inset: 0; border-radius: 12px; background: #d0d8d0; cursor: pointer; transition: background 0.25s; }
  .switch-track::after { content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%; background: #fff; top: 3px; left: 3px; transition: transform 0.25s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
  .switch input:checked + .switch-track { background: var(--green-light); }
  .switch input:checked + .switch-track::after { transform: translateX(20px); }

  /* Profile */
  .profile-row { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap; }
  .profile-avatar-wrap { position: relative; flex-shrink: 0; }
  .profile-avatar { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--green-light), var(--green-accent)); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: #fff; }
  .avatar-edit-btn { position: absolute; bottom: 0; right: 0; width: 24px; height: 24px; border-radius: 50%; background: var(--green-dark); border: 2px solid #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; }
  .avatar-edit-btn:hover { background: var(--green-accent); }
  .avatar-edit-btn svg { width: 11px; height: 11px; color: #fff; }
  .profile-meta .profile-name { font-size: 16px; font-weight: 700; }
  .profile-meta .profile-role { font-size: 12.5px; color: var(--text-muted); margin-top: 3px; }

  /* Danger zone */
  .danger-card { background: #fff8f8; border: 1.5px solid #fecaca; border-radius: 16px; padding: 24px 32px; }
  .danger-heading { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: var(--red); margin-bottom: 16px; }
  .danger-heading svg { width: 16px; height: 16px; }
  .danger-item { display: flex; align-items: center; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid #fecaca; flex-wrap: wrap; gap: 12px; }
  .danger-item:last-child { border-bottom: none; padding-bottom: 0; }
  .danger-label { font-size: 13.5px; font-weight: 600; color: var(--text); }
  .danger-sub { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
  .btn-danger { padding: 8px 18px; border-radius: 9px; border: 1.5px solid #fca5a5; background: #fff; font-size: 12.5px; font-weight: 700; color: var(--red); cursor: pointer; transition: all 0.2s; font-family: inherit; white-space: nowrap; }
  .btn-danger:hover { background: #ffeaea; border-color: var(--red); }

  /* Bottom bar */
  .bottom-bar { position: fixed; bottom: 0; left: var(--sidebar-w); right: 0; background: var(--card-bg); border-top: 1px solid var(--border); padding: 14px 36px; display: flex; align-items: center; justify-content: flex-end; gap: 10px; z-index: 50; }
  .btn-cancel-bare { padding: 10px 22px; border-radius: 10px; border: none; background: none; font-size: 13.5px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: color 0.2s; font-family: inherit; }
  .btn-cancel-bare:hover { color: var(--text); }
  .btn-save { display: flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: 10px; border: none; background: var(--green-dark); font-size: 13.5px; font-weight: 700; color: #fff; cursor: pointer; transition: background 0.2s; font-family: inherit; }
  .btn-save svg { width: 15px; height: 15px; }
  .btn-save:hover { background: var(--green-accent); }

  /* Toast */
  .toast { position: fixed; bottom: 80px; right: 32px; background: var(--green-dark); color: #fff; padding: 12px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); opacity: 0; transform: translateY(10px); transition: all 0.3s; pointer-events: none; z-index: 200; }
  .toast.show { opacity: 1; transform: translateY(0); }
  .toast svg { width: 15px; height: 15px; }

  .content { padding-bottom: 80px !important; }

  /* Occasion grid */
  .occasion-admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }
  .occasion-admin-item {
    background: var(--bg, #f8faf8);
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 18px;
    transition: border-color 0.2s;
  }
  .occasion-admin-item:hover { border-color: var(--green-accent); }
  .oc-circle-preview {
    width: 64px; height: 64px; border-radius: 50%;
    border: 2px dashed var(--border); background: #fff;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; cursor: pointer; flex-shrink: 0; position: relative;
    transition: border-color 0.2s;
  }
  .oc-circle-preview:hover { border-color: var(--green-accent); }
  .oc-circle-preview img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
  .oc-rm-btn {
    display: none; position: absolute; top: -4px; right: -4px;
    width: 20px; height: 20px; border-radius: 50%; background: #e74c3c;
    color: #fff; border: 2px solid #fff; font-size: 12px; cursor: pointer;
    align-items: center; justify-content: center; line-height: 1;
  }
  .oc-upload-btn {
    display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px;
    border-radius: 8px; border: 1.5px solid var(--border); background: var(--card-bg);
    font-size: 12px; font-weight: 600; color: var(--text); cursor: pointer;
    font-family: inherit; transition: all 0.2s;
  }
  .oc-upload-btn:hover { border-color: var(--green-accent); color: var(--green-accent); }
  .oc-sm-input {
    width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;
    font-size: 13px; font-family: inherit; color: var(--text);
    background: var(--card-bg); outline: none; transition: border-color 0.2s;
  }
  .oc-sm-input:focus { border-color: var(--green-accent); }
  .oc-sm-input::placeholder { color: #b0bdb5; }

  /* Responsive */
  @media (max-width: 900px) {
    .form-grid-2 { grid-template-columns: 1fr; }
    .occasion-admin-grid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 768px) {
    .page-title { font-size: 22px; }
    .settings-card, .danger-card { padding: 20px 18px; }
    .bottom-bar { left: 0; padding: 12px 16px; }
    .settings-tabs { overflow-x: auto; padding-bottom: 0; }
  }
  @media (max-width: 480px) {
    .logo-upload-row { flex-direction: column; align-items: flex-start; }
    .profile-row { flex-direction: column; align-items: flex-start; }
    .occasion-admin-grid { grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')

      <h1 class="page-title">Pengaturan</h1>
      <p class="page-sub">Kelola branding toko, media sosial, dan informasi kontak yang tampil ke pelanggan.</p>

      @if($errors->any())
        <div style="background:#fff0f0;border:1px solid #f5c6c0;border-radius:10px;padding:12px 18px;margin-bottom:18px;font-size:13.5px;color:#c0392b;">
          @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
      @endif

      <div class="settings-tabs">
        <button class="stab active" onclick="switchTab('branding', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><circle cx="8" cy="15" r="1.5"/></svg>Branding
        </button>
        <button class="stab" onclick="switchTab('akun', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Akun
        </button>
        <button class="stab" onclick="switchTab('notifikasi', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>Notifikasi
        </button>
        <button class="stab" onclick="switchTab('keamanan', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Keamanan
        </button>
      </div>

      <!-- BRANDING -->
      <div class="tab-panel active" id="tab-branding">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" id="form-branding" enctype="multipart/form-data">
          @csrf @method('PUT')

        {{-- ── BRANDING CARD ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>Branding
          </div>

          <!-- Logo Upload -->
          <div class="form-group">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
              {{-- Logo Footer --}}
              <div>
                <label class="form-label" style="margin-bottom:10px;">
                  Logo Footer
                  <span style="font-weight:400;color:var(--text-muted);font-size:11.5px;margin-left:6px;">Tampil di footer website</span>
                </label>
                <div class="logo-upload-row" style="margin-bottom:0;">
                  <div class="logo-preview" id="logo-preview" onclick="document.getElementById('logo-file').click()">
                    @if(!empty($settings['site_logo']))
                      <img id="logo-img" src="{{ asset('storage/' . $settings['site_logo']) }}" alt="logo" style="display:block;">
                      <button class="logo-remove-btn" id="logo-remove" onclick="removeLogo(event)" style="display:flex;">×</button>
                    @else
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                      <img id="logo-img" src="" alt="logo">
                      <button class="logo-remove-btn" id="logo-remove" onclick="removeLogo(event)">×</button>
                    @endif
                  </div>
                  <div>
                    <button type="button" class="btn-upload-logo" onclick="document.getElementById('logo-file').click()">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                      Upload
                    </button>
                    <div class="upload-hint">512×512px. JPG/PNG.</div>
                  </div>
                  <input type="file" id="logo-file" name="site_logo" accept="image/*" style="display:none" onchange="previewLogo(this)">
                </div>
              </div>

              {{-- Logo Navbar --}}
              <div>
                <label class="form-label" style="margin-bottom:10px;">
                  Logo Navbar
                  <span style="font-weight:400;color:var(--text-muted);font-size:11.5px;margin-left:6px;">Tampil di menu atas — jika kosong, pakai Logo Footer</span>
                </label>
                <div class="logo-upload-row" style="margin-bottom:0;">
                  <div class="logo-preview" id="logo-navbar-preview" onclick="document.getElementById('logo-navbar-file').click()">
                    @if(!empty($settings['site_logo_navbar']))
                      <img id="logo-navbar-img" src="{{ asset('storage/' . $settings['site_logo_navbar']) }}" alt="logo navbar" style="display:block;">
                      <button class="logo-remove-btn" id="logo-navbar-remove" onclick="removeLogoNavbar(event)" style="display:flex;">×</button>
                    @else
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                      <img id="logo-navbar-img" src="" alt="logo navbar">
                      <button class="logo-remove-btn" id="logo-navbar-remove" onclick="removeLogoNavbar(event)">×</button>
                    @endif
                  </div>
                  <div>
                    <button type="button" class="btn-upload-logo" onclick="document.getElementById('logo-navbar-file').click()">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                      Upload
                    </button>
                    <div class="upload-hint">Disarankan transparan (PNG). Tinggi maks 40px.</div>
                  </div>
                  <input type="file" id="logo-navbar-file" name="site_logo_navbar" accept="image/*" style="display:none" onchange="previewLogoNavbar(this)">
                  <input type="hidden" name="remove_logo_navbar" id="remove_logo_navbar" value="">
                </div>
              </div>
            </div>
          </div>


          {{-- Foto Banner / Hero --}}
          <div class="form-group" style="margin-top:24px;">
            <label class="form-label" style="margin-bottom:6px;">
              Foto Banner (Hero)
              <span style="font-weight:400;color:var(--text-muted);font-size:11.5px;margin-left:6px;">Background di semua halaman — Beranda, Katalog, Tentang Kami</span>
            </label>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
              <div id="hero-preview" onclick="document.getElementById('hero-file').click()" style="width:220px;height:110px;border-radius:8px;border:2px dashed var(--border);overflow:hidden;cursor:pointer;position:relative;background:#f5f3f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                @if(!empty($settings['hero_photo']))
                  <img id="hero-img" src="{{ asset('storage/' . $settings['hero_photo']) }}" alt="banner" style="width:100%;height:100%;object-fit:cover;display:block;">
                  <button type="button" id="hero-remove-btn" onclick="removeHeroBanner(event)" style="position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);color:#fff;border:none;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;z-index:2;">×</button>
                @else
                  <div id="hero-placeholder" style="text-align:center;color:#bbb;pointer-events:none;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><div style="font-size:11px;margin-top:4px;">Klik untuk upload</div></div>
                  <img id="hero-img" src="" alt="banner" style="width:100%;height:100%;object-fit:cover;display:none;">
                  <button type="button" id="hero-remove-btn" onclick="removeHeroBanner(event)" style="position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);color:#fff;border:none;cursor:pointer;font-size:16px;align-items:center;justify-content:center;z-index:2;display:none;">×</button>
                @endif
              </div>
              <div>
                <div class="upload-hint">JPG/PNG/WEBP, maks 5MB. Disarankan landscape (1920×600px).</div>
                <div class="upload-hint" style="margin-top:4px;">Jika kosong, pakai foto bawaan (<code>HeroLand.jpg</code>).</div>
              </div>
            </div>
            <input type="file" id="hero-file" name="hero_photo" accept="image/*" style="display:none" onchange="previewHeroBanner(this)">
            <input type="hidden" name="remove_hero_photo" id="remove_hero_photo" value="">
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Nama Toko</label>
              <input type="text" class="form-input" name="site_name"
                value="{{ $settings['site_name'] ?? 'TuquParcel' }}" placeholder="Nama toko Anda">
            </div>
            <div class="form-group">
              <label class="form-label">Tagline</label>
              <input type="text" class="form-input" name="site_tagline"
                value="{{ $settings['site_tagline'] ?? '' }}" placeholder="cth. Premium Floral Arrangements">
              <div class="form-hint">Ditampilkan di bawah nama toko pada halaman utama.</div>
            </div>
          </div>
        </div>

        {{-- ── SOSIAL & KONTAK ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>Sosial & Kontak
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Username Instagram</label>
              <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                <input type="text" class="form-input" name="instagram"
                  value="{{ $settings['instagram'] ?? 'tuquparcel' }}" placeholder="username">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Email Kontak</label>
              <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input type="text" class="form-input" name="email_contact"
                  value="{{ $settings['email_contact'] ?? '' }}" placeholder="hello@tuquparcel.com">
              </div>
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Nomor WhatsApp</label>
              <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.56 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <input type="text" class="form-input" name="whatsapp"
                  value="{{ $settings['whatsapp'] ?? '' }}" placeholder="+62 8xx xxxx xxxx">
              </div>
              <div class="form-hint">Sertakan kode negara (cth. +62)</div>
            </div>
            <div class="form-group">
              <label class="form-label">URL Instagram</label>
              <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/></svg>
                <input type="text" class="form-input" name="instagram_url"
                  value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/tuquparcel">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">URL TikTok</label>
              <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>
                <input type="text" class="form-input" name="tiktok_url"
                  value="{{ $settings['tiktok_url'] ?? '' }}" placeholder="https://tiktok.com/@tuquparcel">
              </div>
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">📍 Alamat Toko 1</label>
              <textarea class="form-input" name="address" style="min-height:90px;"
                placeholder="Alamat lengkap toko cabang pertama">{{ $settings['address'] ?? '' }}</textarea>
              <div class="form-hint">Ditampilkan di footer dan halaman Tentang Kami (peta kiri).</div>
            </div>
            <div class="form-group">
              <label class="form-label">📍 Alamat Toko 2 <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
              <textarea class="form-input" name="address_2" style="min-height:90px;"
                placeholder="Alamat lengkap toko cabang kedua (kosongkan jika tidak ada)">{{ $settings['address_2'] ?? '' }}</textarea>
              <div class="form-hint">Ditampilkan di footer dan halaman Tentang Kami (peta kanan) jika diisi.</div>
            </div>
          </div>
        </div>

        {{-- ── SECTION KENAPA MEMILIH KAMI ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="3"/><path d="M12 2a3 3 0 013 3c0 1.5-3 4-3 4S9 6.5 9 5a3 3 0 013-3z"/><path d="M12 22a3 3 0 01-3-3c0-1.5 3-4 3-4s3 2.5 3 4a3 3 0 01-3 3z"/><path d="M2 12a3 3 0 013-3c1.5 0 4 3 4 3S6.5 15 5 15a3 3 0 01-3-3z"/><path d="M22 12a3 3 0 01-3 3c-1.5 0-4-3-4-3s2.5-3 4-3a3 3 0 013 3z"/></svg>
            Kenapa Memilih Kami
          </div>
          <p style="font-size:0.82rem;color:var(--text-muted);margin:-4px 0 20px;">Judul dan isi 3 kartu keunggulan yang tampil di halaman beranda.</p>

          {{-- Judul Section --}}
          <div class="form-group" style="margin-bottom:24px;">
            <label class="form-label">Judul</label>
            <input type="text" name="why_title" class="form-input"
              value="{{ $settings['why_title'] ?? '' }}"
              placeholder="Kenapa Memilih TuquParcel?">
          </div>

          {{-- 3 Kartu --}}
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
            @foreach([
              ['num'=>1,'def_title'=>'Bunga Segar Premium','def_desc'=>'Kami hanya menggunakan bunga kualitas grade A yang dipetik di pagi hari untuk memastikan kesegaran maksimal saat tiba di tangan Anda.'],
              ['num'=>2,'def_title'=>'Desain Eksklusif',   'def_desc'=>'Setiap rangkaian dirancang oleh florist profesional kami yang berpengalaman, unik untuk setiap pesanan, dan penuh keeleganan.'],
              ['num'=>3,'def_title'=>'Pengiriman Aman',    'def_desc'=>'Sarana pengiriman tepat waktu dengan packaging khusus yang terlindungi membawa parsel, memastikan hadiah sampai dalam kondisi sempurna.'],
            ] as $card)
            <div style="border:1.5px solid var(--border);border-radius:12px;padding:18px;display:flex;flex-direction:column;gap:12px;">
              <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--green-accent);">Kartu {{ $card['num'] }}</div>
              <div>
                <label class="form-label" style="font-size:0.75rem;">Judul</label>
                <input type="text" name="why_card{{ $card['num'] }}_title" class="form-input"
                  value="{{ $settings['why_card'.$card['num'].'_title'] ?? '' }}"
                  placeholder="{{ $card['def_title'] }}">
              </div>
              <div>
                <label class="form-label" style="font-size:0.75rem;">Deskripsi</label>
                <textarea name="why_card{{ $card['num'] }}_desc" class="form-input" rows="4"
                  style="resize:vertical;" placeholder="{{ $card['def_desc'] }}">{{ $settings['why_card'.$card['num'].'_desc'] ?? '' }}</textarea>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- ── FOTO KEUNGGULAN KAMI ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            Foto Keunggulan Kami — Halaman Tentang Kami
          </div>
          <p style="font-size:0.82rem;color:var(--text-muted);margin:-4px 0 20px;">Foto-foto yang tampil bergantian (slideshow) di section Keunggulan Kami. Tambahkan hingga 3 foto.</p>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
            @foreach([1,2,3] as $n)
            <div style="border:1.5px solid var(--border);border-radius:12px;padding:16px;">
              <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--green-accent);margin-bottom:12px;">Foto {{ $n }}</div>
              <div id="keunggulan-preview-{{ $n }}"
                onclick="document.getElementById('keunggulan-file-{{ $n }}').click()"
                style="width:100%;aspect-ratio:4/5;border-radius:8px;overflow:hidden;border:2px dashed var(--border);background:var(--ivory);cursor:pointer;display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:10px;">
                @if(!empty($settings["keunggulan_photo_{$n}"]))
                  <img id="keunggulan-img-{{ $n }}" src="{{ asset('storage/' . $settings["keunggulan_photo_{$n}"]) }}"
                    style="width:100%;height:100%;object-fit:cover;object-position:center;display:block;">
                  <button type="button" onclick="removeKeunggulanPhoto(event,{{ $n }})"
                    style="position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);color:#fff;border:none;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;z-index:2;">×</button>
                @else
                  <div id="keunggulan-placeholder-{{ $n }}" style="text-align:center;color:#bbb;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    <div style="font-size:11px;margin-top:6px;">Klik untuk upload</div>
                  </div>
                  <img id="keunggulan-img-{{ $n }}" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
                @endif
              </div>
              <input type="file" id="keunggulan-file-{{ $n }}" name="keunggulan_photo_{{ $n }}" accept="image/*" style="display:none" onchange="previewKeunggulanPhoto(this,{{ $n }})">
              <input type="hidden" name="remove_keunggulan_photo_{{ $n }}" id="remove-keunggulan-{{ $n }}" value="">
              <div style="font-size:11px;color:var(--text-muted);">Format portrait disarankan. Maks 5MB.</div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- ── FOTO TIM ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Foto Tim — Halaman Tentang Kami
          </div>
          <p style="font-size:0.82rem;color:var(--text-muted);margin:-4px 0 20px;">Dua foto yang tampil berdampingan di halaman Tentang Kami.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            @foreach([1,2] as $n)
            <div style="border:1.5px solid var(--border);border-radius:12px;padding:16px;">
              <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--green-accent);margin-bottom:12px;">Foto {{ $n }}</div>

              <div id="team-preview-{{ $n }}"
                onclick="document.getElementById('team-file-{{ $n }}').click()"
                style="width:100%;aspect-ratio:3/4;border-radius:8px;overflow:hidden;border:2px dashed var(--border);background:var(--ivory);cursor:pointer;display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:12px;">
                @if(!empty($settings["team_photo_{$n}"]))
                  <img id="team-img-{{ $n }}" src="{{ asset('storage/' . $settings["team_photo_{$n}"]) }}"
                    style="width:100%;height:100%;object-fit:cover;display:block;">
                  <button type="button" onclick="removeTeamPhoto(event,{{ $n }})"
                    style="position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);color:#fff;border:none;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;z-index:2;">×</button>
                @else
                  <div id="team-placeholder-{{ $n }}" style="text-align:center;color:#bbb;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <div style="font-size:11px;margin-top:6px;">Klik untuk upload</div>
                  </div>
                  <img id="team-img-{{ $n }}" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
                @endif
              </div>

              <input type="file" id="team-file-{{ $n }}" name="team_photo_{{ $n }}" accept="image/*" style="display:none" onchange="previewTeamPhoto(this,{{ $n }})">
              <input type="hidden" name="remove_team_photo_{{ $n }}" id="remove-team-{{ $n }}" value="">
              <div style="font-size:11px;color:var(--text-muted);">Format portrait disarankan. Maks 5MB.</div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- ── SHOP BY OCCASION ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
           Produk Unggulan Kami
            <span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:4px;">— foto bulat di landing page</span>
          </div>

          <div class="occasion-admin-grid">
            @php
              $occasionDefaults = [
                1 => ['label' => 'Ulang Tahun',  'placeholder' => '/katalog?occasion=ulang-tahun'],
                2 => ['label' => 'Romance',       'placeholder' => '/katalog?occasion=romance'],
                3 => ['label' => 'Wisuda',        'placeholder' => '/katalog?occasion=wisuda'],
                4 => ['label' => 'Grand Opening', 'placeholder' => '/katalog?occasion=grand-opening'],
                5 => ['label' => 'Get Well Soon', 'placeholder' => '/katalog?occasion=get-well-soon'],
                6 => ['label' => 'Custom',        'placeholder' => '/katalog'],
                7 => ['label' => 'Pernikahan',    'placeholder' => '/katalog?occasion=pernikahan'],
                8 => ['label' => 'Baby Shower',   'placeholder' => '/katalog?occasion=baby-shower'],
                9 => ['label' => 'Anniversary',   'placeholder' => '/katalog?occasion=anniversary'],
                10 => ['label' => 'Lebaran',      'placeholder' => '/katalog?occasion=lebaran'],
                11 => ['label' => 'Natal',        'placeholder' => '/katalog?occasion=natal'],
                12 => ['label' => 'Lainnya',      'placeholder' => '/katalog'],
              ];
            @endphp

            @for($oc = 1; $oc <= 12; $oc++)
            @php
              $ocLabel   = $settings["occasion_{$oc}_label"] ?? $occasionDefaults[$oc]['label'];
              $ocLink    = $settings["occasion_{$oc}_link"]  ?? '';
              $ocPhoto   = $settings["occasion_{$oc}_photo"] ?? '';
              $ocPhotoUrl = $ocPhoto ? asset('storage/' . $ocPhoto) : '';
            @endphp
            <div class="occasion-admin-item">
              {{-- Preview lingkaran + tombol upload --}}
              <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                <div class="oc-circle-preview" id="oc-preview-{{ $oc }}"
                     onclick="document.getElementById('oc-file-{{ $oc }}').click()">
                  @if($ocPhotoUrl)
                    <img id="oc-img-{{ $oc }}" src="{{ $ocPhotoUrl }}" alt="{{ $ocLabel }}">
                    <button type="button" class="oc-rm-btn" id="oc-rm-{{ $oc }}"
                      onclick="removeOccasionPhoto(event, {{ $oc }})"
                      style="display:flex;">×</button>
                  @else
                    <img id="oc-img-{{ $oc }}" src="" alt="" style="display:none;">
                    <svg id="oc-icon-{{ $oc }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c8d4c8" stroke-width="1.5">
                      <rect x="3" y="3" width="18" height="18" rx="2"/>
                      <circle cx="8.5" cy="8.5" r="1.5"/>
                      <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    <button type="button" class="oc-rm-btn" id="oc-rm-{{ $oc }}"
                      onclick="removeOccasionPhoto(event, {{ $oc }})">×</button>
                  @endif
                </div>
                <div style="flex:1;min-width:0;">
                  <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.06em;">Occasion {{ $oc }}</div>
                  <button type="button" class="oc-upload-btn"
                    onclick="document.getElementById('oc-file-{{ $oc }}').click()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="16 16 12 12 8 16"/>
                      <line x1="12" y1="12" x2="12" y2="21"/>
                      <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                    </svg>
                    Upload Foto
                  </button>
                  <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Rasio 1:1 (persegi). Maks 3MB.</div>
                </div>
              </div>

              <input type="file" id="oc-file-{{ $oc }}" name="occasion_{{ $oc }}_photo"
                     accept="image/*" style="display:none"
                     onchange="previewOccasionPhoto(this, {{ $oc }})">
              <input type="hidden" name="remove_occasion_{{ $oc }}_photo"
                     id="oc-remove-flag-{{ $oc }}" value="">

              {{-- Label --}}
              <div style="margin-bottom:12px;">
                <label style="display:block;font-size:12px;font-weight:600;color:var(--text);margin-bottom:6px;">Label / Nama</label>
                <input type="text" class="oc-sm-input" name="occasion_{{ $oc }}_label"
                       value="{{ $ocLabel }}"
                       placeholder="{{ $occasionDefaults[$oc]['label'] }}">
              </div>

              {{-- Link --}}
              <div>
                <label style="display:block;font-size:12px;font-weight:600;color:var(--text);margin-bottom:6px;">
                  Link URL <span style="font-weight:400;color:var(--text-muted)">(opsional)</span>
                </label>
                <input type="text" class="oc-sm-input" name="occasion_{{ $oc }}_link"
                       value="{{ $ocLink }}"
                       placeholder="{{ $occasionDefaults[$oc]['placeholder'] }}">
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Kosongkan → otomatis ke halaman katalog.</div>
              </div>
            </div>
            @endfor
          </div>

          <div style="margin-top:16px;padding:11px 14px;background:#f0faf4;border-radius:8px;font-size:12px;color:#276749;display:flex;align-items:flex-start;gap:8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span>Foto ditampilkan sebagai <strong>lingkaran bulat</strong> di section "Shop by Occasion" landing page. Gunakan foto persegi (1:1) untuk hasil terbaik. Jika foto belum diupload, akan tampil emoji bawaan.</span>
          </div>
        </div>

        {{-- ── KEBIJAKAN & SYARAT ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Kebijakan & Syarat
          </div>
          <div class="form-group">
            <label class="form-label">Privacy Policy</label>
            <textarea class="form-input" name="privacy_policy" style="min-height:140px;"
              placeholder="Tulis kebijakan privasi toko kamu di sini...">{{ $settings['privacy_policy'] ?? '' }}</textarea>
            <div class="form-hint">Akan tampil sebagai popup di footer website.</div>
          </div>
          <div class="form-group">
            <label class="form-label">Terms of Service</label>
            <textarea class="form-input" name="terms_of_service" style="min-height:140px;"
              placeholder="Tulis syarat dan ketentuan toko kamu di sini...">{{ $settings['terms_of_service'] ?? '' }}</textarea>
            <div class="form-hint">Akan tampil sebagai popup di footer website.</div>
          </div>
        </div>

        {{-- ── REKENING BANK ── --}}
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            Rekening Bank Pembayaran
          </div>

          {{-- Bank 1 --}}
          <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C9A96E;margin-bottom:12px;">Bank 1 (Utama)</p>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:24px;">
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Nama Bank</label>
              <input type="text" class="form-input" name="bank_name_1" value="{{ \App\Models\Setting::get('bank_name_1') }}" placeholder="contoh: BCA">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">No. Rekening</label>
              <input type="text" class="form-input" name="bank_number_1" value="{{ \App\Models\Setting::get('bank_number_1') }}" placeholder="contoh: 1234567890">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Atas Nama</label>
              <input type="text" class="form-input" name="bank_holder_1" value="{{ \App\Models\Setting::get('bank_holder_1') }}" placeholder="contoh: TuquParcel">
            </div>
          </div>

          {{-- Bank 2 --}}
          <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6B6560;margin-bottom:12px;">Bank 2 (Opsional)</p>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Nama Bank</label>
              <input type="text" class="form-input" name="bank_name_2" value="{{ \App\Models\Setting::get('bank_name_2') }}" placeholder="contoh: Mandiri">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">No. Rekening</label>
              <input type="text" class="form-input" name="bank_number_2" value="{{ \App\Models\Setting::get('bank_number_2') }}" placeholder="contoh: 0987654321">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Atas Nama</label>
              <input type="text" class="form-input" name="bank_holder_2" value="{{ \App\Models\Setting::get('bank_holder_2') }}" placeholder="contoh: TuquParcel">
            </div>
          </div>
        </div>

        </form>
      </div>

      <!-- AKUN -->
      <div class="tab-panel" id="tab-akun">
        <form method="POST" action="{{ route('admin.pengaturan.updateAkun') }}" id="form-akun">
          @csrf @method('PUT')
        <div class="settings-card">
          <div class="section-heading">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Profil Admin
          </div>
          <div class="profile-row">
            <div class="profile-avatar-wrap">
              <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
            </div>
            <div class="profile-meta">
              <div class="profile-name">{{ auth()->user()->name }}</div>
              <div class="profile-role">Administrator</div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-input" name="name" value="{{ auth()->user()->name }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <div class="input-icon-wrap">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" class="form-input" name="email" value="{{ auth()->user()->email }}" required>
            </div>
          </div>
          <div style="display:flex;justify-content:flex-end;margin-top:16px;">
            <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:var(--green-dark);color:#fff;border:none;border-radius:10px;font-size:13.5px;font-weight:700;cursor:pointer;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Simpan Profil
            </button>
          </div>
        </div>
        </form>
        <div class="danger-card">
          <div class="danger-heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Danger Zone</div>
          <div class="danger-item">
            <div><div class="danger-label">Reset Semua Data Produk</div><div class="danger-sub">Menghapus semua produk dari inventaris. Tidak bisa dibatalkan.</div></div>
            <button class="btn-danger">Reset Produk</button>
          </div>
          <div class="danger-item">
            <div><div class="danger-label">Hapus Akun Admin</div><div class="danger-sub">Akun ini akan dihapus permanen beserta semua aksesnya.</div></div>
            <button class="btn-danger">Hapus Akun</button>
          </div>
        </div>
      </div>

      <!-- NOTIFIKASI -->
      <div class="tab-panel" id="tab-notifikasi">
        <div class="settings-card">
          <div class="section-heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>Preferensi Notifikasi</div>
          <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Stok Hampir Habis</div><div class="toggle-sub">Notifikasi ketika stok produk di bawah batas minimum</div></div><label class="switch"><input type="checkbox" checked><span class="switch-track"></span></label></div>
          <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Review Baru</div><div class="toggle-sub">Notifikasi ketika ada ulasan baru dari pelanggan</div></div><label class="switch"><input type="checkbox"><span class="switch-track"></span></label></div>
          <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Laporan Mingguan</div><div class="toggle-sub">Kirim ringkasan performa toko setiap Senin pagi</div></div><label class="switch"><input type="checkbox" checked><span class="switch-track"></span></label></div>
        </div>
        <div class="settings-card">
          <div class="section-heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email Notifikasi</div>
          <div class="form-group">
            <label class="form-label">Email Penerima Notifikasi</label>
            <div class="input-icon-wrap">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" class="form-input" name="admin_email" form="form-branding"
                value="{{ old('admin_email', $settings['admin_email'] ?? '') }}"
                placeholder="admin@gmail.com">
            </div>
            <div class="form-hint">Email ini akan menerima notifikasi review baru dan stok rendah.</div>
          </div>
          <div class="form-group">
            <label class="form-label">Batas Minimum Stok</label>
            <input type="number" class="form-input" name="low_stock_threshold" form="form-branding" value="{{ $settings['low_stock_threshold'] ?? 10 }}" min="1" style="max-width:200px;">
            <div class="form-hint">Notifikasi akan dikirim jika stok produk di bawah angka ini.</div>
          </div>
        </div>
      </div>

      <!-- KEAMANAN -->
      <div class="tab-panel" id="tab-keamanan">
        <form method="POST" action="{{ route('admin.pengaturan.updatePassword') }}" id="form-password">
          @csrf @method('PUT')
        <div class="settings-card">
          <div class="section-heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Ganti Password</div>
          <div class="form-group"><label class="form-label">Password Saat Ini</label><input type="password" class="form-input" name="current_password" placeholder="••••••••" style="max-width:400px;" required></div>
          <div class="form-grid-2" style="max-width:820px;">
            <div class="form-group"><label class="form-label">Password Baru</label><input type="password" class="form-input" name="password" placeholder="••••••••" required></div>
            <div class="form-group"><label class="form-label">Konfirmasi Password Baru</label><input type="password" class="form-input" name="password_confirmation" placeholder="••••••••" required></div>
          </div>
          <div class="form-hint">Gunakan minimal 8 karakter dengan kombinasi huruf, angka, dan simbol.</div>
          <button type="submit" class="btn-save" style="margin-top:16px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
            Simpan Password
          </button>
        </div>
        </form>
        <div class="settings-card">
<form method="POST" action="{{ route('admin.pengaturan.update') }}" id="form-keamanan">@csrf          <div class="section-heading"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Keamanan Login</div>
          <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Two-Factor Authentication (2FA)</div><div class="toggle-sub">Tambahan keamanan dengan kode verifikasi saat login</div></div><label class="switch"><input type="checkbox" name="admin_2fa" value="1" {{ ($settings['admin_2fa'] ?? '') == '1' ? 'checked' : '' }}><span class="switch-track"></span></label></div>
          <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Login Notification</div><div class="toggle-sub">Kirim email saat ada login baru ke akun ini</div></div><label class="switch"><input type="checkbox" name="admin_login_notification" value="1" {{ ($settings['admin_login_notification'] ?? '1') == '1' ? 'checked' : '' }}><span class="switch-track"></span></label></div>
          <div class="toggle-row"><div class="toggle-info"><div class="toggle-label">Auto Logout</div><div class="toggle-sub">Keluar otomatis setelah 30 menit tidak aktif</div></div><label class="switch"><input type="checkbox" name="admin_auto_logout" value="1" {{ ($settings['admin_auto_logout'] ?? '1') == '1' ? 'checked' : '' }}><span class="switch-track"></span></label></div>
        </div>
      </div></form>

    </div>
  </div>

  <!-- Bottom Bar -->
  <div class="bottom-bar">
    <button type="button" class="btn-cancel-bare" onclick="window.history.back()">Batal</button>
    <button type="submit" form="form-branding" class="btn-save" id="btn-save-main">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Simpan Pengaturan
    </button>
  </div>

</div>
@push('scripts')
<script>
// Map tab -> form id for save button
  const tabFormMap = {
    branding: 'form-branding',
    akun: 'form-akun',
    notifikasi: 'form-branding',
    keamanan: 'form-keamanan',
  };

  function switchTab(tabId, el) {
    document.querySelectorAll('.stab').forEach(btn => btn.classList.remove('active'));
    if(el) el.classList.add('active');
    document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
    const target = document.getElementById('tab-' + tabId);
    if(target) target.classList.add('active');

    const saveBtn = document.getElementById('btn-save-main');
    const formId = tabFormMap[tabId];
    if (saveBtn) {
      if (formId) {
        saveBtn.form = formId;
        saveBtn.style.display = 'flex';
      } else {
        saveBtn.style.display = 'none';
      }
    }
  }

  function previewLogo(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.getElementById('logo-img');
        const defaultIcon = document.querySelector('.logo-preview > svg');
        const removeBtn = document.getElementById('logo-remove');
        img.src = e.target.result;
        img.style.display = 'block';
        if(defaultIcon) defaultIcon.style.display = 'none';
        removeBtn.style.display = 'flex';
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  // === KEUNGGULAN PHOTO ===
  function previewKeunggulanPhoto(input, n) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.getElementById('keunggulan-img-' + n);
      const placeholder = document.getElementById('keunggulan-placeholder-' + n);
      img.src = e.target.result;
      img.style.display = 'block';
      if (placeholder) placeholder.style.display = 'none';
      document.getElementById('remove-keunggulan-' + n).value = '';
      const preview = document.getElementById('keunggulan-preview-' + n);
      let btn = preview.querySelector('.k-rm');
      if (!btn) {
        btn = document.createElement('button');
        btn.type = 'button'; btn.className = 'k-rm';
        btn.innerHTML = '×';
        btn.style.cssText = 'position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);color:#fff;border:none;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;z-index:2;';
        btn.onclick = function(ev) { removeKeunggulanPhoto(ev, n); };
        preview.appendChild(btn);
      }
    };
    reader.readAsDataURL(input.files[0]);
  }

  function removeKeunggulanPhoto(e, n) {
    e.stopPropagation();
    const img = document.getElementById('keunggulan-img-' + n);
    const placeholder = document.getElementById('keunggulan-placeholder-' + n);
    const input = document.getElementById('keunggulan-file-' + n);
    const preview = document.getElementById('keunggulan-preview-' + n);
    img.src = ''; img.style.display = 'none';
    if (placeholder) placeholder.style.display = 'block';
    const btn = preview.querySelector('.k-rm');
    if (btn) btn.remove();
    input.value = '';
    document.getElementById('remove-keunggulan-' + n).value = '1';
  }


  function previewTeamPhoto(input, n) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.getElementById('team-img-' + n);
      const placeholder = document.getElementById('team-placeholder-' + n);
      img.src = e.target.result;
      img.style.display = 'block';
      if (placeholder) placeholder.style.display = 'none';
      document.getElementById('remove-team-' + n).value = '';
      const preview = document.getElementById('team-preview-' + n);
      let btn = preview.querySelector('.t-rm');
      if (!btn) {
        btn = document.createElement('button');
        btn.type = 'button'; btn.className = 't-rm';
        btn.innerHTML = '×';
        btn.style.cssText = 'position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);color:#fff;border:none;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;z-index:2;';
        btn.onclick = function(ev) { removeTeamPhoto(ev, n); };
        preview.appendChild(btn);
      }
    };
    reader.readAsDataURL(input.files[0]);
  }

  function removeTeamPhoto(e, n) {
    e.stopPropagation();
    const img = document.getElementById('team-img-' + n);
    const placeholder = document.getElementById('team-placeholder-' + n);
    const input = document.getElementById('team-file-' + n);
    const preview = document.getElementById('team-preview-' + n);
    img.src = ''; img.style.display = 'none';
    if (placeholder) placeholder.style.display = 'block';
    const btn = preview.querySelector('.t-rm');
    if (btn) btn.remove();
    input.value = '';
    document.getElementById('remove-team-' + n).value = '1';
  }

  // === OCCASION PHOTO ===
  function previewOccasionPhoto(input, n) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      const img  = document.getElementById('oc-img-' + n);
      const icon = document.getElementById('oc-icon-' + n);
      const rm   = document.getElementById('oc-rm-' + n);
      img.src = e.target.result;
      img.style.display = 'block';
      if (icon) icon.style.display = 'none';
      if (rm)   rm.style.display   = 'flex';
      document.getElementById('oc-remove-flag-' + n).value = '';
    };
    reader.readAsDataURL(input.files[0]);
  }

  function removeOccasionPhoto(e, n) {
    e.stopPropagation();
    const img  = document.getElementById('oc-img-' + n);
    const icon = document.getElementById('oc-icon-' + n);
    const rm   = document.getElementById('oc-rm-' + n);
    const file = document.getElementById('oc-file-' + n);
    img.src = ''; img.style.display = 'none';
    if (icon) icon.style.display = 'block';
    if (rm)   rm.style.display   = 'none';
    if (file) file.value = '';
    document.getElementById('oc-remove-flag-' + n).value = '1';
  }
  // === END OCCASION ===

  function removeLogo(e) {
    e.stopPropagation();
    const img = document.getElementById('logo-img');
    const defaultIcon = document.querySelector('#logo-preview > svg');
    const removeBtn = document.getElementById('logo-remove');
    const input = document.getElementById('logo-file');
    img.src = '';
    img.style.display = 'none';
    if(defaultIcon) defaultIcon.style.display = 'block';
    removeBtn.style.display = 'none';
    input.value = '';
  }

  function previewLogoNavbar(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.getElementById('logo-navbar-img');
        const defaultIcon = document.querySelector('#logo-navbar-preview > svg');
        const removeBtn = document.getElementById('logo-navbar-remove');
        img.src = e.target.result;
        img.style.display = 'block';
        if(defaultIcon) defaultIcon.style.display = 'none';
        removeBtn.style.display = 'flex';
        document.getElementById('remove_logo_navbar').value = '';
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function removeLogoNavbar(e) {
    e.stopPropagation();
    const img = document.getElementById('logo-navbar-img');
    const defaultIcon = document.querySelector('#logo-navbar-preview > svg');
    const removeBtn = document.getElementById('logo-navbar-remove');
    const input = document.getElementById('logo-navbar-file');
    img.src = '';
    img.style.display = 'none';
    if(defaultIcon) defaultIcon.style.display = 'block';
    removeBtn.style.display = 'none';
    input.value = '';
    document.getElementById('remove_logo_navbar').value = '1';
  }


</script>
@endpush
@endsection
