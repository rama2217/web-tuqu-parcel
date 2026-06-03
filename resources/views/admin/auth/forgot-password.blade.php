<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password — TuquParcel Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green-dark: #2D4A3E; --green-mid: #3D6B59; --green-light: #E8F0EB;
      --ivory: #F7F3EE; --white: #FDFCFB; --bg: #EFEFED;
      --text-dark: #1A1A1A; --text-mid: #4A4A4A; --text-muted: #6B6560;
      --border: #E0D9D0; --shadow-card: 0 8px 40px rgba(45,74,62,0.10);
      --radius-card: 12px; --radius-btn: 6px; --radius-input: 6px;
    }
    html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text-dark); font-size: 16px; }
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }

    .login-card {
      background: var(--white);
      border-radius: var(--radius-card);
      box-shadow: var(--shadow-card);
      width: 100%; max-width: 440px;
      padding: 48px 44px 36px;
      border: 1px solid rgba(224,217,208,0.6);
      animation: fadeUp 0.4s ease both;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .logo-wrap { display: flex; justify-content: center; margin-bottom: 24px; }
    .logo-icon {
      width: 60px; height: 60px;
      background: var(--green-light); border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      border: 1px solid rgba(45,74,62,0.12);
    }
    .logo-icon svg { width: 28px; height: 28px; stroke: var(--green-dark); }

    .login-header { text-align: center; margin-bottom: 10px; }
    .login-title { font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; }
    .login-subtitle { font-size: 0.85rem; color: var(--text-muted); font-weight: 300; line-height: 1.6; margin-bottom: 28px; }

    /* Success banner */
    .success-msg {
      display: flex; align-items: flex-start; gap: 10px;
      background: #EDFAF3; border: 1px solid #A8DFC0;
      border-radius: var(--radius-input); padding: 13px 15px;
      margin-bottom: 20px; font-size: 0.84rem; color: #1E6E45; line-height: 1.5;
    }
    .success-msg svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }

    /* Error banner */
    .error-msg {
      display: flex; align-items: flex-start; gap: 8px;
      background: #FEF0EE; border: 1px solid #F5C6C0;
      border-radius: var(--radius-input); padding: 11px 14px;
      margin-bottom: 16px; font-size: 0.83rem; color: #C0392B;
    }
    .error-msg svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 0.82rem; font-weight: 500; color: var(--text-mid); margin-bottom: 6px; letter-spacing: 0.01em; }
    .form-input {
      width: 100%; padding: 13px 16px;
      background: var(--white); border: 1.5px solid var(--border);
      border-radius: var(--radius-input);
      font-size: 0.9rem; font-family: 'DM Sans', sans-serif; color: var(--text-dark);
      outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus { border-color: var(--green-dark); box-shadow: 0 0 0 3px rgba(45,74,62,0.08); }
    .form-input::placeholder { color: var(--text-muted); font-weight: 300; }

    .btn-primary {
      width: 100%; padding: 14px;
      background: var(--green-dark); color: var(--ivory);
      border: none; border-radius: var(--radius-btn);
      font-size: 0.95rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
      letter-spacing: 0.04em; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: all 0.25s;
    }
    .btn-primary:hover { background: var(--green-mid); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(45,74,62,0.2); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-primary svg { width: 16px; height: 16px; }

    /* Spinner */
    .spinner {
      width: 16px; height: 16px;
      border: 2px solid rgba(247,243,238,0.4);
      border-top-color: var(--ivory);
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .card-divider { height: 1px; background: var(--border); margin: 24px 0 18px; }
    .back-link {
      display: flex; align-items: center; justify-content: center; gap: 6px;
      font-size: 0.83rem; color: var(--text-muted); text-decoration: none;
      transition: color 0.2s;
    }
    .back-link:hover { color: var(--green-dark); }
    .back-link svg { width: 14px; height: 14px; }
    .card-footer { text-align: center; font-size: 0.75rem; color: var(--text-muted); font-weight: 300; margin-top: 18px; }

    @media (max-width: 480px) {
      .login-card { padding: 36px 24px 28px; }
      .login-title { font-size: 1.5rem; }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="logo-wrap">
      <div class="logo-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2C8.5 2 6 5 6 8c0 4 6 10 6 10s6-6 6-10c0-3-2.5-6-6-6z"/>
        </svg>
      </div>
    </div>

    <div class="login-header">
      <h1 class="login-title">Lupa Password?</h1>
      <p class="login-subtitle">
        Masukkan email akun admin Anda.<br>
        Kami akan mengirimkan link untuk membuat password baru.
      </p>
    </div>

    {{-- Success message --}}
    @if (session('status'))
    <div class="success-msg">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
      {{ session('status') }}
    </div>
    @endif

    {{-- Error message --}}
    @if ($errors->any())
    <div class="error-msg">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm">
      @csrf
      <div class="form-group">
        <label class="form-label" for="email">Alamat Email</label>
        <input
          type="email" name="email" id="email"
          class="form-input"
          placeholder="admintuquparcel2020@gmail.com"
          value="{{ old('email') }}"
          required autocomplete="email"
        />
      </div>
      <button type="submit" class="btn-primary" id="submitBtn">
        <span id="btnText">Kirim Link Reset</span>
        <span class="spinner" id="btnSpinner"></span>
        <svg id="btnIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13"/>
          <polygon points="22 2 15 22 11 13 2 9 22 2"/>
        </svg>
      </button>
    </form>

    <div class="card-divider"></div>

    <a href="{{ route('admin.login') }}" class="back-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      Kembali ke halaman login
    </a>

    <div class="card-footer">© {{ date('Y') }} TuquParcel. All rights reserved.</div>
  </div>

  <script>
    document.getElementById('forgotForm').addEventListener('submit', function () {
      const btn     = document.getElementById('submitBtn');
      const text    = document.getElementById('btnText');
      const spinner = document.getElementById('btnSpinner');
      const icon    = document.getElementById('btnIcon');

      btn.disabled         = true;
      text.textContent     = 'Mengirim...';
      spinner.style.display = 'block';
      icon.style.display    = 'none';
    });
  </script>
</body>
</html>
