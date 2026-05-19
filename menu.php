<?php
require 'config.php'; 
need_login(); 
require 'layout.php';

if (isset($_GET['hapus'])) {
  $hid = (int) $_GET['hapus'];

  $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM menu WHERE id_menu=$hid"));

  mysqli_query($conn, "DELETE FROM menu WHERE id_menu=$hid");

  if ($old && !empty($old['foto']) && file_exists(__DIR__ . '/' . $old['foto'])) {
    @unlink(__DIR__ . '/' . $old['foto']);
  }

  header('Location: menu.php'); 
  exit;
}

render_header('Kelola Menu', 'menu');

$qterm = esc($_GET['q'] ?? '');

$total = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM menu"))['c'];

$makanan = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM menu m 
  JOIN kategori k ON m.id_kategori = k.id_kategori 
  WHERE k.nama_kategori = 'Makanan'
"))['c'];

$minuman = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM menu m 
  JOIN kategori k ON m.id_kategori = k.id_kategori 
  WHERE k.nama_kategori = 'Minuman'
"))['c'];

$lain = $total - $makanan - $minuman;

$emojis = [
  'Makanan' => '🍛',
  'Minuman' => '🥤',
  'Snack' => '🍟',
  'Dessert' => '🍰'
];
?>

<div class="cards">
  <div class="card">
    <div class="num"><?= $total ?></div>
    <div class="label">Total Menu</div>
  </div>

  <div class="card">
    <div class="num"><?= $makanan ?></div>
    <div class="label">Makanan</div>
  </div>

  <div class="card">
    <div class="num"><?= $minuman ?></div>
    <div class="label">Minuman</div>
  </div>

  <div class="card">
    <div class="num"><?= $lain ?></div>
    <div class="label">Snack & Dessert</div>
  </div>
</div>

<div class="" style="display: flex; justify-content: space-between; align-items: center; margin: 22px 0 14px;">
  <h1 class="section-title">Daftar Menu</h1>
  <a class="btn btn-primary" href="tambah_menu.php">+ Tambah Menu</a>
</div>



<table class="table menu-table">
  <tr>
    <th>Gambar</th>
    <th>Nama</th>
    <th>Deskripsi</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
  </tr>

<?php
$sql = "
  SELECT m.*, k.nama_kategori 
  FROM menu m 
  JOIN kategori k ON m.id_kategori = k.id_kategori
";

if ($qterm) {
  $sql .= " 
    WHERE m.nama_menu LIKE '%$qterm%' 
    OR k.nama_kategori LIKE '%$qterm%'
  ";
}

$sql .= " ORDER BY m.id_menu DESC";

$res = mysqli_query($conn, $sql);

while ($m = mysqli_fetch_assoc($res)) {
  $cat = $m['nama_kategori'];
  $emoji = $emojis[$cat] ?? '🍽️';

  $foto = trim($m['foto'] ?? '');

  $gambar = $foto 
    ? "<img class='menu-photo-thumb' src='" . htmlspecialchars($foto) . "' alt='" . htmlspecialchars($m['nama_menu']) . "'>" 
    : "<div class='menu-img'>$emoji</div>";

  $catClass = (
    $cat == 'Makanan' ? 'dibatalkan' : 
    ($cat == 'Minuman' ? 'diterima' : 
    ($cat == 'Snack' ? 'diproses' : 'selesai'))
  );

  /*
    BAGIAN PENTING:
    Kalau stok 0 atau kurang, status yang tampil otomatis jadi Habis.
  */
  $stok = (int) $m['stok'];
  $statusTampil = ($stok <= 0) ? 'Habis' : $m['status'];
  $badge = strtolower($statusTampil);
?>

  <tr>
    <td><?= $gambar ?></td>

    <td>
      <b><?= htmlspecialchars($m['nama_menu']) ?></b>
    </td>

    <td>
      <?= htmlspecialchars($m['deskripsi']) ?>
    </td>

    <td>
      <span class="badge <?= $catClass ?>">
        <?= htmlspecialchars($cat) ?>
      </span>
    </td>

    <td class="price">
      <?= rupiah($m['harga']) ?>
    </td>

    <td>
      <b><?= $stok ?></b><br>
      <span class="badge <?= $badge ?>">
        <?= htmlspecialchars($statusTampil) ?>
      </span>
    </td>

    <td>
      <a class="btn btn-small" href="edit_menu.php?id=<?= $m['id_menu'] ?>">
        Edit
      </a>

      <a 
        class="btn btn-red btn-small" 
        onclick="return confirm('Hapus menu?')" 
        href="menu.php?hapus=<?= $m['id_menu'] ?>"
      >
        Hapus
      </a>
    </td>
  </tr>

<?php } ?>

</table>

<?php render_footer(); ?>