<?php require 'config.php'; need_login(); require 'layout.php';
if(isset($_GET['hapus'])){ mysqli_query($conn,"DELETE FROM kategori WHERE id_kategori=".(int)$_GET['hapus']); header('Location: kategori.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama=esc($_POST['nama_kategori']);
  if(isset($_POST['id']) && $_POST['id']) mysqli_query($conn,"UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori=".(int)$_POST['id']);
  else mysqli_query($conn,"INSERT INTO kategori(nama_kategori) VALUES('$nama')");
  header('Location: kategori.php'); exit;
}
$edit=null; if(isset($_GET['edit'])){$r=mysqli_query($conn,"SELECT * FROM kategori WHERE id_kategori=".(int)$_GET['edit']); $edit=mysqli_fetch_assoc($r);} 
render_header('Kategori Menu','kategori'); $qterm=esc($_GET['q']??'');
?>
<div class="formbox" style="margin-bottom:24px"><form method="post"><input type="hidden" name="id" value="<?= $edit['id_kategori'] ?? '' ?>"><div class="formgroup"><label><?= $edit?'Edit':'Tambah' ?> Kategori</label><input class="input" name="nama_kategori" required placeholder="cth. Makanan" value="<?= htmlspecialchars($edit['nama_kategori'] ?? '') ?>"></div><button class="btn btn-primary"><?= $edit?'Simpan Perubahan':'Tambah Kategori' ?></button> <?php if($edit){ ?><a class="btn" href="kategori.php">Batal</a><?php } ?></form></div>
<h1 class="section-title">Data Kategori</h1><table class="table"><tr><th>ID</th><th>Nama Kategori</th><th>Jumlah Menu</th><th>Aksi</th></tr><?php $sql="SELECT k.*, COUNT(m.id_menu) jumlah FROM kategori k LEFT JOIN menu m ON k.id_kategori=m.id_kategori".($qterm?" WHERE k.nama_kategori LIKE '%$qterm%'":'')." GROUP BY k.id_kategori ORDER BY k.id_kategori"; $res=mysqli_query($conn,$sql); while($k=mysqli_fetch_assoc($res)){ echo "<tr><td class='code'>KT-{$k['id_kategori']}</td><td><b>{$k['nama_kategori']}</b></td><td>{$k['jumlah']} menu</td><td><a class='btn btn-small' href='kategori.php?edit={$k['id_kategori']}'>Edit</a> <a class='btn btn-red btn-small' onclick=\"return confirm('Hapus kategori?')\" href='kategori.php?hapus={$k['id_kategori']}'>Hapus</a></td></tr>"; } ?></table>
<?php render_footer(); ?>
