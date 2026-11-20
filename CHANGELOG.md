# Changelog

Semua perubahan minor, major, dan dokumentasi dicatat di sini. Tanggal mengikuti Asia/Jakarta. Versi aplikasi pertama belum dirilis.

## [Unreleased]

### 2026-11-20 ? Perbaikan kompatibilitas formatter atribut Blade

- Mengganti directive error pada atribut input login/password dengan ekspresi atribut biasa. Pemformatan Blade berulang sebelumnya dapat memecah directive dari argumennya dan menyebabkan HTTP 500.
- Mempertahankan tampilan validasi, aria-invalid dan perilaku autentikasi; pemeriksaan format berulang serta regresi dilakukan kembali setelah perbaikan.


### 2026-11-20 ? Implementasi UI Sneat lengkap

- Menyalin seluruh bundle 685 file public/assets dari SILAP lama; mempertahankan struktur direktori dan perbaikan font Boxicons WOFF2 lokal.
- Memisahkan layout Blade base/auth/app serta sidebar/navbar/footer mengikuti markup Sneat sumber. Menghapus override layout responsif sementara agar sidebar menggunakan perilaku template asli.
- Menerapkan UI login, password awal/biasa, dashboard multi-role, daftar/filter/tambah/ubah akun dan halaman error 403/404/419/429/500/503.
- Memuat helper/config serta vendor inti berurutan; plugin tambahan tersedia tetapi hanya dimuat ketika dibutuhkan. Tidak mengaktifkan customizer demo atau menggandakan vendor melalui Vite.
- Menambahkan komponen password dengan tombol tampil/sembunyikan, skip link, dropdown akun, serta modal konfirmasi reset/nonaktif. Policy, route, pencabutan sesi dan audit tetap menggunakan implementasi yang sudah ada.
- Memperbarui NOTICE aset, README, rencana integrasi Sneat dan progres lokal; coding style tetap 2 spasi dan kurung sejajar.
- Validasi: SQLite dan MySQL terisolasi masing-masing 34 test/188 assertion lulus; kompilasi Blade, build Vite dan pemeriksaan PHP/frontend lulus. Browser desktop/mobile tanpa overflow halaman, error JavaScript atau kegagalan pemuatan aset pada skenario yang diperiksa.
- Browser memeriksa sidebar buka/tutup, dropdown, toggle password, batal/konfirmasi reset, wajib ganti password dan logout. Fixture preview dibersihkan dari silap_test dan server preview dihentikan.

Batas tahap: integrasi UI halaman yang sudah tersedia; modul akademik dan fitur bisnis lainnya belum dibangun.


### 2026-11-20 ? Coding style 2 spasi dan kurung sejajar

- Menerapkan keputusan pengguna D12: indentasi 2 spasi tanpa tab dan kurung pembuka pada baris deklarasi/kondisi, termasuk class, method dan closure.
- Mengganti Pint dengan PHP CS Fixer untuk mengatur indentasi PHP; menambahkan Prettier serta plugin Blade untuk frontend/config.
- Menyelaraskan .editorconfig, konfigurasi formatter, script Composer/npm dan pemeriksaan CI.
- Memformat kode PHP, Blade, CSS, JS, JSON, YAML dan XML PHPUnit yang dikelola proyek. Bundle Sneat asli dan lockfile generator tidak diformat ulang.
- Memperbarui README, daftar keputusan dan draft modular coding style pada plan/ (tetap diabaikan Git).
- Validasi lokal: SQLite dan MySQL terisolasi masing-masing 34 test/188 assertion lulus; kedua pemeriksaan format, Composer validate --strict dan build Vite lulus.

Perubahan ini menetapkan standar kode; integrasi bundle Sneat lengkap tetap paket UI berikutnya.


### 2026-11-20 — Rencana integrasi bundle Sneat lengkap

- Mencatat keputusan pengguna D11: seluruh isi public/assets SILAP lama boleh disalin untuk menggunakan template Sneat yang sudah tersedia.
- Mengganti target penyalinan aset minimum menjadi bundle lengkap dengan struktur css/js/vendor/img tetap; Blade memakai ulang markup/layout template, bukan membangun replika.
- Menetapkan integrasi login, password, dashboard dan akun sebagai paket UI berikutnya sebelum memperluas halaman fitur.
- Menyelaraskan arsitektur UI, tahapan, indeks, daftar keputusan, draft modular integrasi Sneat, dan README.
- Mempertahankan kebutuhan pemuatan vendor per halaman, urutan script, policy/route v2 dan pengujian desktop/mobile.
- Validasi perubahan ini berupa peninjauan konsistensi dokumen, tautan lokal dan aturan ignore; tidak menjalankan ulang test aplikasi karena tidak ada perubahan runtime.

Status: pembaruan rencana saja. Bundle lengkap belum disalin dan Blade runtime belum diubah pada sesi ini.


### 2026-11-20 — Verifikasi CI tahap 1B

- Implementasi 58d4842 sudah di-push; GitHub Actions run 44714647355 menunjukkan seluruh job sukses: sqlite (8.3), sqlite (8.5), dan mysql.
- Pemeriksaan remote mencakup test, formatter, dependency dan build frontend sesuai workflow.
- README serta progres lokal diperbarui dari menunggu hasil CI menjadi lulus. Tidak ada perubahan kode runtime pada pembaruan catatan ini.


### 2026-11-20 — Tahap 1B: autentikasi, role dan pengelolaan akun

Added:
- Login/logout username, pembatasan percobaan gagal, regenerasi sesi, pemeriksaan akun nonaktif dan invalidasi sesi setelah password berubah.
- Spatie Permission 8.5.0, enum/seeder sembilan role tetap dan policy akun tanpa bypass superadmin global.
- Migration tambahan username unik, email opsional, wajib ganti password, timestamp password dan status nonaktif; tabel permission serta audit akun.
- Bootstrap superadmin via CLI dengan prompt tersembunyi atau password acak sekali tampil; konfigurasi env opsional dan seeder idempotent tanpa password bawaan.
- Pengelolaan akun bagi admin/superadmin: tambah/ubah, multi-role, filter/pagination, reset sementara sekali tampil, nonaktif/aktif dan pencabutan sesi.
- Proteksi akun superadmin, akun sendiri dan administrator terakhir, dengan transaksi/lock dan audit yang tidak menyimpan password/hash.
- Dashboard multi-role dan navigasi sesuai policy memakai template SILAP; ganti password, login dan daftar akun responsif.
- Pesan validasi Indonesia, konfigurasi test MySQL terpisah dan guard agar test tidak memakai database aplikasi.
- Workflow CI SQLite/PHP 8.3+8.5, MySQL service, formatter dan build Node 24.

Changed:
- Root sekarang mengarah ke login/dashboard, bukan halaman persiapan.
- Password awal/reset wajib diganti sebelum memakai dashboard/akun; password sendiri diperiksa ulang dalam transaksi agar reset bersamaan tidak tertimpa.
- Flash password sementara disimpan terenkripsi; respons autentikasi/akun tidak di-cache.
- Perubahan role sendiri mengakhiri sesi; session database dan remember token akun terkait dicabut untuk reset/nonaktif/role.
- Lock reset/ganti password dibatasi pada akun terkait; perubahan administrator diserialisasi melalui role inti agar tidak mengunci seluruh tabel users.
- Menghapus kolom email_verified_at dan tabel reset email yang tidak memiliki alur fitur melalui migration baru, tanpa menulis ulang baseline yang sudah terpasang.
- composer setup menjalankan migration dengan seeder; README dan progres modular mengikuti kondisi aktual.

Validasi lokal:
- SQLite :memory:: 34 test lulus, 188 assertion.
- MySQL silap_test: 34 test lulus, 188 assertion; database aplikasi silap_db tidak di-reset.
- Chrome headless: login, dashboard multi-role dan daftar akun diperiksa pada desktop/ponsel; perbaikan override sidebar mencegah konten mobile menyempit.
- Fixture akun/browser memakai silap_test dan dibersihkan; server pratinjau 8001 dihentikan.
- Enam migration aplikasi berstatus Ran; sembilan role dan superadmin awal tersedia secara lokal.
- Password bootstrap, .env, database, screenshot/profil browser, dependency dan plan tidak dimasukkan ke commit.

Batas:
- CI implementasi 58d4842 lulus pada GitHub Actions run 44714647355; rincian tercatat pada verifikasi CI di atas.
- Belum ada fitur akademik, enrollment, presensi, HR atau QR keterlambatan; tahap berikutnya fondasi akademik.
- Tampilan audit akun belum diimplementasikan; rekaman audit sudah tersimpan untuk penelusuran.


### 2026-11-20 — Database pengembangan MySQL

Changed:
- Mengikuti konfigurasi MySQL pada .env yang diperbarui pengguna; .env tidak diubah atau dipublikasikan.
- .env.example dan fallback koneksi database memakai MySQL dengan contoh silap_db.
- Script composer setup tidak lagi membuat file SQLite; database MySQL disiapkan lebih dahulu.
- README dan progres lokal diselaraskan dengan MySQL; SQLite dipertahankan hanya sebagai database test in-memory dan file awal tidak dihapus.
- Memperbaiki penulisan path Windows pada README/NOTICE yang kehilangan pemisah direktori saat penulisan sebelumnya.

Validasi:
- Koneksi efektif MySQL berhasil; schema silap_db dipastikan kosong melalui information_schema sebelum mutation.
- Ketiga migration fondasi diterapkan dengan migrate --seed; migrate:status menunjukkan seluruhnya Ran.
- Tidak menjalankan migrate:fresh, reset, atau rollback pada MySQL. Seeder tidak membuat akun contoh.
- Konfigurasi PHPUnit tetap memaksa SQLite :memory: dan mengosongkan DB_URL; 5 test (11 assertion), Pint dan Composer validate lulus. HTTP / dan /up kembali 200 setelah server pengembangan dimuat ulang.
- Pemeriksaan akhir: koneksi tetap MySQL, migration berjumlah 3 dan akun users tetap 0 setelah test. Test tidak memigrasikan/mereset MySQL.

Batas hasil:
- Perubahan ini menyesuaikan database fondasi; auth/role dan fitur bisnis tetap pekerjaan tahap 1B berikutnya.
- Server pengembangan yang sebelumnya dijalankan oleh sesi agen dimuat ulang karena masih mewarisi driver SQLite; server/layanan lain, konfigurasi PHP global dan database SILAP lama tidak diubah.


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
