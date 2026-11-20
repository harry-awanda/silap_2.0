# SILAP v2.0

Rebuild SILAP untuk menyederhanakan administrasi siswa, penempatan akademik, presensi, pembinaan, dan operasional piket. Nama proyek dan folder lokal: **silap_v2.0**. Repository GitHub yang ditetapkan pengguna: [harry-awanda/silap_2.0](https://github.com/harry-awanda/silap_2.0). Folder lokal tetap menggunakan nama semula; nama remote mengikuti URL yang diberikan.

**Status 20 November 2026: tahap 0 — analisis kode dan draft kerja selesai disusun; menunggu tinjauan pengguna. Folder ini belum berisi aplikasi Laravel yang dapat dijalankan.** Repository menggunakan branch main dan remote origin https://github.com/harry-awanda/silap_2.0.git. Baseline Git hanya berisi dokumentasi dan aturan ignore.

## Keputusan dasar

- Database baru dan kosong; tidak mengimpor database operasional SILAP lama.
- Seluruh role yang ditemukan di sumber tetap dipertahankan: superadmin, admin, tata_usaha, guru, wali_kelas, guru_piket, guru_bk, kesiswaan, siswa.
- Alur boleh dirombak. Rincian kewenangan baru masih merupakan rekomendasi untuk ditinjau, kecuali keputusan yang sudah ditetapkan pengguna.
- Keterlambatan hanya diinput oleh pengguna dengan role guru_piket. QR keterlambatan dihapus; siswa tidak perlu login, membawa HP, memiliki paket internet, atau mengirim GPS untuk proses tersebut. Presensi mandiri hadir tetap terpisah.
- UI tetap memakai template Blade/Bootstrap/Sneat dari SILAP lama, termasuk gaya, sidebar, navbar, tabel, dan komponen form.
- Manajemen Kepegawaian tidak dibangun. Mutasi pegawai dan administrasi status pegawai diusulkan ikut dikeluarkan sebagai satu modul HR.
- Data guru minimal untuk akun, wali kelas, jadwal piket, dan catatan KBM tetap diperlukan. Penghapusan HR tidak berarti penghapusan role guru.
- Implementasi bertahap; setiap tahap memiliki hasil yang bisa diperiksa sebelum tahap berikutnya.

## Hasil analisis awal

Sumber: D:\.Learning\silap, commit ceebec6, termasuk perubahan lokal yang belum di-commit saat analisis. Kode lama tidak diperbaiki atau dihapus dalam tahap ini. Analisis tidak membaca isi .env atau data operasional database.

| Bagian | Rekomendasi |
| --- | --- |
| Template UI, autentikasi, multi-role, periode, kelas, penugasan wali | Pertahankan konsep; rapikan implementasi dan otorisasi |
| Import preview–commit, foto massal, laporan Excel/PDF | Pertahankan manfaat; satukan layanan dan validasi |
| Penempatan, promosi, mutasi, kelulusan | Rancang ulang; satu sumber penempatan dan riwayat bertanggal |
| Presensi mandiri hadir, pendataan keterlambatan oleh guru piket, koreksi wali | Tanpa QR keterlambatan; aturan waktu, konflik dan audit konsisten |
| Pelanggaran/pembinaan | Satu kasus dengan tindak lanjut dan histori |
| Kepegawaian | Keluarkan dari versi baru |
| Face recognition, izin keluar, tabel alumni lama | Jangan salin tanpa kebutuhan fitur aktif yang terbukti |

Temuan prioritas: kelulusan lama menghapus siswa beserta histori; status hadir pada jalur mandiri tidak dihitung dari cutoff; sebagian koreksi tidak diaudit; laporan historis bergantung pada keanggotaan siswa saat ini; beberapa route masih menunjuk method yang tidak tersedia.

Biodata tambahan siswa sudah digunakan oleh form, import, dan export. Kolom tersebut tidak otomatis dianggap tidak terpakai. Audit membedakan penggunaan kode dari kebutuhan operasional yang masih perlu dikonfirmasi.

## Pendataan keterlambatan — keputusan revisi

Guru piket membuka Pendataan Keterlambatan → mencari siswa berdasarkan nama/NIS dengan filter kelas → memilih siswa → memeriksa tanggal/jam kedatangan yang terisi otomatis → menambahkan alasan bila tersedia → menyimpan. Jam kedatangan dapat disesuaikan jika pencatatan tertunda; waktu pencatatan server dan petugas tetap tersimpan untuk audit.

Hasil masuk ke rekap presensi dan laporan keterlambatan. Presensi yang sudah ada menampilkan konflik dan memerlukan tindakan koreksi beralasan, bukan ditimpa otomatis. Endpoint input memeriksa role guru_piket; akun admin/superadmin tanpa role tersebut tidak mendapat pengecualian. Presensi mandiri setelah cutoff mengarahkan siswa melapor ke guru piket. QR pribadi/Assetly tidak termasuk penghapusan ini.

## Rencana bertahap

| Tahap | Hasil yang ditinjau | Status |
| --- | --- | --- |
| 0 | Analisis, workflow, audit skema, draft modular | Dokumen tersedia; tinjauan pengguna belum dilakukan |
| 1 | Fondasi Laravel, template UI, auth, role, konfigurasi, CI dasar | Belum dimulai |
| 2 | Periode, kelas, guru minimal, penugasan wali | Belum dimulai |
| 3 | Siswa, akun, import/export, foto, penempatan | Belum dimulai |
| 4 | Presensi hadir, input keterlambatan khusus guru piket tanpa QR, koreksi dengan audit | Belum dimulai |
| 5 | Promosi, pindah kelas, mutasi, kelulusan dan pembatalan aman | Belum dimulai |
| 6 | Pembinaan, kegiatan pagi, agenda piket | Belum dimulai |
| 7 | Laporan terpadu, struktur sekolah, media, QR pribadi/Assetly bila diperlukan | Belum dimulai |
| 8 | Uji penerimaan dan persiapan deployment | Belum dimulai |

Draft kerja lokal ada di berkas plan/00-index.md. Folder plan sengaja diabaikan Git sesuai permintaan dan tidak tersedia di GitHub atau hasil clone. Ringkasan keputusan dan progres penting harus tetap disalin ke README dan CHANGELOG supaya repository tetap dapat dipahami tanpa folder plan.

## Instalasi — kondisi saat ini

Saat ini cukup buka folder proyek dan baca dokumen. Jangan menjalankan composer install, artisan migrate, atau npm build di folder ini: manifest dan aplikasi belum dibuat.

Untuk melanjutkan persiapan lokal:

1. Buka PowerShell di folder D:\.Learning\silap_v2.0.
2. Baca README, CHANGELOG, lalu plan/00-index.md.
3. Tinjau pembagian kewenangan, batas HR, alur presensi, dan data siswa pada daftar keputusan.
4. Implementasikan tahap 1 setelah hasil analisis ditinjau. Jangan menggunakan database SILAP lama sebagai database v2.

## Panduan instalasi aplikasi — draft untuk tahap 1

Bagian ini adalah urutan yang harus dilengkapi dan diuji setelah skeleton aplikasi tersedia, bukan langkah instalasi yang sudah tervalidasi.

1. Tetapkan versi Laravel/PHP yang masih didukung dan cocok dengan paket Excel, PDF, permission, serta DataTables. Catat versi pasti dan commit lockfile.
2. Siapkan PHP, Composer, database, Node.js/npm bila aset memakai Vite. Aktifkan ekstensi yang diperlukan, terutama zip, fileinfo, mbstring, XML dan PDO driver database.
3. Clone repository dengan perintah berikut; argumen folder mempertahankan nama lokal silap_v2.0:

   git clone https://github.com/harry-awanda/silap_2.0.git silap_v2.0
   cd silap_v2.0

   Hasil clone tahap ini hanya berisi README, CHANGELOG, dan .gitignore; lanjutkan langkah dependency setelah skeleton aplikasi tersedia.
4. Jalankan composer install dan npm ci jika package-lock.json sudah disediakan.
5. Salin .env.example menjadi .env; atur APP_NAME, APP_URL, APP_TIMEZONE=Asia/Jakarta dan koneksi database khusus v2.
6. Generate APP_KEY. Buat database v2 kosong, lalu jalankan migration dan seeder role serta superadmin awal.
7. Konfigurasi penyimpanan media. File privat menggunakan endpoint yang memeriksa hak akses; aset template tetap publik.
8. Build aset, jalankan aplikasi, lalu uji login dan setup periode dari database kosong.
9. README harus diperbarui dengan perintah yang benar-benar sudah diuji, kebutuhan ekstensi, seed akun awal, dan troubleshooting.

Pemilihan versi framework dan database belum dikunci pada tahap analisis. Rekomendasi arsitektur: monolit modular Laravel + Blade, satu database lokal aplikasi, tanpa layanan master terpisah. Tidak ada alasan dari analisis ini untuk menghidupkan kembali dependensi silap-master.

## Validasi sumber lama

- php artisan route:list --except-vendor --json: berhasil, 224 route.
- Pemeriksaan statis method action: 21 route mengarah ke method yang tidak ditemukan pada controller terkait.
- php vendor/phpunit/phpunit/phpunit --do-not-cache-result: 21 test, 75 assertion, 2 error karena ZipArchive tidak tersedia; 19 test selesai tanpa error.
- Runtime CLI yang dipakai: PHP 8.5.12. Hasil ini bukan bukti kompatibilitas deployment v2.
- Belum dilakukan pengujian UI melalui browser, profiling beban, inspeksi database aktual, atau uji integrasi Assetly.
- Aplikasi v2 belum dibuat, sehingga belum ada test runtime v2.

## Disiplin perubahan

Setiap perubahan minor maupun major harus dicatat dalam CHANGELOG.md pada sesi yang sama: apa yang berubah, alasan, dampak, dan cara validasi. README diperbarui jika progres, dependensi, konfigurasi, instalasi, atau perilaku aplikasi berubah. Perubahan dokumen plan lokal juga diringkas dalam CHANGELOG meskipun file plan tidak di-track.

Tidak ada migrasi data lama atau deployment aplikasi dalam tahap 0. Sinkronisasi GitHub pada tahap ini hanya untuk baseline dokumentasi; plan/ tetap lokal.
