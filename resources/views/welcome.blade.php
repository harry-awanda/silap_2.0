@extends('layouts.app')

@section('title', 'SILAP — Layanan Administrasi Sekolah')

@section('content')
    <div class="card">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="silap-brand-mark" aria-hidden="true"><i class="bx bxs-school"></i></span>
                <div>
                    <h1 class="h3 mb-1">SILAP</h1>
                    <p class="mb-0 text-muted">Layanan Administrasi Sekolah</p>
                </div>
            </div>
            <span class="badge bg-label-primary mb-3">Dalam persiapan</span>
            <h2 class="h4">Administrasi sekolah yang lebih sederhana.</h2>
            <p class="mb-0">
                Layanan sedang disiapkan dan akan tersedia secara bertahap untuk mendukung
                administrasi siswa, presensi, dan pembinaan.
            </p>
        </div>
    </div>
    <footer class="text-center text-muted small mt-4">
        SILAP &copy; {{ now()->year }}
    </footer>
@endsection
