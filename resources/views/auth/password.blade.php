@extends('layouts.app')
@section('title', 'Ganti Password')
@section('content')
    <div class="card silap-auth mx-auto">
        <div class="card-body p-4">
            <h1 class="h4">Ganti password</h1>
            <p>{{ $forced ? 'Ganti password awal atau sementara sebelum melanjutkan.' : 'Gunakan password yang hanya Anda ketahui.' }}</p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password saat ini</label>
                    <input id="current_password" class="form-control" type="password" name="current_password" autocomplete="current-password" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password baru</label>
                    <input id="password" class="form-control" type="password" name="password" autocomplete="new-password" minlength="12" required aria-describedby="password-help">
                    <div id="password-help" class="form-text">Minimal 12 karakter, dengan huruf besar, huruf kecil dan angka.</div>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Ulangi password baru</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password" minlength="12" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Simpan Password</button>
            </form>
            @unless ($forced)
                <a class="btn btn-link mt-2 w-100" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
            @endunless
            <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf<button type="submit" class="btn btn-outline-secondary w-100">Keluar</button></form>
        </div>
    </div>
@endsection
