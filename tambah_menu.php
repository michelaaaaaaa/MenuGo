<?php 
require 'config.php'; 
need_login(); 
require 'layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = esc($_POST['nama_menu']);
  $desc = esc($_POST['deskripsi']);
  $harga = (int) $_POST['harga'];
  $stok = (int) $_POST['stok'];
  $kat = (int) $_POST['id_kategori'];
  $status = esc($_POST['status']);
  $foto = esc(upload_menu_photo('foto'));

  mysqli_query($conn, "INSERT INTO menu(id_kategori,nama_menu,deskripsi,harga,stok,foto,status) 
  VALUES($kat,'$nama','$desc',$harga,$stok,'$foto','$status')");

  header('Location: menu.php'); 
  exit;
}

render_header('Tambah Menu Baru', 'menu'); 
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
      <span class="upload-icon">🖼️</span>
      <span class="btn btn-green btn-small">Pilih File</span>
      <small>JPG, PNG, WEBP/GIF · max 2MB</small>
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
    <input class="input" name="nama_menu" placeholder="cth. Nasi Goreng" required>
  </div>

  <div class="formgroup">
    <label>Deskripsi</label>
    <textarea name="deskripsi" placeholder="Deskripsi singkat menu..."></textarea>
  </div>

  <div class="formgroup">
    <label>Harga</label>
    <input class="input" name="harga" type="number" placeholder="25000" required>
  </div>

  <div class="formgroup">
    <label>Kategori</label>
    <select name="id_kategori">
      <?php 
      $k = mysqli_query($conn, "SELECT * FROM kategori"); 
      while ($r = mysqli_fetch_assoc($k)) {
        echo "<option value='{$r['id_kategori']}'>{$r['nama_kategori']}</option>";
      } 
      ?>
    </select>
  </div>

  <div class="formgroup">
    <label>Stok</label>
    <input class="input" name="stok" type="number" value="10" required>
  </div>

  <div class="formgroup">
    <label>Status</label>
    <select name="status">
      <option>Tersedia</option>
      <option>Habis</option>
    </select>
  </div>

  <div class="actions">
    <a class="btn" href="menu.php">Batal</a>
    <button class="btn btn-primary" type="submit">Tambah Menu</button>
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