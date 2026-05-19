<?php
require 'user_config.php';
require 'user_layout.php';

u_need_customer();

if (isset($_GET['add'])) {
    $id_menu = (int)$_GET['add'];

    $q = mysqli_query($conn, "
        SELECT m.*, k.nama_kategori
        FROM menu m
        JOIN kategori k ON m.id_kategori = k.id_kategori
        WHERE m.id_menu = $id_menu
        AND m.status = 'Tersedia'
        AND m.stok > 0
    ");

    $m = mysqli_fetch_assoc($q);

    if ($m) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id_menu])) {
            $_SESSION['cart'][$id_menu]['qty']++;
        } else {
            $_SESSION['cart'][$id_menu] = [
                'id_menu' => $m['id_menu'],
                'nama_menu' => $m['nama_menu'],
                'harga' => $m['harga'],
                'foto' => $m['foto'],
                'qty' => 1
            ];
        }
    }

    header('Location: menu.php');
    exit;
}

$kategoriAktif = $_GET['kategori'] ?? 'Semua';

$where = "WHERE m.status='Tersedia' AND m.stok > 0";

if ($kategoriAktif != 'Semua') {
    $katEsc = u_esc($kategoriAktif);
    $where .= " AND k.nama_kategori='$katEsc'";
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori ASC");

$menu = mysqli_query($conn, "
    SELECT m.*, k.nama_kategori
    FROM menu m
    JOIN kategori k ON m.id_kategori = k.id_kategori
    $where
    ORDER BY m.id_menu DESC
");

u_header('Menu', true);
?>

<div class="tabs">
    <a class="tab <?= $kategoriAktif == 'Semua' ? 'active' : '' ?>" href="menu.php?kategori=Semua">
        Semua
    </a>

    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
        <a
            class="tab <?= $kategoriAktif == $k['nama_kategori'] ? 'active' : '' ?>"
            href="menu.php?kategori=<?= urlencode($k['nama_kategori']) ?>"
        >
            <?= htmlspecialchars($k['nama_kategori']) ?>
        </a>
    <?php } ?>
</div>

<div class="menu-grid">
    <?php if (mysqli_num_rows($menu) > 0) { ?>
        <?php while ($m = mysqli_fetch_assoc($menu)) { ?>
            <div class="menu-card">
                <?php if (!empty($m['foto'])) { ?>
                    <img src="../<?= htmlspecialchars($m['foto']) ?>" alt="<?= htmlspecialchars($m['nama_menu']) ?>">
                <?php } else { ?>
                    <div class="no-img">🍽️</div>
                <?php } ?>

                <div class="menu-body">
                    <h3><?= htmlspecialchars($m['nama_menu']) ?></h3>
                    <p><?= htmlspecialchars($m['deskripsi']) ?></p>
                    <div class="menu-price"><?= u_rupiah($m['harga']) ?></div>
                </div>

                <a class="add-btn" href="menu.php?add=<?= $m['id_menu'] ?>">+</a>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="empty" style="grid-column: 1 / -1;">
            Menu belum tersedia.
        </div>
    <?php } ?>
</div>

<?php u_footer(true, 'menu'); ?>