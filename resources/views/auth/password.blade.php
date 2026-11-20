@extends ($forced ? 'layouts.auth' : 'layouts.app')
@section ('title', 'Ganti Password')
@section ('content')
  <div class="row justify-content-center">
    <div class="{{ $forced ? 'col-12' : 'col-lg-8 col-xl-6' }}">
      <div class="card">
        <div class="card-body">
          <span class="badge bg-label-primary p-3 mb-3"
            ><i class="bx bx-lock-open-alt fs-3" aria-hidden="true"></i
          ></span>
          <h1 class="h4">Ganti password</h1>
          <p>{{ $forced ? 'Ganti password awal atau sementara sebelum melanjutkan.' : 'Gunakan password yang hanya Anda ketahui.' }}</p>
          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method ('PUT')
            <x-password-input
              name="current_password"
              label="Password saat ini"
              autocomplete="current-password"
              autofocus
            />
            <x-password-input
              name="password"
              label="Password baru"
              minlength="12"
              help="Minimal 12 karakter, dengan huruf besar, huruf kecil dan angka."
            />
            <x-password-input name="password_confirmation" label="Ulangi password baru" minlength="12" />
            <button class="btn btn-primary w-100" type="submit">Simpan Password</button>
          </form>
          @unless ($forced)
            <a class="btn btn-outline-secondary mt-3 w-100" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
          @else
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
              @csrf
              <button type="submit" class="btn btn-outline-secondary w-100">Keluar</button>
            </form>
          @endunless
        </div>
      </div>
    </div>
  </div>
@endsection
