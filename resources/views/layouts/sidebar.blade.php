<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" aria-label="Menu utama">
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link gap-2">
      <span class="app-brand-logo demo"><i class="bx bxs-school text-primary fs-2" aria-hidden="true"></i></span>
      <span class="app-brand-text demo menu-text fw-bold text-uppercase">SILAP</span>
    </a>
    <button
      type="button"
      class="layout-menu-toggle menu-link text-large ms-auto border-0 bg-transparent"
      aria-label="Buka atau tutup menu"
    >
      <i class="bx bx-chevron-left bx-sm align-middle" aria-hidden="true"></i>
    </button>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-3">
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Menu utama</span></li>
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a
        class="menu-link"
        href="{{ route('dashboard') }}"
        @if (request()->routeIs('dashboard')) aria-current="page" @endif
      >
        <i class="menu-icon bx bx-home-circle" aria-hidden="true"></i><span>Dashboard</span>
      </a>
    </li>
    @can ('viewAny', \App\Models\User::class)
      <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <a
          class="menu-link"
          href="{{ route('users.index') }}"
          @if (request()->routeIs('users.*')) aria-current="page" @endif
        >
          <i class="menu-icon bx bx-group" aria-hidden="true"></i><span>Pengelolaan Akun</span>
        </a>
      </li>
    @endcan
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Akun saya</span></li>
    <li class="menu-item {{ request()->routeIs('password.*') ? 'active' : '' }}">
      <a
        class="menu-link"
        href="{{ route('password.edit') }}"
        @if (request()->routeIs('password.*')) aria-current="page" @endif
      >
        <i class="menu-icon bx bx-lock-alt" aria-hidden="true"></i><span>Ganti Password</span>
      </a>
    </li>
  </ul>
</aside>
