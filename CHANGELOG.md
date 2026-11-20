# Changelog

Semua perubahan minor, major, dan dokumentasi dicatat di sini. Tanggal mengikuti Asia/Jakarta. Versi aplikasi pertama belum dirilis.

## [Unreleased]

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
