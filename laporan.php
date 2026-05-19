<?php 
require 'config.php'; need_login(); 
require 'layout.php'; 

render_header('Laporan Penjualan','laporan'); 

$qterm=esc($_GET['q']??'');
$bulan=date('Y-m', strtotime(latest_order_date()));
$pendapatan = (int)mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(SUM(total),0) t FROM pesanan WHERE DATE_FORMAT(tanggal,'%Y-%m')='$bulan' AND status_pesanan!='Dibatalkan'"))['t'];
$trans=(int)mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM pesanan WHERE DATE_FORMAT(tanggal,'%Y-%m')='$bulan'"))['c'];
$avg=$trans?round($pendapatan/$trans):0;
$best=mysqli_fetch_assoc(mysqli_query($conn,"SELECT m.nama_menu, SUM(d.qty) qty FROM detail_pesanan d JOIN menu m ON d.id_menu=m.id_menu JOIN pesanan p ON d.id_pesanan=p.id_pesanan WHERE p.status_pesanan!='Dibatalkan' GROUP BY m.id_menu ORDER BY qty DESC LIMIT 1"));
$chart=sales_last_days(7);
$catq=mysqli_query($conn,"SELECT k.nama_kategori, SUM(d.qty) qty FROM detail_pesanan d JOIN menu m ON d.id_menu=m.id_menu JOIN kategori k ON m.id_kategori=k.id_kategori JOIN pesanan p ON d.id_pesanan=p.id_pesanan WHERE p.status_pesanan!='Dibatalkan' GROUP BY k.id_kategori,k.nama_kategori ORDER BY qty DESC");
$cats=[]; $totalQty=0; while($c=mysqli_fetch_assoc($catq)){ $c['qty']=(int)$c['qty']; $totalQty+=$c['qty']; $cats[]=$c; } if($totalQty<=0)$totalQty=1;
?>
<div class="cards">
  <div class="card"><div class="num"><?= compact_rupiah($pendapatan) ?></div><div class="label">Total Pendapatan<br>Bulan ini</div><div class="trend">Data bulan <?= date('m/Y', strtotime($bulan.'-01')) ?></div></div>
  <div class="card"><div class="num"><?= $trans ?></div><div class="label">Total Transaksi</div><div class="trend">Transaksi tersimpan</div></div>
  <div class="card"><div class="num"><?= compact_rupiah($avg) ?></div><div class="label">Rata-rata/Transaksi</div><div class="trend orange">Stabil</div></div>
  <div class="card"><div class="num" style="font-size:34px;text-align:center"><?= htmlspecialchars($best['nama_menu'] ?? '-') ?></div><div class="label">Menu Terlaris</div><div class="trend blue"><?= (int)($best['qty'] ?? 0) ?> Terjual</div></div>
</div>
<div class="grid2">
  <div class="panel"><h2>Pendapatan Mingguan</h2><div class="bars"><?= render_bars($chart,'revenue') ?></div></div>
  <div class="panel"><h2>Penjualan Per Kategori</h2><div style="display:flex;gap:30px"><div class="category-bars">
  <?php $classes=['red','orangebg','green','bluebg']; $i=0; foreach($cats as $c){ $h=max(10,round(($c['qty']/$totalQty)*120)); echo "<div class='{$classes[$i++%4]}' style='height:{$h}px'></div>"; } ?>
  </div><div class="legend">
  <?php $i=0; foreach($cats as $c){ $pct=round(($c['qty']/$totalQty)*100); echo "<span class='dot {$classes[$i++%4]}'></span>".htmlspecialchars($c['nama_kategori'])." {$pct}%<br>"; } ?>
  </div></div></div>
</div>
<h1 class="section-title">Log Transaksi</h1>
<table class="table"><tr><th>ID</th><th>Waktu</th><th>Menu</th><th>Qty</th><th>Metode</th><th>Total</th></tr>
<?php $sql="SELECT p.*, SUM(d.qty) qty FROM pesanan p JOIN detail_pesanan d ON p.id_pesanan=d.id_pesanan".($qterm?" WHERE p.kode_pesanan LIKE '%$qterm%' OR p.nama_pelanggan LIKE '%$qterm%'":'')." GROUP BY p.id_pesanan ORDER BY p.tanggal DESC"; $res=mysqli_query($conn,$sql); while($p=mysqli_fetch_assoc($res)){ echo "<tr><td class='code'>{$p['kode_pesanan']}</td><td>".date('d/m/Y H:i',strtotime($p['tanggal']))."</td><td>".htmlspecialchars(order_items($p['id_pesanan']))."</td><td><b>{$p['qty']}</b></td><td><span class='badge ".($p['metode_bayar']=='QRIS'?'diterima':'diproses')."'>{$p['metode_bayar']}</span></td><td class='price'>".rupiah($p['total'])."</td></tr>"; } ?>
</table><?php render_footer(); ?>
