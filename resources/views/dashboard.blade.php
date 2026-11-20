@extends ('layouts.app')
@section ('title', 'Dashboard')
@section ('content')
  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="row align-items-center h-100">
          <div class="col-sm-8">
            <div class="card-body">
              <span class="badge bg-label-primary mb-3">Layanan sekolah</span>
              <h1 class="h4 text-primary">Selamat datang, {{ auth()->user()->name }}.</h1>
              <p class="mb-4">Kelola layanan sekolah melalui menu yang tersedia sesuai role dan penugasan Anda.</p>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('password.edit') }}">Keamanan akun</a>
            </div>
          </div>
          <div class="col-sm-4 text-center px-4 pb-4 pt-sm-4">
            <img
              src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}"
              class="img-fluid silap-welcome-image"
              alt=""
            />
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="avatar mb-3">
            <span class="avatar-initial rounded bg-label-success"
              ><i class="bx bx-check-shield" aria-hidden="true"></i
            ></span>
          </div>
          <h2 class="h5">Akses akun Anda</h2>
          <p class="text-muted">Seluruh role aktif Anda ditampilkan di bawah.</p>
          <span class="h3 mb-0">{{ $roles->count() }}</span><span class="text-muted ms-2">role ditetapkan</span>
        </div>
      </div>
    </div>
  </div>
  @can ('viewAny', \App\Models\User::class)
    <div class="card mb-4">
      <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <span class="avatar"
            ><span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group" aria-hidden="true"></i></span
          ></span>
          <div>
            <h2 class="h5 mb-1">Pengelolaan Akun</h2>
            <p class="text-muted mb-0">Buat akun, tetapkan role, atau bantu kendala akses.</p>
          </div>
        </div>
        <a class="btn btn-primary" href="{{ route('users.index') }}"
          >Kelola Akun<i class="bx bx-right-arrow-alt ms-2" aria-hidden="true"></i
        ></a>
      </div>
    </div>
  @endcan
  <h2 class="h5 mb-3">Role dan layanan Anda</h2>
  <div class="row g-3">
    @forelse ($roles as $role)
      <div class="col-sm-6 col-xl-4">
        <section class="card h-100">
          <div class="card-body">
            <span class="badge bg-label-primary mb-3">Role Anda</span>
            <h2 class="h5">{{ $role->label() }}</h2>
            <p class="text-muted mb-0">
              @switch ($role->value)
                @case ('superadmin')
                  Pengawasan dan pengaturan layanan sekolah.
                  @break
                @case ('admin')
                  Dukungan akun dan administrasi operasional.
                  @break
                @case ('tata_usaha')
                  Administrasi data dan mutasi siswa.
                  @break
                @case ('guru')
                  Akses layanan sesuai penugasan guru.
                  @break
                @case ('wali_kelas')
                  Pendampingan siswa sesuai kelas binaan.
                  @break
                @case ('guru_piket')
                  Pendataan keterlambatan dan kegiatan piket.
                  @break
                @case ('guru_bk')
                  Pembinaan dan tindak lanjut siswa.
                  @break
                @case ('kesiswaan')
                  Pemantauan dan pembinaan kesiswaan.
                  @break
                @case ('siswa')
                  Akses layanan dan riwayat pribadi.
                  @break
              @endswitch
            </p>
          </div>
        </section>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">Role belum ditetapkan. Hubungi administrator sekolah.</div>
      </div>
    @endforelse
  </div>
  <p class="text-muted small mt-4">Layanan sekolah lainnya akan tersedia secara bertahap.</p>
@endsection
