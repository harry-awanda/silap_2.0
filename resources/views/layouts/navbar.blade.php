<nav
  id="layout-navbar"
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  aria-label="Navigasi akun"
>
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 d-xl-none">
    <button class="nav-item nav-link px-0 border-0 bg-transparent" type="button" aria-label="Buka menu utama">
      <i class="bx bx-menu bx-sm" aria-hidden="true"></i>
    </button>
  </div>
  <div class="navbar-nav-right d-flex align-items-center w-100">
    <span class="fw-medium text-truncate me-3">@yield ('title', 'SILAP')</span>
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item me-3 d-none d-md-block"><span class="badge bg-label-primary">SILAP v2.0</span></li>
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <button
          id="account-menu"
          class="nav-link dropdown-toggle hide-arrow border-0 bg-transparent"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          aria-label="Buka menu akun"
        >
          <span class="avatar"
            ><span
              class="avatar-initial rounded-circle bg-label-primary"
              >{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span
            ></span
          >
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="account-menu">
          <li class="px-3 py-2">
            <strong class="d-block text-break">{{ auth()->user()->name }}</strong>
            <small class="text-muted text-break">{{ auth()->user()->username }}</small>
          </li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <a class="dropdown-item" href="{{ route('password.edit') }}"
              ><i class="bx bx-lock-alt me-2" aria-hidden="true"></i>Ganti Password</a
            >
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="dropdown-item" type="submit">
                <i class="bx bx-power-off me-2" aria-hidden="true"></i>Keluar
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
