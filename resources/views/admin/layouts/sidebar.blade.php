<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-avatar">TP</div>
    <div class="brand-text">
      <div class="name">TuquParcel</div>
      <div class="sub">Admin Panel</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-label">Menu</div>

    <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      Dashboard
    </a>

    <a class="nav-item {{ request()->routeIs('admin.inventaris*') ? 'active' : '' }}" href="{{ route('admin.inventaris.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      Inventaris
    </a>

    <a class="nav-item {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 6h16M4 10h16M4 14h10M4 18h6"/>
      </svg>
      Kategori
    </a>

    <div class="nav-label">Lainnya</div>

    <a class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      Pesanan
      @php $pendingPaid = \App\Models\Order::where('status','paid')->count(); @endphp
      @if($pendingPaid > 0)
        <span style="margin-left:auto;background:#C9A96E;color:white;font-size:0.65rem;font-weight:700;padding:1px 7px;border-radius:20px;">{{ $pendingPaid }}</span>
      @endif
    </a>

    <a class="nav-item {{ request()->routeIs('admin.review*') ? 'active' : '' }}" href="{{ route('admin.review.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      Komentar
    </a>

    <a class="nav-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}" href="{{ route('admin.pengaturan.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
      </svg>
      Pengaturan
    </a>

    <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" style="display:none;">
      @csrf
    </form>
    <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Keluar
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
      <div class="user-info">
        <div class="uname">{{ auth()->user()->name ?? 'Admin' }}</div>
        <div class="urole">Administrator</div>
      </div>
    </div>
  </div>
</aside>
