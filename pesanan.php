<?php
require 'config.php';
need_login();
require 'layout.php';

if (isset($_POST['status_id'])) {

    $id = (int) $_POST['status_id'];
    $status = esc($_POST['status']);

    mysqli_query(
        $conn,
        "UPDATE pesanan 
         SET status_pesanan='$status'
         WHERE id_pesanan=$id"
    );

    header('Location: pesanan.php');
    exit;
}

render_header('Pesanan Masuk', 'pesanan');


$qterm = esc($_GET['q'] ?? '');


$all = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) c FROM pesanan")
)['c'];

$aktif = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) c 
         FROM pesanan 
         WHERE status_pesanan IN('Diterima','Diproses')"
    )
)['c'];

$done = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) c 
         FROM pesanan 
         WHERE status_pesanan='Selesai'"
    )
)['c'];

$cancel = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) c 
         FROM pesanan 
         WHERE status_pesanan='Dibatalkan'"
    )
)['c'];
?>

<style>
.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-bottom:20px;
}

.card{
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.card .num{
    font-size:28px;
    font-weight:bold;
}

.card .label{
    color:#666;
    margin-top:5px;
}

.right{
    text-align:right;
}

.section-title{
    margin:20px 0 15px;
}

.table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.table th{
    background:#f3f4f6;
    padding:14px;
    text-align:left;
}

.table td{
    padding:14px;
    border-bottom:1px solid #eee;
    vertical-align:middle;
}

.code{
    font-weight:bold;
    color:#2563eb;
}

.price{
    font-weight:bold;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
}

.badge.diterima{
    background:#dbeafe;
    color:#1d4ed8;
}

.badge.diproses{
    background:#fef3c7;
    color:#b45309;
}

.badge.selesai{
    background:#dcfce7;
    color:#15803d;
}

.badge.dibatalkan{
    background:#fee2e2;
    color:#b91c1c;
}

.status-select{
    padding:7px 10px;
    border-radius:8px;
    border:1px solid #ccc;
    background:#fff;
}

.btn{
    padding:8px 12px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    color:white;
    font-size:13px;
    display:inline-block;
    cursor:pointer;
}

.btn-primary{
    background:#ee3f24;
}

.btn:hover{
    opacity:0.9;
}
</style>

<div class="cards">

    <div class="card">
        <div class="num"><?= $all ?></div>
        <div class="label">Semua Pesanan</div>
    </div>

    <div class="card">
        <div class="num"><?= $aktif ?></div>
        <div class="label">Aktif</div>
    </div>

    <div class="card">
        <div class="num"><?= $done ?></div>
        <div class="label">Selesai</div>
    </div>

    <div class="card">
        <div class="num"><?= $cancel ?></div>
        <div class="label">Dibatalkan</div>
    </div>

</div>

<div class="right" style="display: flex; justify-content: space-between; align-items: center; margin: 22px 0 14px;">
    <h1 class="section-title">
    Daftar Pesanan Masuk
    </h1>
    <a href="tambah_pesanan.php" class="btn btn-primary">
        + Tambah Pesanan
    </a>
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
$qterm = $_GET['q'] ?? '';

$sql = "SELECT 
            p.*, 
            GROUP_CONCAT(m.nama_menu,' ', dp.qty, 'x' SEPARATOR ', ') AS daftar_menu
        FROM pesanan p
        LEFT JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
        LEFT JOIN menu m ON dp.id_menu = m.id_menu";

if (!empty($qterm)) {
    $q_aman = mysqli_real_escape_string($conn, $qterm);
    $sql .= " WHERE p.kode_pesanan LIKE '%$q_aman%'
              OR p.nama_pelanggan LIKE '%$q_aman%'";
}

$sql .= " GROUP BY p.id_pesanan";
$sql .= " ORDER BY p.tanggal DESC";

$res = mysqli_query($conn, $sql);

while ($p = mysqli_fetch_assoc($res)) :
    $cls = strtolower($p['status_pesanan']);
?>
    <tr>
        <td class="code">
            <?= $p['kode_pesanan'] ?><br>
            <span style="font-size: 12px; color: #555; font-weight: normal;"><?= $p['nama_pelanggan'] ?></span>
        </td>

        <td>
            <b><?= $p['nomor_meja'] ?></b><br>
        </td>

        <td style="line-height: 1.5;">
            <?= $p['daftar_menu'] ?>
        </td>

        <td class="price">
            <?= rupiah($p['total']) ?>
        </td>

        <td>
            <span class="badge <?= $cls ?>">
                <?= $p['status_pesanan'] ?>
            </span>
        </td>

        <td>
            <form method="post" style="display:inline-block;">
                <input type="hidden" name="status_id" value="<?= $p['id_pesanan'] ?>">
                <select name="status" class="status-select" onchange="this.form.submit()">
                    <option <?= $p['status_pesanan'] == 'Diterima' ? 'selected' : '' ?>>Diterima</option>
                    <option <?= $p['status_pesanan'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option <?= $p['status_pesanan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option <?= $p['status_pesanan'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </form>
        </td>
    </tr>
<?php endwhile; ?>
</table>

<?php render_footer(); ?>