@extends ('layouts.app')
@section ('title', $account->exists ? 'Ubah Akun' : 'Tambah Akun')
@section ('content')
  <h1 class="h3 mb-4">{{ $account->exists ? 'Ubah Akun' : 'Tambah Akun' }}</h1>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ $account->exists ? route('users.update', $account) : route('users.store') }}">
        @csrf
        @if ($account->exists) @method ('PUT') @endif
        <div class="mb-3">
          <label class="form-label" for="name">Nama</label>
          <input
            id="name"
            class="form-control"
            name="name"
            value="{{ old('name', $account->name) }}"
            maxlength="100"
            required
          />
        </div>
        <div class="mb-3">
          <label class="form-label" for="username">Username</label>
          <input
            id="username"
            class="form-control"
            name="username"
            value="{{ old('username', $account->username) }}"
            minlength="3"
            maxlength="50"
            pattern="[a-zA-Z0-9][a-zA-Z0-9._-]*"
            required
          />
          <div class="form-text">Huruf, angka, titik, garis bawah atau tanda minus. Disimpan sebagai huruf kecil.</div>
        </div>
        <div class="mb-3">
          <label class="form-label" for="email">Email (opsional)</label>
          <input
            id="email"
            class="form-control"
            name="email"
            type="email"
            value="{{ old('email', $account->email) }}"
            maxlength="255"
          />
        </div>
        <fieldset class="mb-4">
          <legend class="form-label">Role — dapat memilih lebih dari satu</legend>
          @foreach ($roles as $role)
            <div class="form-check mb-2">
              <input
                class="form-check-input"
                id="role-{{ $role->value }}"
                type="checkbox"
                name="roles[]"
                value="{{ $role->value }}"
                @checked (in_array($role->value, old('roles', $account->exists ? $account->getRoleNames()->all() : []), true))
              />
              <label class="form-check-label" for="role-{{ $role->value }}">{{ $role->label() }}</label>
            </div>
          @endforeach
        </fieldset>
        @unless ($account->exists)
          <p class="text-muted">Password sementara akan dibuat otomatis dan ditampilkan sekali setelah akun disimpan.</p>
        @endunless
        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-primary" type="submit">Simpan</button>
          <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection
