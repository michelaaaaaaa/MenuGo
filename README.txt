MENUGO ADMIN - REVISI CSS DAN GRAFIK DATABASE

Login:
Username: admin
Password: admin123

Cara menjalankan:
1. Jalankan Apache dan MySQL di XAMPP/Laragon.
2. Buka phpMyAdmin.
3. Import file database.sql.
4. Simpan folder ini di htdocs atau C:\laragon\www.
5. Buka http://localhost/menugo_admin_revisi/index.php

Catatan revisi:
- CSS admin disesuaikan dengan desain Figma/PDF MenuGo.
- Warna utama: orange #F86015, hijau #5CB7A5, background #A2D1B1 dan #F3E8CC.
- Font menggunakan Boogaloo, Nunito, dan Inter.
- Grafik batang Dashboard dan Laporan Penjualan sekarang mengambil data dari tabel pesanan.
- Jika data transaksi ditambah/diubah/dihapus, tinggi grafik otomatis naik/turun mengikuti total penjualan per hari.
- Menu Terlaris dan Penjualan per Kategori juga mengambil data dari tabel detail_pesanan, menu, dan kategori.
- Laporan Penjualan tetap memiliki tombol Export Laporan PDF.


FITUR UPLOAD GAMBAR MENU:
- Pada halaman Tambah Menu dan Edit Menu, klik area Foto Produk / Pilih File.
- Pilih gambar dari komputer dengan format JPG, PNG, WEBP, atau GIF.
- Ukuran maksimal gambar 2MB.
- Gambar akan tersimpan otomatis di folder uploads/menu dan tampil di tabel Menu.
- Kalau folder uploads/menu belum ada, sistem akan membuatnya otomatis.
