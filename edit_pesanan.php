<?php 
require 'config.php'; 
need_login(); 
require 'layout.php';

$id=(int)

($_GET['id']??0); 
$p=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM pesanan WHERE id_pesanan=$id")); 
if(!$p){
  header('Location: pesanan.php');exit;
}

$d=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM detail_pesanan WHERE id_pesanan=$id LIMIT 1"));
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama=esc($_POST['nama_pelanggan']); $id_menu=(int)$_POST['id_menu']; $qty=(int)$_POST['qty']; $metode=esc($_POST['metode_bayar']); $status=esc($_POST['status_pesanan']);
  $m=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM menu WHERE id_menu=$id_menu")); $harga=(int)$m['harga']; $subtotal=$harga*$qty;
  mysqli_query($conn,"UPDATE pesanan SET nama_pelanggan='$nama',status_pesanan='$status',metode_bayar='$metode',total=$subtotal WHERE id_pesanan=$id");
  mysqli_query($conn,"UPDATE detail_pesanan SET id_menu=$id_menu,qty=$qty,harga=$harga,subtotal=$subtotal WHERE id_detail=".(int)$d['id_detail']);
  header('Location: pesanan.php'); exit;
}
render_header('Edit Pesanan','pesanan'); ?>
<form class="formbox" method="post"><div class="formgroup"><label>Nama Pelanggan</label><input class="input" name="nama_pelanggan" value="<?= htmlspecialchars($p['nama_pelanggan']) ?>" required></div><div class="formgroup"><label>Pilih Menu</label><select name="id_menu"><?php $q=mysqli_query($conn,"SELECT * FROM menu"); while($m=mysqli_fetch_assoc($q)){ $sel=$d && $d['id_menu']==$m['id_menu']?'selected':''; echo "<option $sel value='{$m['id_menu']}'>{$m['nama_menu']} - ".rupiah($m['harga'])."</option>";} ?></select></div><div class="formgroup"><label>Jumlah</label><input class="input" type="number" name="qty" min="1" value="<?= $d['qty'] ?? 1 ?>" required></div><div class="formgroup"><label>Status</label><select name="status_pesanan"><option <?= $p['status_pesanan']=='Diterima'?'selected':'' ?>>Diterima</option><option <?= $p['status_pesanan']=='Diproses'?'selected':'' ?>>Diproses</option><option <?= $p['status_pesanan']=='Selesai'?'selected':'' ?>>Selesai</option><option <?= $p['status_pesanan']=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option></select></div><div class="formgroup"><label>Metode Bayar</label><select name="metode_bayar"><option <?= $p['metode_bayar']=='Cash'?'selected':'' ?>>Cash</option><option <?= $p['metode_bayar']=='QRIS'?'selected':'' ?>>QRIS</option></select></div><div class="actions"><a class="btn" href="pesanan.php">Batal</a><button class="btn btn-primary">Edit Pesanan</button></div></form><?php render_footer(); ?>
