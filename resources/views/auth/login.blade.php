@extends ('layouts.app')
@section ('title', 'Masuk')
@section ('content')
  <div class="card silap-auth mx-auto">
    <div class="card-body p-4 p-md-5">
      <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center gap-2 mb-4">
        <i class="bx bxs-school fs-1" aria-hidden="true"></i><span class="h3 mb-0 text-primary">SILAP</span>
      </a>
      <h1 class="h4">Selamat datang kembali</h1>
      <p class="text-muted">Masuk untuk mengakses layanan administrasi sekolah.</p>
      <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            id="username"
            class="form-control"
            name="username"
            value="{{ old('username') }}"
            autocomplete="username"
            maxlength="50"
            required
            autofocus
          />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            id="password"
            class="form-control"
            name="password"
            type="password"
            autocomplete="current-password"
            required
          />
        </div>
        <div class="form-check mb-3">
          <input
            id="remember"
            class="form-check-input"
            name="remember"
            value="1"
            type="checkbox"
            @checked (old('remember'))
          />
          <label for="remember" class="form-check-label">Ingat saya</label>
        </div>
        <button class="btn btn-primary w-100" type="submit">Masuk</button>
      </form>
      <p class="small text-muted mt-4 mb-0">Lupa password atau akun tidak aktif? Hubungi administrator sekolah.</p>
    </div>
  </div>
@endsection
