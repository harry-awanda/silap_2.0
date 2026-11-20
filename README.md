# SILAP v2.0

Rebuild SILAP untuk menyederhanakan administrasi siswa, penempatan akademik, presensi, pembinaan, dan operasional piket.

Root Laravel lokal: **D:.Learningsilap_v2.0**. Repository: [harry-awanda/silap_2.0](https://github.com/harry-awanda/silap_2.0), branch main. Nama folder lokal tetap silap_v2.0 sesuai persiapan awal.

**Status 20 November 2026: tahap 1A selesai — skeleton Laravel dapat dijalankan langsung dari root, database pengembangan v2 terpisah, dan halaman awal memakai template SILAP.** Autentikasi, sembilan role, dashboard operasional dan fitur bisnis belum diimplementasikan. Tahap 1 keseluruhan belum selesai.

## Fondasi yang tersedia

- Laravel 13.43.0, PHP minimum 8.3; versi dependency pasti ada dalam composer.lock.
- Resolusi dependency memakai platform PHP 8.3.0 agar tidak mengunci paket khusus PHP 8.5 hanya karena runtime lokal memakai PHP 8.5.
- Blade dengan CSS Bootstrap/Sneat dan Boxicons dari SILAP lama. Aset minimum di public/assets; referensi font memakai WOFF2 lokal.
- Vite 8 dengan lockfile npm; tanpa Tailwind atau starter kit UI lain.
- Locale id, fallback en, timezone Asia/Jakarta.
- SQLite khusus proyek pada database/database.sqlite untuk pengembangan awal. Database produksi belum dipilih.
- Migration infrastruktur bawaan Laravel untuk users/sessions, cache dan jobs. Schema autentikasi/role akan disesuaikan pada tahap 1B; belum ada tabel bisnis.
- Seeder tidak membuat akun contoh. Login dan akun superadmin awal belum tersedia.
- Route publik / untuk halaman persiapan dan /up untuk kesehatan aplikasi.
- Lima test fondasi dengan SQLite in-memory; tidak memakai database operasional.
- plan/, .env, database SQLite lokal, vendor, node_modules dan hasil build tidak di-track Git.

Sumber template: D:.Learningsilappublicassets. Hanya CSS dan font yang diperlukan disalin; foto siswa, storage dan konfigurasi environment lama tidak disalin. Lihat public/assets/NOTICE.md.

## Keputusan workflow

- Database baru dan kosong; tidak mengimpor database operasional SILAP lama.
- Sembilan role dipertahankan dalam rancangan: superadmin, admin, tata_usaha, guru, wali_kelas, guru_piket, guru_bk, kesiswaan, siswa.
- Keterlambatan hanya diinput role guru_piket, tanpa QR keterlambatan dan tanpa login/HP/internet/GPS siswa. Guru piket memakai sesi dan koneksi aplikasinya sendiri.
- Input keterlambatan: cari nama/NIS/filter kelas → pilih siswa → periksa tanggal/jam default → alasan opsional → simpan. Waktu datang dapat disesuaikan bila input tertunda; actor dan waktu pencatatan server tetap diaudit.
- Record presensi yang sudah ada tidak ditimpa otomatis; gunakan koreksi beralasan. Admin/superadmin tanpa guru_piket tidak mendapat bypass input keterlambatan.
- QR pribadi/Assetly dibahas terpisah. Presensi hadir mandiri tetap bagian rancangan.
- Manajemen Kepegawaian tidak dibangun. Guru minimal untuk wali/piket dan catatan KBM tetap diperlukan; rincian batas HR lainnya masih mengikuti tinjauan.
- Lifecycle siswa dan laporan dirancang menjaga histori; kelulusan tidak menghapus siswa beserta riwayat.
- Implementasi bertahap; rincian kewenangan yang belum diputuskan tetap merupakan rekomendasi untuk ditinjau.

## Kebutuhan lokal

- PHP 8.3 atau lebih baru; teruji pada PHP 8.5.12.
- Composer 2; gunakan versi yang kompatibel dengan PHP Anda.
- Node.js ^20.19.0 atau >=22.12.0 sesuai Vite yang terpasang; teruji pada Node 24.16.0 dan npm 11.13.0.
- Ekstensi PHP: PDO/pdo_sqlite untuk database lokal, mbstring, openssl, fileinfo, ctype, tokenizer, dom, xml, xmlwriter dan ekstensi standar framework.
- zip disarankan untuk ekstraksi dependency Composer dan akan dibutuhkan modul Excel/ZIP pada tahap berikutnya.

PHP lokal menyediakan php_zip.dll tetapi zip belum aktif pada php.ini. Instalasi dependency sesi ini menjalankan PHP dengan opsi -d extension=zip tanpa mengubah konfigurasi global. Jika Composer gagal mengekstrak arsip, aktifkan extension=zip pada php.ini CLI yang ditampilkan oleh php --ini, atau gunakan opsi tersebut untuk memanggil composer.phar.

Composer lokal 2.7.7 menghasilkan deprecation notice pada PHP 8.5; instalasi tetap berhasil. Perbarui Composer sebelum memakai kombinasi ini untuk pekerjaan rutin. Tidak ada pengabaian platform requirement dalam instalasi proyek.

## Instalasi lokal dari GitHub

PowerShell:

    git clone https://github.com/harry-awanda/silap_2.0.git silap_v2.0
    Set-Location silap_v2.0
    composer install
    npm ci
    Copy-Item .env.example .env
    php artisan key:generate
    New-Item -ItemType File -Path database/database.sqlite
    php artisan migrate --seed
    npm run build
    php artisan serve --host=127.0.0.1 --port=8000

Buka http://127.0.0.1:8000. Endpoint kesehatan: http://127.0.0.1:8000/up.

Jika folder lokal sudah disiapkan pada sesi ini, .env, APP_KEY, dependency, database dan build sudah tersedia; cukup jalankan php artisan serve. Jangan menimpa .env atau database yang sudah ada ketika mengikuti ulang panduan.

Untuk Composer XAMPP pada komputer ini bila zip belum aktif:

    php -d extension=zip C:ProgramDataComposerSetupincomposer.phar install

Alternatif setup awal pada database kosong: salin .env.example ke .env, lalu composer run setup. Script menyiapkan key, membuat file SQLite bila belum ada, menjalankan migration, memasang dependency npm dari lockfile, dan build aset. Gunakan pada proyek baru; key:generate bukan perintah pemeliharaan rutin untuk aplikasi yang sudah digunakan.

Belum ada akun login setelah migrate --seed. Seeder role dan superadmin akan ditambahkan pada tahap 1B.

## Pengembangan frontend

Setelah build, halaman dapat dibuka tanpa server Vite. Untuk perubahan CSS/JS saat development, jalankan npm run dev pada terminal kedua sementara php artisan serve tetap aktif. composer run dev menjalankan server PHP; jalankan Vite terpisah bila diperlukan.

Jika muncul Vite manifest not found, jalankan npm ci dan npm run build. Jika database file belum ada, buat database/database.sqlite lalu jalankan migration. Jika port 8000 dipakai aplikasi lain, pilih port lain pada artisan serve dan sesuaikan APP_URL bila diperlukan.

## Konfigurasi

| Key | Default pengembangan |
| --- | --- |
| APP_NAME | SILAP v2.0 |
| APP_URL | http://127.0.0.1:8000 |
| APP_TIMEZONE | Asia/Jakarta |
| APP_LOCALE / APP_FALLBACK_LOCALE | id / en |
| DB_CONNECTION | sqlite |
| SESSION_DRIVER / CACHE_STORE | database / database |
| QUEUE_CONNECTION | database; belum ada background job bisnis |

SQLite default berada di database/database.sqlite. File database dan .env bersifat lokal, tidak ikut push. Jangan arahkan DB_DATABASE/DB_URL ke database SILAP lama.

## Validasi

Perintah yang dipakai:

    php artisan test --compact
    php vendor/bin/pint --test
    composer validate --strict
    composer check-platform-reqs
    php artisan migrate:status
    php artisan route:list
    npm run build

Hasil tahap 1A: 5 test lulus (11 assertion), formatter lulus, manifest Composer valid, platform requirements terpenuhi, 3 migration berhasil, dan build Vite berhasil. HTTP halaman utama, kesehatan, seluruh CSS/font template yang dipakai, dan aset build merespons 200.

Pengujian mencakup halaman publik, endpoint kesehatan, locale/timezone, seeder tanpa akun contoh, serta rollback dan migrate ulang pada SQLite in-memory. Belum ada uji browser visual, performa produksi, atau pengujian fitur bisnis.

## Progres bertahap

| Tahap | Hasil | Status |
| --- | --- | --- |
| 0 | Analisis workflow, role dan audit schema | Draft tersedia; tinjauan keseluruhan masih berjalan |
| 1A | Root Laravel, environment, database lokal, template awal dan pengujian | Selesai |
| 1B | Auth username, sembilan role, akun nonaktif/reset, dashboard sesuai akses dan CI | Berikutnya |
| 2 | Periode, kelas, guru minimal dan penugasan wali | Belum dimulai |
| 3 | Siswa, akun, import/export, foto dan enrollment | Belum dimulai |
| 4 | Presensi hadir, keterlambatan khusus guru piket tanpa QR, audit koreksi | Belum dimulai |
| 5 | Promosi, pindah kelas, mutasi, kelulusan dan pembatalan aman | Belum dimulai |
| 6 | Pembinaan, kegiatan pagi, agenda piket | Belum dimulai |
| 7 | Laporan, struktur sekolah, media, QR pribadi/Assetly bila diperlukan | Belum dimulai |
| 8 | Penerimaan dan deployment | Belum dimulai |

Draft modular ada di plan/00-index.md pada workspace lokal. plan/ sengaja diabaikan Git dan tidak tersedia di hasil clone; keputusan/progres penting tetap diringkas di README dan CHANGELOG.

## Ringkasan analisis lama

Analisis sumber SILAP pada commit ceebec6 beserta perubahan lokal menemukan penempatan ganda, histori yang dapat tersembunyi setelah promosi/mutasi, penghapusan histori saat kelulusan, aturan presensi yang berbeda antarjalur, dan route resource tanpa method.

Baseline sumber lama: 46 migration, 53 controller, 224 route; 21 action tanpa method berdasarkan pemeriksaan statis. Dari 21 test lama, 2 error karena ZipArchive tidak tersedia. Hasil tersebut berbeda dari test fondasi v2 yang dijelaskan di atas.

Rincian biodata siswa sudah dipakai form/import/export; tidak langsung dianggap tidak terpakai. Tabel face recognition, izin keluar dan alumni lama tidak disalin tanpa kebutuhan fitur yang dikonfirmasi.

## Disiplin perubahan

Setiap perubahan minor/major dicatat dalam CHANGELOG pada sesi yang sama. README diperbarui mengikuti progres, konfigurasi dan instalasi yang sudah diuji. Schema berikutnya dirancang per domain; tidak menyalin 46 migration lama.

Tidak ada migrasi data lama atau deployment produksi. Pilihan Laravel 13 mengacu [support policy resmi](https://laravel.com/framework/docs/13.x/releases); kompatibilitas paket fitur tambahan diperiksa saat modul tersebut dibangun.
