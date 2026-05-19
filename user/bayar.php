<?php
require 'user_config.php';
require 'user_layout.php';

u_need_customer();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (empty($_SESSION['draft_order_code'])) {
    $_SESSION['draft_order_code'] = u_make_kode_pesanan();
}

$draftKode = $_SESSION['draft_order_code'];

$totalCart = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalCart += ((int)$item['harga'] * (int)$item['qty']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (!empty($_SESSION['cart'])) {
        $kode    = u_esc($_SESSION['draft_order_code']);
        $nama    = u_esc($_SESSION['customer_name']);
        $meja    = u_esc($_SESSION['nomor_meja'] ?? '');
        $tanggal = date('Y-m-d H:i:s');
        $metode  = u_esc($_POST['metode_bayar'] ?? 'Cash');

        mysqli_query($conn, "
            INSERT INTO pesanan (
                kode_pesanan,
                nama_pelanggan,
                nomor_meja,
                tanggal,
                total,
                metode_bayar,
                status_pesanan
            ) VALUES (
                '$kode',
                '$nama',
                '$meja',
                '$tanggal',
                $totalCart,
                '$metode',
                'Diterima'
            )
        ");

        $idPesanan = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $item) {
            $id_menu  = (int)$item['id_menu'];
            $qty      = (int)$item['qty'];
            $harga    = (int)$item['harga'];
            $subtotal = $qty * $harga;

            mysqli_query($conn, "
                INSERT INTO detail_pesanan (
                    id_pesanan,
                    id_menu,
                    qty,
                    harga,
                    subtotal
                ) VALUES (
                    $idPesanan,
                    $id_menu,
                    $qty,
                    $harga,
                    $subtotal
                )
            ");

            mysqli_query($conn, "
                UPDATE menu
                SET 
                    stok = GREATEST(stok - $qty, 0),
                    status = CASE
                        WHEN GREATEST(stok - $qty, 0) <= 0 THEN 'Habis'
                        ELSE status
                    END
                WHERE id_menu = $id_menu
            ");
        }

        $_SESSION['last_order_id'] = $idPesanan;
        $_SESSION['last_payment_method'] = $metode;

        unset($_SESSION['cart']);
        unset($_SESSION['draft_order_code']);

        header('Location: status.php');
        exit;
    }
}

u_header('Metode Pembayaran', true);
?>

<style>
    .pay-page{
        max-width: 760px;
        margin: 10px auto 140px;
        background: #f3f3f3;
        border-radius: 28px;
        box-shadow: 0 6px 12px rgba(0,0,0,.18);
        padding: 28px 24px 18px;
    }

    .pay-title{
        text-align:center;
        font-size: 40px;
        font-weight: 900;
        color:#111;
        margin: 6px 0 24px;
        text-shadow: 0 3px 5px rgba(0,0,0,.18);
    }

    .pay-total{
        background:#ececec;
        border-radius:18px;
        box-shadow: 0 4px 8px rgba(0,0,0,.16);
        padding: 28px 28px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 18px;
    }

    .pay-total b{
        color:#1d6b38;
        font-size: 28px;
    }

    .method-bar{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 18px;
    }

    .method-btn{
        width: 100%;
        height: 64px;
        border: none;
        background: #f1f1f1;
        border-radius: 16px;
        box-shadow: 0 4px 8px rgba(0,0,0,.16);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s ease;
        font-weight: 900;
    }

    .method-btn.active{
        outline: 3px solid #f43a25;
        background:#fff8f5;
    }

    .method-btn.qris img{
        max-height:36px;
        max-width:150px;
        object-fit:contain;
    }

    .method-btn.cash{
        font-size: 44px;
    }

    .method-panel{
        display:none;
        background:#efefef;
        border-radius:18px;
        box-shadow: 0 4px 8px rgba(0,0,0,.16);
        padding: 14px;
        margin-bottom: 18px;
    }

    .method-panel.active{
        display:block;
    }

    .cash-panel{
        min-height: 360px;
        display:flex;
        align-items:center;
        justify-content:center;
        text-align:center;
        padding: 20px;
    }

    .cash-panel h3{
        font-size: 30px;
        margin: 0 0 8px;
        font-weight: 900;
        color:#111;
    }

    .cash-code{
        font-size: 96px;
        line-height: 1;
        font-weight: 900;
        color:#000;
        letter-spacing: 2px;
        text-shadow: 0 4px 8px rgba(0,0,0,.2);
        margin: 14px 0 18px;
    }

    .cash-note{
        font-size: 20px;
        font-weight: 800;
        color:#111;
    }

    .qris-grid{
        display:grid;
        grid-template-columns: 270px 1fr;
        gap: 14px;
        align-items:stretch;
    }

    .qris-card,
    .qris-note{
        background:#f8f8f8;
        border-radius:16px;
        min-height: 300px;
        border:1px solid #e6e6e6;
    }

    .qris-card{
        display:flex;
        align-items:center;
        justify-content:center;
        padding:16px;
    }

    .qris-card img{
        max-width:100%;
        max-height:280px;
        object-fit:contain;
        border-radius:10px;
    }

    .qris-note{
        display:flex;
        align-items:center;
        justify-content:center;
        text-align:center;
        padding:20px;
        font-size:20px;
        font-weight:800;
        line-height:1.35;
        color:#111;
    }

    .pay-submit{
        width:100%;
        height:60px;
        border:none;
        border-radius:12px;
        background:#f43a25;
        color:#fff;
        font-size:20px;
        font-weight:900;
        cursor:pointer;
        box-shadow: 0 4px 8px rgba(0,0,0,.16);
    }

    .pay-submit:hover{
        opacity:.95;
    }

    .empty-pay{
        text-align:center;
        padding:40px 20px;
    }

    .empty-pay a{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:220px;
        height:52px;
        border-radius:12px;
        background:#34b9a8;
        color:#fff;
        text-decoration:none;
        font-weight:800;
    }

    @media (max-width: 768px){
        .pay-page{
            margin: 10px 14px 130px;
            padding: 20px 16px 16px;
        }

        .pay-title{
            font-size: 34px;
        }

        .pay-total{
            padding: 20px;
            font-size: 18px;
        }

        .pay-total b{
            font-size: 22px;
        }
        
        .method-bar{
            grid-template-columns: 1fr 1fr;
        }

        .qris-grid{
            grid-template-columns: 1fr;
        }

        .cash-code{
            font-size: 58px;
        }

        .cash-panel{
            min-height: 260px;
        }

        .cash-note,
        .qris-note{
            font-size: 18px;
        }
    }
</style>

<div class="pay-page">
    <div class="pay-title">Metode Pembayaran</div>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <div class="pay-total">
            <span>Total Pesanan</span>
            <b><?= u_rupiah($totalCart); ?></b>
        </div>

        <div class="method-bar">
            <button type="button" class="method-btn qris" id="btnQris" onclick="setMetode('QRIS')">
                <?php if (file_exists(__DIR__ . '/../assets/qris-logo.png')) { ?>
                    <img src="../assets/qris-logo.png" alt="QRIS">
                <?php } else { ?>
                    <span style="font-size:34px;">QRIS</span>
                <?php } ?>
            </button>

            <button type="button" class="method-btn cash active" id="btnCash" onclick="setMetode('Cash')">
                💵
            </button>
        </div>

        <!-- CASH -->
        <div class="method-panel active" id="panelCash">
            <div class="cash-panel">
                <div>
                    <h3>Nomor Pesanan Kamu</h3>
                    <div class="cash-code"><?= htmlspecialchars($draftKode) ?></div>
                    <div class="cash-note">
                        Tunjukan nomor ini ke kasir saat membayar
                    </div>
                </div>
            </div>
        </div>

        <!-- QRIS -->
        <div class="method-panel" id="panelQris">
            <div class="qris-grid">
                <div class="qris-card">
                    <?php if (file_exists(__DIR__ . '/../assets/qris.png')) { ?>
                        <img src="../assets/qris.png" alt="QRIS">
                    <?php } else { ?>
                        <div style="text-align:center;">
                            <div style="font-size:34px;font-weight:900;margin-bottom:14px;">QRIS</div>
                            <div style="font-size:90px;">▦</div>
                        </div>
                    <?php } ?>
                </div>

                <div class="qris-note">
                    Scan QR Code di samping<br>
                    menggunakan aplikasi dompet<br>
                    digital kamu
                </div>
            </div>
        </div>

        <form method="post">
            <input type="hidden" name="metode_bayar" id="metodeBayar" value="Cash">
            <button type="submit" name="checkout" class="pay-submit">Bayar Sekarang</button>
        </form>

    <?php } else { ?>
        <div class="empty-pay">
            <h3>Keranjang masih kosong</h3>
            <a href="menu.php">Kembali ke Menu</a>
        </div>
    <?php } ?>
</div>

<script>
function setMetode(metode){
    const btnQris = document.getElementById('btnQris');
    const btnCash = document.getElementById('btnCash');
    const panelQris = document.getElementById('panelQris');
    const panelCash = document.getElementById('panelCash');
    const metodeInput = document.getElementById('metodeBayar');

    metodeInput.value = metode;

    if(metode === 'QRIS'){
        btnQris.classList.add('active');
        btnCash.classList.remove('active');

        panelQris.classList.add('active');
        panelCash.classList.remove('active');
    }else{
        btnCash.classList.add('active');
        btnQris.classList.remove('active');

        panelCash.classList.add('active');
        panelQris.classList.remove('active');
    }
}
</script>

<?php u_footer(true, 'pesanan'); ?>