<?php require 'config.php'; need_login(); require 'layout.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama=esc($_POST['nama_pelanggan']); $id_menu=(int)$_POST['id_menu']; $qty=(int)$_POST['qty']; $metode=esc($_POST['metode_bayar']);
  $m=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM menu WHERE id_menu=$id_menu")); $harga=(int)$m['harga']; $subtotal=$harga*$qty; $kode='#MG-'.str_pad(rand(45,9999),4,'0',STR_PAD_LEFT);
  mysqli_query($conn,"INSERT INTO pesanan(kode_pesanan,nama_pelanggan,status_pesanan,metode_bayar,total) VALUES('$kode','$nama','Diterima','$metode',$subtotal)");
  $idp=mysqli_insert_id($conn); mysqli_query($conn,"INSERT INTO detail_pesanan(id_pesanan,id_menu,qty,harga,subtotal) VALUES($idp,$id_menu,$qty,$harga,$subtotal)");
  header('Location: pesanan.php'); exit;
}
render_header('Tambah Pesanan','pesanan'); ?>
<form class="formbox" method="post"><div class="formgroup"><label>Nama Pelanggan</label><input class="input" name="nama_pelanggan" placeholder="cth. Budi" required></div><div class="formgroup"><label>Pilih Menu</label><select name="id_menu"><?php $q=mysqli_query($conn,"SELECT m.*,k.nama_kategori FROM menu m JOIN kategori k ON m.id_kategori=k.id_kategori WHERE m.status='Tersedia'"); while($m=mysqli_fetch_assoc($q)){echo "<option value='{$m['id_menu']}'>{$m['nama_menu']} - ".rupiah($m['harga'])."</option>";} ?></select></div><div class="formgroup"><label>Jumlah</label><input class="input" type="number" name="qty" min="1" value="1" required></div><div class="formgroup"><label>Metode Bayar</label><select name="metode_bayar"><option>Cash</option><option>QRIS</option></select></div><div class="actions"><a class="btn" href="pesanan.php">Batal</a><button class="btn btn-primary">Tambah Pesanan</button></div></form><?php render_footer(); ?>
