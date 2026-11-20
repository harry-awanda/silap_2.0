# Changelog

Semua perubahan minor, major, dan dokumentasi dicatat di sini. Tanggal mengikuti Asia/Jakarta. Versi aplikasi pertama belum dirilis.

## [Unreleased]

### 2026-11-20 — Tahap 1A: root Laravel dan fondasi aplikasi

Added:
- Skeleton resmi Laravel langsung pada root silap_v2.0: artisan, app, bootstrap, config, database, routes, resources, public, storage dan tests.
- Laravel Framework 13.43.0 dengan composer.lock; platform dependency PHP 8.3.0, teruji menggunakan runtime PHP 8.5.12.
- Environment contoh SILAP, locale id/fallback en dan timezone Asia/Jakarta; database SQLite pengembangan terpisah pada database/database.sqlite.
- Layout Blade dan halaman persiapan menggunakan CSS Bootstrap/Sneat serta Boxicons dari SILAP lama. NOTICE mencatat asal aset; font mirror diperbaiki ke WOFF2 lokal.
- Vite 8.3.8, package-lock.json dan build CSS/JS tanpa Tailwind.
- Lima test fondasi untuk halaman awal, kesehatan, konfigurasi, seeder kosong, serta rollback/migrate ulang pada database in-memory.

Changed:
- README kini berisi deskripsi kondisi aplikasi aktual, kebutuhan runtime, instalasi teruji, cara menjalankan, troubleshooting dan progres 1A/1B.
- .gitignore melindungi plan/, .env, database SQLite/journal, dependency, log dan artefak build.
- Seeder bawaan tidak membuat akun Test User. Paket dev Pail/Pao dan frontend yang belum digunakan tidak disertakan; composer run dev menjalankan server PHP.
- Script setup memakai lockfile npm dan membuat file SQLite bila belum ada; script test kompatibel dengan Composer lokal.

Validasi:
- Dependency PHP dan npm terpasang; platform requirements terpenuhi dan composer validate --strict lulus.
- 3 migration infrastruktur berhasil; seeder tidak membuat akun contoh.
- php artisan test --compact: 5 passed, 11 assertions.
- Pint lulus setelah normalisasi line ending pada tiga file baru; npm run build berhasil.
- Smoke HTTP: /, /up, CSS template, Boxicons WOFF2 dan aset build merespons 200.
- Tidak ada dependency bisnis Excel/PDF/permission yang dipasang sebelum modulnya dibangun.

Catatan environment:
- Composer 2.7.7 lokal mengeluarkan deprecation notice pada PHP 8.5; instalasi berhasil.
- Ekstensi zip dimuat hanya untuk proses Composer melalui php -d extension=zip; php.ini global tidak diubah.

Batas hasil:
- Tahap 1 keseluruhan belum selesai: auth username, sembilan role, superadmin awal, dashboard operasional dan CI dilanjutkan pada tahap 1B.
- Belum ada fitur bisnis, QR keterlambatan, HR atau impor database SILAP lama.
- Belum ada pemeriksaan visual browser atau deployment produksi.


### 2026-11-20 — Remote GitHub dan baseline dokumentasi

Changed:
- Menetapkan origin https://github.com/harry-awanda/silap_2.0.git sesuai instruksi pengguna, dengan branch main.
- Mempertahankan folder lokal silap_v2.0; nama remote mengikuti URL terbaru yang diberikan pengguna.
- Memperbarui README dengan lokasi repository, perintah clone ke folder silap_v2.0, dan penjelasan bahwa plan/ tidak tersedia pada GitHub/hasil clone.
- Menyelaraskan indeks dan keputusan lokal D10; menyiapkan commit awal README, CHANGELOG dan .gitignore untuk push.

Validasi:
- git ls-remote berhasil dan tidak menemukan ref pada remote sebelum push awal.
- Daftar file yang disiapkan untuk commit diperiksa agar plan/, environment, data operasional dan sumber aplikasi lama tidak masuk.

Batas hasil:
- Tidak membuat repository GitHub baru atau mengubah visibility; menggunakan repository yang diberikan pengguna.
- Belum ada skeleton aplikasi, dependency, database v2, atau deployment aplikasi.


### 2026-11-20 — Revisi rencana: keterlambatan tanpa QR

Changed:
- Menetapkan keputusan pengguna D09: hanya role guru_piket menginput keterlambatan siswa.
- Menghapus generator/scanner, endpoint form/submit/token, validator dan konfigurasi QR keterlambatan dari rancangan v2.
- Menyederhanakan workflow: cari nama/NIS dengan filter kelas, pilih siswa, tanggal/jam default otomatis yang dapat disesuaikan jika input tertunda, alasan rutin opsional, lalu simpan.
- Pencatatan tidak mensyaratkan akun/login/password, HP, paket internet atau GPS siswa. Guru piket memakai sesi/perangkat/koneksi aplikasi sendiri.
- Mendokumentasikan petugas, waktu kedatangan dan waktu pencatatan server; hasil masuk rekap/laporan, konflik memerlukan koreksi beralasan tanpa penimpaan otomatis.
- Menyelaraskan README, indeks, rekomendasi analisis, matriks akses, workflow target, audit database, arsitektur, tahap implementasi, pengujian, dan daftar keputusan. Workflow lama tetap dipertahankan sebagai bukti historis dengan penanda revisi.
- Memisahkan QR pribadi/Assetly dari keputusan ini; presensi hadir mandiri tetap mengikuti rancangan sebelumnya.

Alasan:
- QR memperlambat pendataan ketika siswa lupa password, tidak membawa HP, atau tidak memiliki paket internet.

Validasi:
- Penelusuran ulang referensi QR pada dokumen target dan pemeriksaan matriks akses khusus guru_piket.
- Pemeriksaan tautan lokal dan aturan ignore plan/. Tidak menjalankan test aplikasi karena perubahan hanya dokumentasi.

Batas hasil:
- Revisi ini mengubah dokumen rencana, bukan kode aplikasi SILAP lama atau implementasi aplikasi v2.


### 2026-11-20 — Tahap 0: analisis dan persiapan workspace

Added:
- Folder proyek silap_v2.0, repository Git lokal dengan branch main, dan draft kerja modular dalam plan/.
- README dengan deskripsi proyek, batas fitur, progres, hasil validasi, serta urutan instalasi yang akan dilengkapi pada tahap fondasi.
- .gitignore yang mengabaikan /plan/, dependency, environment lokal, dan artefak runtime.
- Dokumen lokal: indeks progres, bukti analisis, workflow lama, matriks role, workflow target, audit database, arsitektur/UI, tahapan kerja, skenario uji, daftar keputusan, inventaris migration, serta daftar route dengan method action tidak tersedia.

Keputusan pengguna:
- Database v2 dimulai kosong, tanpa migrasi data operasional lama.
- Semua role dipertahankan; alur kerja boleh dirombak.
- Template UI SILAP lama digunakan kembali.
- Manajemen Kepegawaian tidak diimplementasikan.

Temuan analisis:
- Sumber lama memiliki 46 file migration, 53 controller, dan 224 route terdaftar.
- Role guru ditemukan dalam kode di luar delapan role yang disebut README lama; total sembilan role menjadi baseline.
- Kelulusan lama menghapus siswa dan histori terkait.
- Penempatan ganda dan filter anggota aktif berisiko membuat histori berubah setelah promosi/mutasi.
- Aturan cutoff dan audit koreksi presensi berbeda antarjalur.
- Ada skema tanpa jalur fitur aktif yang ditemukan, tetapi biodata siswa tambahan masih digunakan.
- Pemeriksaan statis menemukan 21 route dengan method controller yang tidak tersedia.

Validasi:
- Route list berhasil.
- PHPUnit sumber lama: 21 test, 75 assertion, 2 error ZipArchive; 19 test selesai tanpa error.
- PHP CLI tidak memuat ekstensi zip; ini hambatan environment untuk dua pengujian Excel.
- Verifikasi struktur dokumen, tautan lokal, dan aturan ignore dilakukan pada akhir tahap.

Batas hasil:
- Belum ada kode aplikasi v2, dependency, database v2, atau aset template yang disalin.
- Rekomendasi alur dan kewenangan masih draft untuk tinjauan pengguna.
- Tidak ada repository GitHub baru, commit, push, atau deploy.
