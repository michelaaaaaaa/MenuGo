<?php 
require 'config.php'; 
need_login(); 
require 'layout.php'; 

/* UPDATE STATUS PESANAN DARI DASHBOARD */
if (isset($_POST['status_id'])) {
  $id = (int) $_POST['status_id'];
  $status = esc($_POST['status']);

  mysqli_query($conn, "
    UPDATE pesanan 
    SET status_pesanan = '$status' 
    WHERE id_pesanan = $id
  ");

  header('Location: dashboard.php');
  exit;
}

render_header('Dashboard', 'dashboard');

$end = latest_order_date();
$today = $end;
$yesterday = date('Y-m-d', strtotime($today . ' -1 day'));

$income = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COALESCE(SUM(total), 0) t 
  FROM pesanan 
  WHERE DATE(tanggal) = '$today' 
  AND status_pesanan != 'Dibatalkan'
"))['t'];

$income_yesterday = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COALESCE(SUM(total), 0) t 
  FROM pesanan 
  WHERE DATE(tanggal) = '$yesterday' 
  AND status_pesanan != 'Dibatalkan'
"))['t'];

$orders = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM pesanan
"))['c'];

$new_today = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM pesanan 
  WHERE DATE(tanggal) = '$today'
"))['c'];

$active = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM pesanan 
  WHERE status_pesanan IN ('Diterima', 'Diproses')
"))['c'];

$menus = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM menu 
  WHERE status = 'Tersedia'
"))['c'];

$kat = (int) mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) c 
  FROM kategori
"))['c'];

$chart = sales_last_days(7);

$top = mysqli_query($conn, "
  SELECT 
    m.nama_menu, 
    SUM(d.qty) qty 
  FROM detail_pesanan d 
  JOIN menu m ON d.id_menu = m.id_menu 
  JOIN pesanan p ON d.id_pesanan = p.id_pesanan 
  WHERE p.status_pesanan != 'Dibatalkan' 
  GROUP BY m.id_menu, m.nama_menu 
  ORDER BY qty DESC 
  LIMIT 4
");

$topRows = []; 
$maxTop = 0; 

while ($r = mysqli_fetch_assoc($top)) { 
  $r['qty'] = (int) $r['qty']; 
  $maxTop = max($maxTop, $r['qty']); 
  $topRows[] = $r; 
} 

if ($maxTop <= 0) {
  $maxTop = 1;
}
?>

<style>
  .status-select {
    width: 140px;
    height: 30px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 8px;
    background: #FFFFFF;
    color: #023820;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    padding: 0 10px;
    outline: none;
  }

  .status-form {
    display: inline-block;
    margin: 0;
  }
</style>

<div class="cards">
  <div class="card">
    <div class="num"><?= compact_rupiah($income) ?></div>
    <div class="label">Pendapatan Hari Ini</div>
    <div class="trend"><?= calc_percent_change($income, $income_yesterday) ?> dari kemarin</div>
  </div>

  <div class="card">
    <div class="num"><?= $orders ?></div>
    <div class="label">Total Pesanan</div>
    <div class="trend">+<?= $new_today ?> pesanan baru</div>
  </div>

  <div class="card">
    <div class="num"><?= $active ?></div>
    <div class="label">Pesanan Aktif</div>
    <div class="trend orange">Perlu diproses</div>
  </div>

  <div class="card">
    <div class="num"><?= $menus ?></div>
    <div class="label">Menu Tersedia</div>
    <div class="trend blue"><?= $kat ?> kategori</div>
  </div>
</div>

<div class="grid2">
  <div class="panel chart">
    <h2>Penjualan 7 Hari Terakhir</h2>
    <div class="bars">
      <?= render_bars($chart, 'revenue') ?>
    </div>
  </div>

  <div class="panel">
    <h2>Menu Terlaris</h2>

    <?php 
    $colors = ['', 'green', 'orangebg', '']; 
    $i = 0; 

    foreach ($topRows as $r) { 
      $w = max(8, round(($r['qty'] / $maxTop) * 346)); 
      $c = $colors[$i++ % 4]; 

      echo "
        <div class='lineitem'>
          <span>" . htmlspecialchars($r['nama_menu']) . "</span>
          <span style='float:right'>{$r['qty']} Terjual</span>
          <div class='line $c' style='width:{$w}px'></div>
        </div>
      "; 
    } 

    if (!$topRows) { 
      echo "<p>Belum ada data penjualan.</p>"; 
    } 
    ?>
  </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 22px 0 14px;">
  <h1 class="section-title" style="margin: 0;">Pesanan Terbaru</h1>
  <a class="btn" href="pesanan.php">Lihat Semua</a>
</div>

<table class="table">
  <tr>
    <th>Order ID</th>
    <th>No Meja</th>
    <th>Menu</th>
    <th>Total</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>

  <?php 
  $q = mysqli_query($conn, "
    SELECT * 
    FROM pesanan 
    ORDER BY tanggal DESC 
    LIMIT 3
  "); 

  if (mysqli_num_rows($q) > 0) {
    while ($p = mysqli_fetch_assoc($q)) { 
      $cls = strtolower($p['status_pesanan']); 
  ?>

    <tr>
      <td class="code">
        <?= htmlspecialchars($p['kode_pesanan']) ?>
      </td>

      <td>
        <b><?= htmlspecialchars($p['no_meja']) ?></b>
        <br>
        <small><?= date('H:i', strtotime($p['tanggal'])) ?></small>
      </td>

      <td>
        <?= htmlspecialchars(order_items($p['id_pesanan'])) ?>
      </td>

      <td class="price">
        <?= rupiah($p['total']) ?>
      </td>

      <td>
        <span class="badge <?= $cls ?>">
          <?= htmlspecialchars($p['status_pesanan']) ?>
        </span>
      </td>

      <td>
        <form method="post" class="status-form">
          <input 
            type="hidden" 
            name="status_id" 
            value="<?= $p['id_pesanan'] ?>"
          >

          <select 
            name="status" 
            class="status-select" 
            onchange="this.form.submit()"
          >
            <option value="Diterima" <?= $p['status_pesanan'] == 'Diterima' ? 'selected' : '' ?>>
              Diterima
            </option>

            <option value="Diproses" <?= $p['status_pesanan'] == 'Diproses' ? 'selected' : '' ?>>
              Diproses
            </option>

            <option value="Selesai" <?= $p['status_pesanan'] == 'Selesai' ? 'selected' : '' ?>>
              Selesai
            </option>

            <option value="Dibatalkan" <?= $p['status_pesanan'] == 'Dibatalkan' ? 'selected' : '' ?>>
              Dibatalkan
            </option>
          </select>
        </form>
      </td>
    </tr>

  <?php 
    } 
  } else { 
  ?>

    <tr>
      <td colspan="6" style="text-align:center; padding:25px; font-weight:800; color:#777;">
        Belum ada pesanan.
      </td>
    </tr>

  <?php } ?>
</table>

<?php render_footer(); ?>