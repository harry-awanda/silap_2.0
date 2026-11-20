@extends ('layouts.auth')
@section ('title', 'Masuk')
@section ('content')
  <div class="card">
    <div class="card-body">
      <div class="app-brand justify-content-center mb-4">
        <a href="{{ route('login') }}" class="app-brand-link gap-2">
          <i class="bx bxs-school text-primary fs-1" aria-hidden="true"></i>
          <span class="app-brand-text demo fw-bold text-uppercase">SILAP</span>
        </a>
      </div>
      <h1 class="h4 mb-2">Selamat datang kembali</h1>
      <p class="mb-4">Masuk untuk mengakses layanan sekolah.</p>
      <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            id="username"
            class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
            name="username"
            value="{{ old('username') }}"
            placeholder="Masukkan username Anda"
            autocomplete="username"
            maxlength="50"
            required
            autofocus
            aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
          />
        </div>
        <x-password-input name="password" label="Password" autocomplete="current-password" />
        <div class="mb-3 form-check">
          <input
            id="remember"
            class="form-check-input"
            type="checkbox"
            name="remember"
            value="1"
            @checked (old('remember'))
          />
          <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
      </form>
      <p class="text-center text-muted small mt-4 mb-0">Kendala akses? Hubungi administrator sekolah.</p>
    </div>
  </div>
@endsection
