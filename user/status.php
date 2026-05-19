<?php
require 'user_config.php';
require 'user_layout.php';

u_need_customer();

$lastId = $_SESSION['last_order_id'] ?? 0;
$order = null;

if ($lastId) {
    $lastId = (int)$lastId;
    $q = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan = $lastId");
    $order = mysqli_fetch_assoc($q);
}

if (!$order) {
    $order = u_latest_order();
}

u_header('Status Pesanan', true);
?>

<div class="status-box">
    <a href="menu.php" class="back-btn">‹</a>

    <?php if ($order) { ?>

        <?php
        $detail = mysqli_query($conn, "
            SELECT d.*, m.nama_menu
            FROM detail_pesanan d
            JOIN menu m ON d.id_menu = m.id_menu
            WHERE d.id_pesanan = {$order['id_pesanan']}
        ");

        $status = $order['status_pesanan'];

        $step1 = in_array($status, ['Diterima', 'Diproses', 'Selesai']) ? 'done' : '';
        $step2 = in_array($status, ['Diproses', 'Selesai']) ? 'active' : '';
        $step3 = $status == 'Selesai' ? 'active' : '';

        $statusText = $status;

        if ($status == 'Diterima') {
            $statusText = 'Pesanan Diterima';
        } elseif ($status == 'Diproses') {
            $statusText = 'Sedang Dimasak';
        } elseif ($status == 'Selesai') {
            $statusText = 'Siap Diambil';
        }
        ?>

        <div class="status-code">
            <?= htmlspecialchars($order['kode_pesanan']) ?>
        </div>

        <div class="status-text">
            <?= htmlspecialchars($statusText) ?>
        </div>

        <div class="status-items">
            <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
                <div class="status-row">
                    <span><?= htmlspecialchars($d['nama_menu']) ?> x<?= $d['qty'] ?></span>
                    <b><?= u_rupiah($d['subtotal']) ?></b>
                </div>
            <?php } ?>
        </div>

        <div class="status-total">
            <span>Total</span>
            <b><?= u_rupiah($order['total']) ?></b>
        </div>

        <div class="progress">
            <div class="progress-step <?= $step1 ?>">
                <div class="progress-dot"></div>
                Diterima
            </div>

            <div class="progress-line <?= $step2 ? 'active' : '' ?>"></div>

            <div class="progress-step <?= $step2 ?>">
                <div class="progress-dot"></div>
                Dimasak
            </div>

            <div class="progress-line <?= $step3 ? 'active' : '' ?>"></div>

            <div class="progress-step <?= $step3 ?>">
                <div class="progress-dot"></div>
                Siap
            </div>
        </div>

    <?php } else { ?>

        <div class="empty">Belum ada pesanan aktif.</div>

    <?php } ?>
</div>

<?php u_footer(true, 'status'); ?>