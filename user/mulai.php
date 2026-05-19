<?php
require 'user_config.php';
require 'user_layout.php';

if (isset($_POST['mulai_pesan'])) {
    $_SESSION['customer_name'] = trim($_POST['nama_pelanggan']);
    $_SESSION['nomor_meja'] = trim($_POST['nomor_meja']);
    $_SESSION['cart'] = [];

    header('Location: menu.php');
    exit;
}

u_header();
?>

<section class="start-page">
    <form class="start-card" method="post">
        <div class="logo">MenuGo</div>

        <p>
            Selamat datang!👋 Masukkan nama kamu untuk mulai memesan.
        </p>

        <label class="form-label">Nama Kamu</label>
        <input class="input" type="text" name="nama_pelanggan" required>

        <p style="margin-top:10px;">
            Nama ini akan digunakan sebagai identitas pesananmu
        </p>

        <label class="form-label">Nomor Meja</label>
        <input class="input" type="text" name="nomor_meja" placeholder="cth. 05" required>

        <button class="full-btn" name="mulai_pesan">
            Mulai pesan
        </button>
    </form>
</section>

<?php u_footer(); ?>