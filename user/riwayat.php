<?php
require 'user_config.php';
require 'user_layout.php';

u_need_customer();

$nama = u_esc($_SESSION['customer_name']);
$meja = u_esc($_SESSION['nomor_meja'] ?? '');

$riwayat = mysqli_query($conn, "
    SELECT *
    FROM pesanan
    WHERE nama_pelanggan = '$nama'
    AND nomor_meja = '$meja'
    ORDER BY tanggal DESC
");

u_header('Riwayat Pesanan', true);
?>

<div class="history-box">
    <?php if (mysqli_num_rows($riwayat) > 0) { ?>

        <?php while ($r = mysqli_fetch_assoc($riwayat)) { ?>

            <?php
            $detail = mysqli_query($conn, "
                SELECT d.*, m.nama_menu
                FROM detail_pesanan d
                JOIN menu m ON d.id_menu = m.id_menu
                WHERE d.id_pesanan = {$r['id_pesanan']}
            ");
            ?>

            <div class="history-card">
                <div class="history-head">
                    <div>
                        <div class="history-code">
                            <?= htmlspecialchars($r['kode_pesanan']) ?>
                        </div>

                        <div class="history-date">
                            <?= date('d F Y, H:i', strtotime($r['tanggal'])) ?>
                        </div>
                    </div>

                    <div class="history-status">
                        <?= htmlspecialchars($r['status_pesanan']) ?>
                    </div>
                </div>

                <div class="history-items">
                    <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
                        <div class="history-row">
                            <span><?= htmlspecialchars($d['nama_menu']) ?> x<?= $d['qty'] ?></span>
                            <b><?= u_rupiah($d['subtotal']) ?></b>
                        </div>
                    <?php } ?>
                </div>

                <div class="history-total">
                    <span>Total</span>
                    <b><?= u_rupiah($r['total']) ?></b>
                    <a href="menu.php" class="reorder-btn">Pesan Lagi</a>
                </div>
            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty">Belum ada riwayat pesanan.</div>

    <?php } ?>
</div>

<?php u_footer(true, 'riwayat'); ?>