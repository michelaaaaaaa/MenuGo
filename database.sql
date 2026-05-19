CREATE DATABASE IF NOT EXISTS menugo_db;
USE menugo_db;

DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS admin;

CREATE TABLE admin (
  id_admin INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL
);

CREATE TABLE kategori (
  id_kategori INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE menu (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  id_kategori INT NOT NULL,
  nama_menu VARCHAR(150) NOT NULL,
  deskripsi TEXT,
  harga INT NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  foto VARCHAR(255) DEFAULT NULL,
  status ENUM('Tersedia','Habis') DEFAULT 'Tersedia',
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE pesanan (
  id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
  kode_pesanan VARCHAR(20) NOT NULL UNIQUE,
  nama_pelanggan VARCHAR(100) NOT NULL,
  tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status_pesanan ENUM('Diterima','Diproses','Selesai','Dibatalkan') DEFAULT 'Diterima',
  metode_bayar ENUM('Cash','QRIS') DEFAULT 'Cash',
  total INT NOT NULL DEFAULT 0,
  no_meja INT,
);

CREATE TABLE detail_pesanan (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT NOT NULL,
  id_menu INT NOT NULL,
  qty INT NOT NULL,
  harga INT NOT NULL,
  subtotal INT NOT NULL,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT INTO admin (nama, username, password) VALUES ('Boni', 'admin', 'admin123');

INSERT INTO kategori (nama_kategori) VALUES
('Makanan'),('Minuman'),('Snack'),('Dessert');

INSERT INTO menu (id_kategori,nama_menu,deskripsi,harga,stok,foto,status) VALUES
(1,'Nasi Goreng Spesial','Nasi goreng dengan telur dan ayam',28000,20,'','Tersedia'),
(1,'Mie Ayam','Mie ayam dengan topping bakso',22000,18,'','Tersedia'),
(1,'Soto Ayam','Soto ayam kuah kuning hangat',20000,16,'','Tersedia'),
(1,'Gado-Gado','Sayuran segar dengan bumbu kacang',18000,15,'','Tersedia'),
(2,'Matcha Latte','Matcha premium dengan susu',22000,25,'','Tersedia'),
(2,'Kopi Susu Aren','Kopi susu dengan gula aren',20000,22,'','Tersedia'),
(2,'Es Teh Manis','Teh manis dingin',8000,40,'','Tersedia'),
(3,'Kentang Goreng','Kentang yang digoreng',15000,10,'','Tersedia'),
(3,'Tempe Mendoan','Tempe goreng tepung',12000,15,'','Tersedia'),
(4,'Klepon','Klepon gula merah kelapa',15000,12,'','Tersedia');

INSERT INTO pesanan (kode_pesanan,nama_pelanggan,nomor_meja,tanggal,status_pesanan,metode_bayar,total) VALUES
('#MG-0042','Rina',5,'2026-05-18 18:51:00','Diterima','QRIS',72000),
('#MG-0043','Andi',3,'2026-05-18 18:47:00','Diproses','Cash',60000),
('#MG-0044','Sari',7,'2026-05-18 18:40:00','Diproses','QRIS',36000),
('#MG-0041','Dimas',2,'2026-05-18 18:30:00','Selesai','Cash',38000),
('#MG-0040','Putra',6,'2026-05-17 19:15:00','Selesai','Cash',36000),
('#MG-0039','Nina',4,'2026-05-17 18:05:00','Dibatalkan','QRIS',50000);

INSERT INTO detail_pesanan (id_pesanan,id_menu,qty,harga,subtotal) VALUES
(1,1,1,28000,28000),(1,5,2,22000,44000),
(2,3,2,20000,40000),(2,6,1,20000,20000),
(3,4,1,18000,18000),(3,7,1,8000,8000),(3,8,1,10000,10000),
(4,2,1,22000,22000),(4,7,2,8000,16000),
(5,9,3,12000,36000),
(6,10,2,15000,30000),(6,5,1,20000,20000);

-- Tambahan data transaksi untuk membuat grafik batang naik/turun sesuai data database
INSERT INTO pesanan (kode_pesanan,nama_pelanggan,no_meja,tanggal,status_pesanan,metode_bayar,total) VALUES
('#MG-0038','Budi',8,'2026-05-16 13:20:00','Selesai','QRIS',102000),
('#MG-0037','Lala',9,'2026-05-15 12:15:00','Selesai','Cash',74000),
('#MG-0036','Mira',10,'2026-05-14 17:05:00','Selesai','QRIS',46000),
('#MG-0035','Rafi',11,'2026-05-13 16:25:00','Selesai','Cash',95000),
('#MG-0034','Dewi',12,'2026-05-12 14:10:00','Selesai','QRIS',58000),
('#MG-0033','Yoga',13,'2026-05-11 19:00:00','Dibatalkan','Cash',40000);

INSERT INTO detail_pesanan (id_pesanan,id_menu,qty,harga,subtotal) VALUES
(7,1,2,28000,56000),(7,5,1,22000,22000),(7,8,2,12000,24000),
(8,2,2,22000,44000),(8,7,2,8000,16000),(8,9,1,12000,12000),
(9,3,1,20000,20000),(9,6,1,20000,20000),(9,7,1,8000,8000),
(10,4,2,18000,36000),(10,5,2,22000,44000),(10,10,1,15000,15000),
(11,1,1,28000,28000),(11,6,1,20000,20000),(11,9,1,12000,12000),
(12,3,1,20000,20000),(12,7,2,8000,16000);
