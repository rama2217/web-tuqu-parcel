<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
  <title>@yield('title', 'Dashboard') — TuquParcel Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"></noscript>
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
  @stack('styles')
  <style>
    html { visibility: hidden; }
  </style>
</head>
<body>
  <script>document.documentElement.style.visibility = 'visible';</script>
<div class="app">
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  @include('admin.layouts.sidebar')

  <div class="main">
    <div class="topbar">
      <div class="topbar-brand">TuquParcel</div>
      <button class="hamburger" onclick="toggleSidebar()"><span></span><span></span><span></span></button>
    </div>

    <div class="content">
      @if(session('success'))
        <div class="alert-success" style="background:#e8f5ee;border:1px solid #38a169;border-radius:10px;padding:12px 18px;margin-bottom:18px;font-size:13.5px;color:#276749;display:flex;align-items:center;gap:8px;">
          ✅ {{ session('success') }}
        </div>
      @endif

      @yield('content')
    </div>
  </div>
</div>

@yield('bottom_bar')

<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
  }
  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
  }
</script>
@stack('scripts')
</body>
</html>
