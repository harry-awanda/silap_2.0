@extends ('layouts.app')
@section ('title', 'Pengelolaan Akun')
@section ('content')
  <div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
    <h1 class="h3 mb-0">Pengelolaan Akun</h1>
    @can ('create', \App\Models\User::class)
      <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Akun</a>
    @endcan
  </div>
  @if (session('temporary_password'))
    <div class="alert alert-warning" role="status">
      <strong>Simpan password sementara ini sebelum meninggalkan halaman.</strong>
      <div>Username: <code>{{ session('temporary_username') }}</code></div>
      <div>
        Password: <code>{{ \Illuminate\Support\Facades\Crypt::decryptString(session('temporary_password')) }}</code>
      </div>
      <small>Ditampilkan sekali. Sampaikan secara pribadi kepada pemilik akun; wajib diganti saat login.</small>
    </div>
  @endif
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('users.index') }}" class="row g-3">
        <div class="col-lg-5">
          <label for="q" class="form-label">Cari nama atau username</label
          ><input id="q" class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" maxlength="100" />
        </div>
        <div class="col-lg-3">
          <label for="role" class="form-label">Role</label>
          <select id="role" class="form-select" name="role">
            <option value="">Semua role</option>
            @foreach ($roles as $role)
              <option value="{{ $role->value }}" @selected (($filters['role'] ?? '') === $role->value)>
                {{ $role->label() }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-2">
          <label for="status" class="form-label">Status</label>
          <select id="status" class="form-select" name="status">
            <option value="">Semua status</option>
            <option value="active" @selected (($filters['status'] ?? '') === 'active')>Aktif</option>
            <option value="disabled" @selected (($filters['status'] ?? '') === 'disabled')>Nonaktif</option>
          </select>
        </div>
        <div class="col-lg-2 d-flex align-items-end">
          <button class="btn btn-outline-primary w-100" type="submit">Terapkan</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0">
        <caption class="px-3">
          Daftar akun pengguna sekolah
        </caption>
        <thead>
          <tr>
            <th>Pengguna</th>
            <th>Role</th>
            <th>Status</th>
            <th>Akses</th>
            <th>Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $account)
            <tr>
              <td>
                <strong>{{ $account->name }}</strong>
                <div class="small text-muted">{{ $account->username }}</div>
              </td>
              <td>
                @foreach ($account->roles as $role)
                  <span
                    class="badge bg-label-secondary mb-1"
                    >{{ \App\Enums\AppRole::tryFrom($role->name)?->label() ?? $role->name }}</span
                  >
                @endforeach
              </td>
              <td>
                <span
                  class="badge {{ $account->disabled_at ? 'bg-label-danger' : 'bg-label-success' }}"
                  >{{ $account->disabled_at ? 'Nonaktif' : 'Aktif' }}</span
                >
              </td>
              <td>{{ $account->must_change_password ? 'Wajib ganti password' : 'Siap digunakan' }}</td>
              <td>
                <div class="d-flex flex-wrap gap-2">
                  @can ('update', $account)
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('users.edit', $account) }}">Ubah</a>
                  @endcan
                  @can ('resetPassword', $account)
                    <form
                      method="POST"
                      action="{{ route('users.reset-password', $account) }}"
                      data-confirm="Reset password dan cabut sesi akun ini?"
                    >
                      @csrf
                      <button class="btn btn-outline-warning btn-sm" type="submit">Reset Password</button>
                    </form>
                  @endcan
                  @can ('setActive', $account)
                    <form
                      method="POST"
                      action="{{ route('users.active', $account) }}"
                      data-confirm="{{ $account->disabled_at ? 'Aktifkan kembali akun ini?' : 'Nonaktifkan akun dan cabut sesinya?' }}"
                    >
                      @csrf
                      @method ('PATCH')
                      <input type="hidden" name="active" value="{{ $account->disabled_at ? 1 : 0 }}" />
                      <button class="btn btn-outline-secondary btn-sm" type="submit">
                        {{ $account->disabled_at ? 'Aktifkan' : 'Nonaktifkan' }}
                      </button>
                    </form>
                  @endcan
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4">Tidak ada akun yang sesuai filter.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-body">{{ $users->links() }}</div>
  </div>
@endsection
