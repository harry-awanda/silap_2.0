@extends ('layouts.app')
@section ('title', 'Dashboard')
@section ('content')
  <div class="mb-4">
    <h1 class="h3">Dashboard</h1>
    <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}.</p>
  </div>
  @can ('viewAny', \App\Models\User::class)
    <div class="card mb-4">
      <div class="card-body">
        <h2 class="h5">Pengelolaan Akun</h2>
        <p>Buat akun, tetapkan role, atau bantu pengguna yang mengalami kendala akses.</p>
        <a class="btn btn-primary" href="{{ route('users.index') }}">Kelola Akun</a>
      </div>
    </div>
  @endcan
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
