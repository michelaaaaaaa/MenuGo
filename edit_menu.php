<?php 
require 'config.php'; 
need_login(); 
require 'layout.php';

$id = (int)($_GET['id'] ?? 0);

$r = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu=$id");
$m = mysqli_fetch_assoc($r);

if (!$m) {
  header('Location: menu.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = esc($_POST['nama_menu']);
  $desc = esc($_POST['deskripsi']);
  $harga = (int) $_POST['harga'];
  $stok = (int) $_POST['stok'];
  $kat = (int) $_POST['id_kategori'];
  $status = esc($_POST['status']);

  /*
    Kalau stok 0, status otomatis Habis.
  */
  if ($stok <= 0) {
    $status = 'Habis';
  }

  $foto = esc(upload_menu_photo('foto', $m['foto']));

  mysqli_query($conn, "UPDATE menu SET 
    id_kategori=$kat,
    nama_menu='$nama',
    deskripsi='$desc',
    harga=$harga,
    stok=$stok,
    foto='$foto',
    status='$status'
    WHERE id_menu=$id
  ");

  header('Location: menu.php'); 
  exit;
}

render_header('Edit Menu', 'menu');

$fotoLama = trim($m['foto'] ?? '');
?>

<style>
  .photo-box.upload-box {
    width: 100%;
    height: 260px;
    border: 2px solid #A8A8A8;
    border-radius: 12px;
    background: #fff;
    cursor: pointer;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    gap: 12px;
    text-align: center;
    overflow: hidden;
  }

  .upload-icon {
    font-size: 72px;
    line-height: 1;
    color: #5CB7A5;
  }

  .photo-box .btn-green {
    background: rgba(92, 183, 165, 0.30);
    color: #5CB7A5;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 22px;
    font-weight: 800;
  }

  .photo-box small {
    display: block;
    font-size: 15px;
    font-weight: 800;
    color: #8A8A8A;
  }

  .file-input {
    display: none;
  }

  .photo-preview {
    width: 200px;
    height: 160px;
    object-fit: cover;
    border-radius: 12px;
    display: block;
  }
</style>

<form class="formbox" method="post" enctype="multipart/form-data">

  <div class="formgroup">
    <label>Foto Produk</label>

    <label class="photo-box upload-box" for="foto">
      <?php if ($fotoLama) { ?>
        <img class="photo-preview" src="<?= htmlspecialchars($fotoLama) ?>" alt="Foto menu">
        <span class="btn btn-green btn-small">Ganti Foto</span>
        <small>JPG, PNG, WEBP/GIF · max 2MB</small>
      <?php } else { ?>
        <span class="upload-icon">🖼️</span>
        <span class="btn btn-green btn-small">Ganti Foto</span>
        <small>JPG, PNG, WEBP/GIF · max 2MB</small>
      <?php } ?>
    </label>

    <input 
      id="foto" 
      class="file-input" 
      name="foto" 
      type="file" 
      accept="image/jpeg,image/png,image/webp,image/gif"
    >
  </div>

  <div class="formgroup">
    <label>Nama Menu</label>
    <input 
      class="input" 
      name="nama_menu" 
      value="<?= htmlspecialchars($m['nama_menu']) ?>" 
      required
    >
  </div>

  <div class="formgroup">
    <label>Deskripsi</label>
    <textarea name="deskripsi"><?= htmlspecialchars($m['deskripsi']) ?></textarea>
  </div>

  <div class="formgroup">
    <label>Harga</label>
    <input 
      class="input" 
      name="harga" 
      type="number" 
      value="<?= $m['harga'] ?>" 
      required
    >
  </div>

  <div class="formgroup">
    <label>Kategori</label>
    <select name="id_kategori">
      <?php 
      $k = mysqli_query($conn, "SELECT * FROM kategori"); 

      while ($row = mysqli_fetch_assoc($k)) { 
        $sel = $row['id_kategori'] == $m['id_kategori'] ? 'selected' : ''; 
        echo "<option $sel value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
      } 
      ?>
    </select>
  </div>

  <div class="formgroup">
    <label>Stok</label>
    <input 
      class="input" 
      name="stok" 
      type="number" 
      value="<?= $m['stok'] ?>" 
      required
    >
  </div>

  <div class="formgroup">
    <label>Status</label>
    <select name="status">
      <option <?= $m['status'] == 'Tersedia' ? 'selected' : '' ?>>
        Tersedia
      </option>
      <option <?= $m['status'] == 'Habis' ? 'selected' : '' ?>>
        Habis
      </option>
    </select>
  </div>

  <div class="actions">
    <a class="btn" href="menu.php">Batal</a>
    <button class="btn btn-primary" type="submit">Edit Menu</button>
  </div>

</form>

<script>
const inputFoto = document.getElementById('foto');
const boxFoto = document.querySelector('.upload-box');

inputFoto.addEventListener('change', function () {
  const file = this.files[0];

  if (!file) return;

  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

  if (!allowedTypes.includes(file.type)) {
    alert('Format gambar harus JPG, PNG, WEBP, atau GIF.');
    this.value = '';
    return;
  }

  if (file.size > 2 * 1024 * 1024) {
    alert('Ukuran gambar maksimal 2MB.');
    this.value = '';
    return;
  }

  const reader = new FileReader();

  reader.onload = e => {
    boxFoto.innerHTML = `
      <img class="photo-preview" src="${e.target.result}" alt="Preview Foto">
      <span class="btn btn-green btn-small">Ganti Foto</span>
      <small>${file.name}</small>
    `;
  };

  reader.readAsDataURL(file);
});
</script>

<?php render_footer(); ?>