# SILAP v2.0

Rebuild SILAP untuk menyederhanakan administrasi siswa, penempatan akademik, presensi, pembinaan, dan operasional piket.

Root Laravel lokal: **D:\.Learning\silap_v2.0**. Repository: [harry-awanda/silap_2.0](https://github.com/harry-awanda/silap_2.0), branch main. Nama folder lokal tetap silap_v2.0 sesuai persiapan awal.

**Status 20 November 2026: tahap 1B selesai dan CI lulus.** Login username, sembilan role tetap, superadmin awal, wajib ganti/reset password, akun nonaktif, dashboard multi-role dan pengelolaan akun tersedia. CI implementasi 58d4842 lulus pada seluruh job SQLite/PHP 8.3+8.5, MySQL, formatter dan build frontend. Fitur akademik/presensi/pembinaan belum dibangun.

## Fondasi yang tersedia

- Laravel 13.43.0, PHP minimum 8.3; versi dependency pasti ada dalam composer.lock.
- Resolusi dependency memakai platform PHP 8.3.0 agar tidak mengunci paket khusus PHP 8.5 hanya karena runtime lokal memakai PHP 8.5.
- Blade dengan CSS Bootstrap/Sneat dan Boxicons dari SILAP lama. Aset minimum di public/assets; referensi font memakai WOFF2 lokal.
- Vite 8 dengan lockfile npm; tanpa Tailwind atau starter kit UI lain.
- Locale id, fallback en, timezone Asia/Jakarta.
- MySQL menjadi database pengembangan sesuai konfigurasi pengguna. Koneksi lokal silap_db terverifikasi dan seluruh migration fondasi sudah diterapkan. SQLite hanya dipakai untuk test in-memory; konfigurasi hosting produksi belum ditetapkan.
- Enam migration: infrastruktur awal dan tambahan autentikasi, tabel Spatie Permission 8.5.0 serta account_audits. Username unik wajib, email opsional, status nonaktif dan penggantian password tercatat. Kolom verifikasi email/tabel reset via email yang tidak dipakai dikeluarkan lewat migration tambahan.
- Seeder membuat sembilan role secara idempotent tanpa akun contoh/password default. Superadmin awal dibuat melalui CLI atau konfigurasi bootstrap opsional.
- Route / mengarah ke /login atau /dashboard; /up tetap untuk kesehatan. Dashboard, ganti password dan pengelolaan akun dilindungi auth, status akun dan pemeriksaan sesi.
- 34 test autentikasi/akun/fondasi, teruji pada SQLite in-memory dan MySQL silap_test yang terisolasi; tidak mereset database aplikasi.
- plan/, .env, database SQLite lokal, vendor, node_modules dan hasil build tidak di-track Git.

Sumber template: D:\.Learning\silap\public\assets. Pada fondasi awal baru CSS/font minimum yang disalin; lihat public/assets/NOTICE.md.

Keputusan UI terbaru: gunakan bundle Sneat yang sudah tersedia dengan menyalin seluruh isi public/assets SILAP lama ke public/assets v2, termasuk css, js, vendor dan img. Blade diimplementasikan ulang memakai struktur HTML/layout/komponen template yang ada, lalu disesuaikan dengan route, data dan policy v2. Tidak perlu membuat ulang desain atau replika CSS/JS Sneat.

Integrasi bundle lengkap dan penyesuaian Blade menjadi paket UI berikutnya; belum dilakukan pada perubahan dokumentasi ini. Vendor yang tersedia tetap dimuat sesuai kebutuhan halaman, dengan urutan dependensi template dan tanpa duplikasi melalui Vite.

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
- Ekstensi PHP: PDO/pdo_mysql untuk MySQL lokal, pdo_sqlite untuk pengujian in-memory, mbstring, openssl, fileinfo, ctype, tokenizer, dom, xml, xmlwriter dan ekstensi standar framework.
- zip disarankan untuk ekstraksi dependency Composer dan akan dibutuhkan modul Excel/ZIP pada tahap berikutnya.

PHP lokal menyediakan php_zip.dll tetapi zip belum aktif pada php.ini. Instalasi dependency sesi ini menjalankan PHP dengan opsi -d extension=zip tanpa mengubah konfigurasi global. Jika Composer gagal mengekstrak arsip, aktifkan extension=zip pada php.ini CLI yang ditampilkan oleh php --ini, atau gunakan opsi tersebut untuk memanggil composer.phar.

Composer lokal 2.7.7 menghasilkan deprecation notice pada PHP 8.5; instalasi tetap berhasil. Perbarui Composer sebelum memakai kombinasi ini untuk pekerjaan rutin. Tidak ada pengabaian platform requirement dalam instalasi proyek.

## Instalasi lokal dari GitHub

Siapkan database MySQL khusus v2 terlebih dahulu. Setelah menyalin .env.example, sesuaikan DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME dan DB_PASSWORD dengan MySQL lokal sebelum menjalankan migration. Contoh .env.example memakai silap_db; jangan memakai database SILAP lama.

PowerShell:

    git clone https://github.com/harry-awanda/silap_2.0.git silap_v2.0
    Set-Location silap_v2.0
    composer install
    npm ci
    Copy-Item .env.example .env
    php artisan key:generate
    php artisan migrate --seed
    npm run build
    php artisan serve --host=127.0.0.1 --port=8000

Buka http://127.0.0.1:8000. Endpoint kesehatan: http://127.0.0.1:8000/up.

Jika folder lokal sudah disiapkan pada sesi ini, .env, APP_KEY, dependency, database dan build sudah tersedia; cukup jalankan php artisan serve. Jangan menimpa .env atau database yang sudah ada ketika mengikuti ulang panduan.

Untuk Composer XAMPP pada komputer ini bila zip belum aktif:

    php -d extension=zip C:\ProgramData\ComposerSetup\bin\composer.phar install

Alternatif setup awal pada database kosong: salin .env.example ke .env, lalu composer run setup. Pastikan database MySQL sudah tersedia dan koneksi .env benar. Script menyiapkan key, menjalankan migration pada koneksi tersebut, memasang dependency npm dari lockfile, dan build aset; tidak membuat file SQLite. Gunakan pada proyek baru; key:generate bukan perintah pemeliharaan rutin untuk aplikasi yang sudah digunakan.

## Akun superadmin awal

Pada clone baru, migrate --seed membuat sembilan role tanpa password bawaan. Buat superadmin pertama dari terminal:

    php artisan silap:superadmin

Masukkan dan ulangi password saat diminta; input tidak ditampilkan. Opsi identitas tersedia: --username=superadmin, --name=Superadmin, --email=alamat@example.com. Alternatif --generate-password membuat password acak yang hanya dicetak oleh CLI; jangan menyalin hasilnya ke log/repository.

Pada workspace lokal sesi pengembangan, akun superadmin awal sudah dibuat dengan username superadmin dan password sementara yang disampaikan terpisah. Password tidak ditulis dalam README/CHANGELOG/.env.example. Login pertama wajib menggantinya sebelum membuka dashboard.

Bootstrap menolak membuat superadmin kedua jika sudah ada superadmin aktif dan tidak mengambil alih username akun lain. Untuk akun administrator berikutnya, gunakan Pengelolaan Akun setelah login.

Alternatif instalasi otomatis: isi SUPERADMIN_USERNAME/NAME/EMAIL/PASSWORD di .env lokal lalu jalankan db:seed. Password wajib memenuhi aturan dan hanya dipakai untuk pembuatan awal. Seeder berulang tidak mereset password yang sudah ada. Konfigurasi password bootstrap dapat dikosongkan setelah akun dibuat.

## Penggunaan autentikasi dan akun

- Login menggunakan username (dinormalisasi menjadi huruf kecil), bukan email. Percobaan gagal dibatasi 5 per menit per username/IP.
- Semua pengguna dapat mengganti password sendiri dengan password saat ini; password baru minimal 12 karakter dengan huruf besar/kecil dan angka.
- Akun baru/reset harus mengganti password dahulu. Halaman ganti password dan logout tetap dapat diakses selama kewajiban ini.
- Admin dan superadmin dapat membuat/mengubah akun serta menetapkan beberapa role. Admin tidak boleh memberi/mengubah akun superadmin.
- Reset menghasilkan password sementara acak; hanya ditampilkan pada kunjungan berikutnya dan flash sesi menyimpannya terenkripsi. Berikan secara pribadi kepada pemilik akun.
- Penonaktifan, reset password dan perubahan role mencabut sesi database akun terkait serta memutar token remember-me. Perubahan password sendiri mempertahankan sesi saat ini dan mencabut sesi lain; perubahan role sendiri mengharuskan login ulang.
- Tidak ada penghapusan akun atau CRUD rename/delete role inti. Admin tidak dapat menonaktifkan diri atau mencabut role admin/superadmin miliknya; minimal satu administrator dan satu superadmin aktif dipertahankan.
- Perubahan akun dicatat di account_audits tanpa password/hash. Pembacaan audit melalui UI belum tersedia.
- Dashboard menggabungkan semua role pengguna dan tidak mengarahkan wali tanpa assignment ke halaman terlarang. Menu hanya menautkan fitur yang sudah dibangun.
- Kapabilitas input keterlambatan tetap khusus guru_piket tanpa bypass superadmin; endpoint bisnis keterlambatan belum dibangun.

Sesi aplikasi menggunakan SESSION_DRIVER=database. Jangan menggantinya tanpa menyesuaikan/menguji mekanisme pencabutan sesi pada layanan akun.

## Pengembangan frontend

Setelah build, halaman dapat dibuka tanpa server Vite. Untuk perubahan CSS/JS saat development, jalankan npm run dev pada terminal kedua sementara php artisan serve tetap aktif. composer run dev menjalankan server PHP; jalankan Vite terpisah bila diperlukan.

Jika muncul Vite manifest not found, jalankan npm ci dan npm run build. Setelah mengubah .env, muat ulang server pengembangan agar tidak memakai driver lama. Jika MySQL menolak koneksi, pastikan server aktif, database tersedia dan kredensial .env sesuai; jalankan php artisan config:clear jika ada cache konfigurasi, lalu php artisan migrate:status. Jika port 8000 dipakai aplikasi lain, pilih port lain pada artisan serve dan sesuaikan APP_URL bila diperlukan.

## Konfigurasi

| Key | Default pengembangan |
| --- | --- |
| APP_NAME | SILAP v2.0 |
| APP_URL | http://127.0.0.1:8000 |
| APP_TIMEZONE | Asia/Jakarta |
| APP_LOCALE / APP_FALLBACK_LOCALE | id / en |
| DB_CONNECTION | mysql |
| DB_HOST / DB_PORT | 127.0.0.1 / 3306 (contoh; sesuaikan lokal) |
| DB_DATABASE | silap_db (contoh database khusus v2) |
| SESSION_DRIVER / CACHE_STORE | database / database |
| QUEUE_CONNECTION | database; belum ada background job bisnis |

Konfigurasi MySQL aktual tetap pada .env lokal dan tidak ikut push. SQLite awal di database/database.sqlite tidak lagi dipakai oleh aplikasi dan tetap diabaikan Git. Test memaksa DB_CONNECTION=sqlite, DB_DATABASE=:memory: dan DB_URL kosong agar tidak menjalankan migration/rollback pada MySQL. Jangan arahkan DB_DATABASE/DB_URL ke database SILAP lama.

## Validasi

Perintah yang dipakai:

    php artisan test --compact
    php vendor/bin/pint --test
    composer validate --strict
    composer check-platform-reqs
    php artisan migrate:status
    php artisan route:list
    npm run build

Hasil terbaru tahap 1B: 34 test lulus dengan 188 assertion pada SQLite in-memory dan 34 test/188 assertion pada MySQL silap_test. Pint, validasi manifest Composer dan build Vite diperiksa sebelum commit. Enam migration sudah diterapkan pada MySQL aplikasi tanpa reset database.

Pengujian mencakup login/logout, pembatasan percobaan, password wajib, sesi lama, batas admin/superadmin, multi-role, bootstrap idempotent, filter/escaping UI dan migration ulang. Login, dashboard dan daftar akun diperiksa dengan Chrome headless pada viewport 1366x900 dan 390x844; sidebar mobile disesuaikan dan tidak ada overflow halaman. Fixture browser hanya pada database test dan sudah dibersihkan. Belum ada pengujian fitur bisnis/performa produksi.

Peralihan ke MySQL: koneksi schema silap_db terverifikasi kosong sebelum migration; 3 migration fondasi diterapkan tanpa reset/fresh/rollback pada MySQL. Test tetap berjalan pada SQLite in-memory.

### Pengujian MySQL dan CI

Default php artisan test selalu memakai SQLite :memory:. Untuk pengujian MySQL, buat database silap_test khusus pengujian, gunakan kredensial lokal yang memiliki akses ke database itu, lalu:

    php vendor/bin/phpunit --configuration phpunit.mysql.xml

Konfigurasi tersebut memaksa nama database silap_test. TestCase menolak database selain SQLite :memory: atau MySQL silap_test sebelum RefreshDatabase berjalan. Isi database test dapat dihapus/migrasi ulang; jangan menaruh data operasional di sana.

Workflow .github/workflows/ci.yml dijalankan pada push main dan pull request: test/format pada PHP 8.3 dan 8.5 dengan SQLite, regresi MySQL 8 pada service terpisah, serta build frontend pada Node 24. Kredensial service CI hanya berlaku pada container test sementara, bukan database lokal.

Bukti CI implementasi: [Laravel CI untuk commit 58d4842](https://github.com/harry-awanda/silap_2.0/actions/runs/44714647355), seluruh tiga job sukses.

## Progres bertahap

| Tahap | Hasil | Status |
| --- | --- | --- |
| 0 | Analisis workflow, role dan audit schema | Draft tersedia; tinjauan keseluruhan masih berjalan |
| 1A | Root Laravel, environment, database lokal, template awal dan pengujian | Selesai |
| 1B | Auth username, sembilan role, akun nonaktif/reset, dashboard sesuai akses dan CI | Selesai; CI lulus |
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
