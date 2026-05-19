<?php
require 'user_config.php';
require 'user_layout.php';

u_need_customer();

if (isset($_GET['plus'])) {
    $id = (int)$_GET['plus'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty']++;
    }

    header('Location: keranjang.php');
    exit;
}

if (isset($_GET['minus'])) {
    $id = (int)$_GET['minus'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty']--;

        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    header('Location: keranjang.php');
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    unset($_SESSION['cart'][$id]);

    header('Location: keranjang.php');
    exit;
}

u_header('Keranjang Pesanan', true);
?>

<div class="page-box">
    <h1 class="page-title">Pesananmu</h1>

    <?php
    $totalCart = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalCart += $item['harga'] * $item['qty'];
    ?>

        <div class="cart-item">
            <div class="cart-info">
                <h3><?= htmlspecialchars($item['nama_menu']) ?></h3>

                <div class="item-price">
                    <?= u_rupiah($item['harga']) ?>
                </div>

                <div class="qty-control">
                    <a class="minus" href="keranjang.php?minus=<?= $item['id_menu'] ?>">−</a>
                    <b><?= $item['qty'] ?></b>
                    <a class="plus" href="keranjang.php?plus=<?= $item['id_menu'] ?>">+</a>
                </div>
            </div>

            <div>
                <a
                    class="remove-cart"
                    href="keranjang.php?hapus=<?= $item['id_menu'] ?>"
                    onclick="return confirm('Hapus item ini?')"
                >
                    🗑
                </a>

                <div class="cart-subtotal">
                    <?= u_rupiah($item['harga'] * $item['qty']) ?>
                </div>
            </div>
        </div>

    <?php
        }
    } else {
    ?>

        <div class="empty">Keranjang masih kosong.</div>

    <?php } ?>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <div class="cart-summary">
            <div class="cart-total">
                <span>Total</span>
                <b><?= u_rupiah($totalCart) ?></b>
            </div>

            <a href="bayar.php" class="checkout-btn">
                Pesan Sekarang
            </a>
        </div>
    <?php } ?>
</div>

<?php u_footer(true, 'keranjang'); ?>