# Aset template Sneat / SILAP

Sumber lokal: D:\.Learning\silap\public\assets. Seluruh bundle disalin ke direktori ini pada 20 November 2026 sesuai instruksi pengguna; sumber tidak diubah.

Inventaris sumber: 685 file, 14.827.121 byte, termasuk css, js, vendor dan img. Seluruh path sumber tersedia pada v2. Perbandingan isi menunjukkan hanya vendor/fonts/boxicons.css berbeda: perbaikan WOFF2 lokal dari fondasi v2 dipertahankan karena URL font mirror .html pada sumber tidak valid. File WOFF2 lokal v2 tetap tersedia.

Layout/markup auth dan vertical menu menggunakan kembali pola Blade SILAP lama. Layout memuat core/theme/styles, Boxicons, helpers/config, jQuery, Popper, Bootstrap, Perfect Scrollbar, Hammer, Menu dan main.js dalam urutan dependensinya. page-auth.css hanya dimuat pada autentikasi. DataTables, Select2 dan plugin lain tersedia dalam bundle tetapi tidak dimuat sebelum diperlukan.

Template customizer demo tidak diaktifkan. CSS/JS aplikasi tambahan dibangun melalui Vite; vendor yang sama tidak dibundel ulang. Tidak menyalin database operasional atau storage SILAP lama. Gambar dan aset contoh yang merupakan bagian bundle tetap tersedia sesuai struktur sumber.

Komentar hak cipta/lisensi dalam file sumber dipertahankan. Lisensi proyek tidak menggantikan ketentuan lisensi aset pihak ketiga. Bundle asli dikecualikan dari formatter; sumber template tetap menjadi rujukan pembaruan vendor.
