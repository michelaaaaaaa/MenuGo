<?php
function u_header($title = '', $show_app_header = false) {
    $nama = $_SESSION['customer_name'] ?? '';
    $meja = $_SESSION['nomor_meja'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MenuGo User</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Boogaloo&family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #F8ECBC;
            color: #111;
        }

        a {
            text-decoration: none;
        }

        .logo {
            font-family: 'Boogaloo', cursive;
            color: #F86015;
            font-size: 48px;
            line-height: 1;
        }

        .app {
            min-height: 100vh;
            padding-bottom: 130px;
            background:
                linear-gradient(rgba(255,248,210,.78), rgba(255,248,210,.78)),
                radial-gradient(circle at 20% 35%, rgba(255,145,40,.35), transparent 24%),
                radial-gradient(circle at 70% 55%, rgba(255,120,0,.45), transparent 30%),
                linear-gradient(160deg, #FFF9D9 0%, #FFF0B8 38%, #FF9D3B 39%, #FF7A16 100%);
            position: relative;
            overflow-x: hidden;
        }

        .app::after {
            content: '';
            position: fixed;
            left: -10%;
            bottom: -18%;
            width: 120%;
            height: 42%;
            background: rgba(255,112,10,.65);
            border-radius: 50% 50% 0 0;
            z-index: 0;
        }

        .user-header {
            position: sticky;
            top: 0;
            z-index: 10;
            height: 165px;
            background: rgba(255,255,245,.92);
            border-radius: 0 0 50px 50px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 28px 58px;
        }

        .header-center {
            text-align: center;
            padding-top: 20px;
        }

        .header-center h1 {
            font-family: 'Boogaloo', cursive;
            font-size: 46px;
            color: #5CB7A5;
            font-weight: 400;
        }

        .header-center p {
            font-size: 26px;
            color: #666;
            font-weight: 800;
        }

        .page-head-title {
            font-family: 'Boogaloo', cursive !important;
            font-size: 46px !important;
            color: #555 !important;
            font-weight: 400 !important;
        }

        .meja-box {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .meja {
            background: #EEAB43;
            color: white;
            padding: 10px 24px;
            border-radius: 18px;
            font-size: 28px;
            font-weight: 500;
            text-align: center;
        }

        .logout-user {
            background: #C00F0C;
            color: white;
            padding: 10px 28px;
            border-radius: 18px;
            font-size: 28px;
            font-weight: 800;
            text-align: center;
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 20px 48px;
        }

        .home {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;   
            text-align: center;
            background:
                linear-gradient(to bottom, #DAE6BA 0 28%, #F8ECBC 28% 78%, #F6E0A5 78% 100%);
            padding: 0 20px;          
        }

        .pill {
            background: white;
            color: #2DBBAC;
            padding: 10px 24px;
            border-radius: 999px;
            box-shadow: 0 8px 18px rgba(0,0,0,.12);
            font-weight: 600;
            letter-spacing: .5px;
            margin-bottom: 45px;
        }

        .pill::before {
            content: '';
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #2DBBAC;
            border-radius: 50%;
            margin-right: 10px;
        }

        .home-title {
            font-family: 'Playfair Display', serif;
            font-size: 82px;
            line-height: .95;
            font-weight: 800;
        }

        .home-title span {
            display: block;
            color: #F86015;
            font-style: italic;
        }

        .home-desc {
            margin: 38px 0;
            font-size: 21px;
            line-height: 1.55;
            color: #666;
            max-width: 500px;
        }

        .primary-btn {
            display: inline-block;
            border: none;
            background: #2DBBAC;
            color: white;
            padding: 18px 42px;
            border-radius: 14px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 25px rgba(45,187,172,.22);
        }

        /* MULAI */
        .start-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F8ECBC;
        }

        .start-card {
            width: 500px;
            background: white;
            padding: 38px 34px;
            border-radius: 50px;
            box-shadow: 12px 12px 0 rgba(0,0,0,.22);
        }

        .start-card .logo {
            font-size: 78px;
            margin-bottom: 15px;
        }

        .start-card p {
            font-size: 20px;
            color: #888;
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 18px;
            font-weight: 700;
            color: #444;
            margin: 16px 0 8px;
            text-transform: uppercase;
        }

        .input {
            width: 100%;
            height: 52px;
            border: 1.5px solid #D9D9D9;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 18px;
            outline: none;
        }

        .input:focus {
            border-color: #5CB7A5;
        }

        .full-btn {
            width: 100%;
            margin-top: 22px;
            height: 58px;
            border: none;
            border-radius: 9px;
            background: #5CB7A5;
            color: white;
            font-size: 20px;
            font-weight: 800;
            cursor: pointer;
        }

        /* MENU */
        .tabs {
            max-width: 1160px;
            margin: -5px auto 12px;
            background: white;
            border-radius: 13px;
            padding: 18px 22px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,.25);
        }

        .tab {
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            color: #111;
            font-size: 20px;
            font-weight: 800;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,.20);
        }

        .tab.active {
            background: #F43A25;
            color: white;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 36px 16px;
            margin-top: 12px;
        }

        .menu-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            min-height: 320px;
            box-shadow: 0 4px 8px rgba(0,0,0,.30);
            position: relative;
        }

        .menu-card img,
        .menu-card .no-img {
            width: 100%;
            height: 143px;
            object-fit: cover;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 55px;
        }

        .menu-body {
            padding: 28px 15px 22px;
        }

        .menu-body h3 {
            font-size: 25px;
            margin-bottom: 12px;
        }

        .menu-body p {
            color: #888;
            font-size: 18px;
            line-height: 1.35;
            min-height: 58px;
        }

        .menu-price {
            color: #0B5B32;
            font-size: 21px;
            font-weight: 900;
            margin-top: 20px;
        }

        .add-btn {
            width: 47px;
            height: 47px;
            border-radius: 50%;
            background: #F43A25;
            color: white;
            font-size: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            right: 18px;
            bottom: 15px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            font-size: 20px;
            font-weight: 700;
            color: #777;
        }

        /* KERANJANG */
        .page-box {
            max-width: 760px;
            min-height: 520px;
            margin: 24px auto 20px;
            background: white;
            border-radius: 14px;
            padding: 28px 26px;
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
        }

        .page-title {
            font-family: 'Boogaloo', cursive;
            text-align: center;
            color: #000;
            font-size: 52px;
            margin-bottom: 26px;
            text-shadow: 3px 4px 4px rgba(0,0,0,.25);
        }

        .cart-item {
            background: #f4f4f4;
            border-radius: 16px;
            padding: 14px;
            display: grid;
            grid-template-columns: 1fr 140px;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,.22);
        }

        .cart-info h3 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .item-price {
            color: #0B5B32;
            font-weight: 900;
            font-size: 17px;
            margin-bottom: 12px;
        }

        .qty-control {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .qty-control a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
        }

        .qty-control a.minus {
            background: #F7EAC8;
            color: #111;
        }

        .qty-control a.plus {
            background: #F43A25;
            color: white;
        }

        .qty-control b {
            font-size: 24px;
        }

        .remove-cart {
            color: #F43A25;
            font-size: 24px;
            font-weight: 900;
        }

        .cart-subtotal {
            text-align: right;
            font-size: 20px;
            font-weight: 900;
            color: #111;
            margin-top: 32px;
        }

        .cart-summary {
            margin: 100px -26px -28px;
            padding: 28px 42px 32px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 -4px 8px rgba(0,0,0,.22);
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 23px;
            font-weight: 900;
            margin-bottom: 18px;
            color: #111;
        }

        .cart-total b {
            color: #0B5B32;
            font-size: 25px;
        }

        .checkout-btn,
        .pay-btn {
            display: block;
            width: 100%;
            height: 58px;
            background: #F43A25;
            color: white;
            border: none;
            border-radius: 8px;
            text-align: center;
            line-height: 58px;
            font-size: 19px;
            font-weight: 800;
            cursor: pointer;
        }

        /* BAYAR */
        .payment-box {
            max-width: 760px;
            margin: 24px auto 20px;
            background: white;
            border-radius: 14px;
            padding: 28px 26px 22px;
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
        }

        .payment-title {
            font-family: 'Boogaloo', cursive;
            text-align: center;
            color: #000;
            font-size: 52px;
            margin-bottom: 28px;
            text-shadow: 3px 4px 4px rgba(0,0,0,.25);
        }

        .payment-total {
            background: #f4f4f4;
            border-radius: 16px;
            height: 100px;
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0,0,0,.22);
            margin-bottom: 18px;
        }

        .payment-total span {
            font-size: 23px;
            font-weight: 900;
        }

        .payment-total b {
            color: #0B5B32;
            font-size: 26px;
        }

        .payment-method-row {
            display: grid;
            grid-template-columns: 1fr 140px;
            gap: 12px;
            margin-bottom: 18px;
        }

        .payment-tab {
            background: #f4f4f4;
            border-radius: 14px;
            height: 66px;
            box-shadow: 0 4px 6px rgba(0,0,0,.22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: 900;
        }

        .payment-tab.cash {
            font-size: 42px;
        }

        .qris-card {
            background: #f4f4f4;
            border-radius: 14px;
            padding: 14px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 18px;
            box-shadow: 0 4px 6px rgba(0,0,0,.22);
            margin-bottom: 18px;
        }

        .qris-image {
            height: 330px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .qris-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fake-qris {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .qris-logo {
            font-size: 34px;
            font-weight: 900;
        }

        .fake-qr {
            width: 190px;
            height: 190px;
            border: 8px solid #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            line-height: 1.2;
            text-align: center;
        }

        .qris-info {
            background: white;
            border-radius: 10px;
            min-height: 330px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 25px;
            font-weight: 900;
            line-height: 1.1;
            text-shadow: 2px 3px 4px rgba(0,0,0,.22);
        }

        /* STATUS */
        .status-box {
            max-width: 760px;
            min-height: 610px;
            margin: 24px auto 20px;
            background: white;
            border-radius: 14px;
            padding: 34px 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
            text-align: left;
            position: relative;
        }

        .back-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #777;
            border-radius: 10px;
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 34px;
        }

        .status-code {
            font-size: 18px;
            font-weight: 900;
        }

        .status-text {
            color: #F86015;
            font-size: 20px;
            font-weight: 900;
            margin-top: 4px;
        }

        .status-items {
            width: 430px;
            margin-top: 18px;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 12px 0;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            margin: 10px 0;
        }

        .status-total {
            width: 430px;
            display: flex;
            justify-content: space-between;
            font-size: 22px;
            font-weight: 900;
            margin-top: 16px;
        }

        .status-total b {
            color: #0B5B32;
        }

        .progress {
            width: 380px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 28px;
        }

        .progress-step {
            text-align: center;
            color: #aaa;
            font-size: 12px;
            font-weight: 700;
        }

        .progress-dot {
            width: 14px;
            height: 14px;
            background: #ddd;
            border-radius: 50%;
            margin: 0 auto 8px;
        }

        .progress-step.done .progress-dot,
        .progress-step.active .progress-dot {
            background: #F86015;
        }

        .progress-line {
            width: 80px;
            height: 2px;
            background: #ddd;
            margin-top: -20px;
        }

        .progress-line.active {
            background: #F86015;
        }

        /* RIWAYAT */
        .history-box {
            max-width: 900px;
            margin: 14px auto 20px;
        }

        .history-card {
            background: white;
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 16px;
        }

        .history-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .history-code {
            font-size: 17px;
            font-weight: 900;
        }

        .history-date {
            color: #888;
            margin-top: 4px;
        }

        .history-status {
            background: #0B5B32;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 900;
        }

        .history-items {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 14px 0;
            margin: 16px 0;
        }

        .history-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            margin: 10px 0;
        }

        .history-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-total span {
            font-size: 22px;
            font-weight: 900;
        }

        .history-total b {
            color: #0B5B32;
            font-size: 25px;
        }

        .reorder-btn {
            background: #F43A25;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 900;
        }

        /* BOTTOM NAV */
        .bottom-nav {
            position: fixed;
            left: 50%;
            bottom: 22px;
            transform: translateX(-50%);
            z-index: 50;
            width: 900px;
            height: 86px;
            background: white;
            border-radius: 13px;
            box-shadow: 0 0 15px rgba(0,0,0,.38);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            align-items: center;
            padding: 0 20px;
        }

        .bottom-nav a {
            position: relative;
            height: 66px;
            border-radius: 14px;
            text-align: center;
            color: #111;
            font-weight: 900;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .bottom-nav a:hover {
            background: rgba(244, 58, 37, .08);
            transform: translateY(-3px);
        }

        .bottom-nav a.active {
            color: #F43A25;
            background: rgba(244, 58, 37, .10);
        }

        .bottom-nav a.active::before {
            content: '';
            position: absolute;
            top: -10px;
            width: 46px;
            height: 5px;
            border-radius: 99px;
            background: #F43A25;
        }

        .bottom-nav .ico {
            display: block;
            color: #F43A25;
            font-size: 32px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .cart-badge {
            position: absolute;
            top: 4px;
            right: 54px;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 99px;
            background: #F43A25;
            color: white;
            font-size: 12px;
            font-style: normal;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media(max-width: 1000px) {
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .bottom-nav {
                width: 90%;
            }

            .tabs {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

<?php if ($show_app_header) { ?>
<section class="app">
    <header class="user-header">
        <div class="logo">MenuGo</div>

        <div class="header-center">
            <?php if ($title == 'Menu') { ?>
                <h1>Halo <?= htmlspecialchars($nama); ?>!</h1>
                <p>Mau pesan apa hari ini?</p>
            <?php } else { ?>
                <h1 class="page-head-title"><?= htmlspecialchars($title); ?></h1>
            <?php } ?>
        </div>

        <div class="meja-box">
            <div class="meja">Meja <?= htmlspecialchars($meja); ?></div>
            <a href="logout.php" class="logout-user">Keluar</a>
        </div>
    </header>

    <main class="content">
<?php } ?>

<?php
}

function u_footer($show_nav = false, $active = '') {
?>
<?php if ($show_nav) { ?>
    </main>

    <nav class="bottom-nav">
        <a href="menu.php" class="<?= $active == 'menu' ? 'active' : '' ?>">
            <span class="ico">📖</span>
            <span>Menu</span>
        </a>

        <a href="keranjang.php" class="<?= $active == 'keranjang' ? 'active' : '' ?>">
            <span class="ico">🛒</span>
            <span>Pesanan</span>

            <?php if (u_cart_count() > 0) { ?>
                <em class="cart-badge"><?= u_cart_count(); ?></em>
            <?php } ?>
        </a>

        <a href="status.php" class="<?= $active == 'status' ? 'active' : '' ?>">
            <span class="ico">🕘</span>
            <span>Status</span>
        </a>

        <a href="riwayat.php" class="<?= $active == 'riwayat' ? 'active' : '' ?>">
            <span class="ico">↶</span>
            <span>Riwayat</span>
        </a>
    </nav>
</section>
<?php } ?>

</body>
</html>
<?php
}
?>