<?php
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function u_esc($value) {
    global $conn;
    return mysqli_real_escape_string($conn, $value);
}

function u_rupiah($angka) {
    return 'Rp ' . number_format((int)$angka, 0, ',', '.');
}

function u_cart_count() {
    $total = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += (int)$item['qty'];
        }
    }

    return $total;
}

function u_need_customer() {
    if (!isset($_SESSION['customer_name'])) {
        header('Location: mulai.php');
        exit;
    }
}

function u_make_kode_pesanan() {
    global $conn;

    $q = mysqli_query($conn, "SELECT MAX(id_pesanan) AS last_id FROM pesanan");
    $r = mysqli_fetch_assoc($q);

    $next = ((int)$r['last_id']) + 1;

    return '#MG-' . str_pad($next, 4, '0', STR_PAD_LEFT);
}

function u_order_items_text($id_pesanan) {
    global $conn;

    $id_pesanan = (int)$id_pesanan;

    $q = mysqli_query($conn, "
        SELECT d.qty, m.nama_menu
        FROM detail_pesanan d
        JOIN menu m ON d.id_menu = m.id_menu
        WHERE d.id_pesanan = $id_pesanan
    ");

    $items = [];

    while ($r = mysqli_fetch_assoc($q)) {
        $items[] = $r['nama_menu'] . ' x' . $r['qty'];
    }

    return implode(', ', $items);
}

function u_latest_order() {
    global $conn;

    if (!isset($_SESSION['customer_name'])) {
        return null;
    }

    $nama = u_esc($_SESSION['customer_name']);
    $meja = u_esc($_SESSION['nomor_meja'] ?? '');

    $q = mysqli_query($conn, "
        SELECT *
        FROM pesanan
        WHERE nama_pelanggan = '$nama'
        AND nomor_meja = '$meja'
        ORDER BY tanggal DESC
        LIMIT 1
    ");

    return mysqli_fetch_assoc($q);
}