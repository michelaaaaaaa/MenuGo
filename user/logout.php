<?php
require 'user_config.php';

unset($_SESSION['customer_name']);
unset($_SESSION['nomor_meja']);
unset($_SESSION['cart']);
unset($_SESSION['last_order_id']);

header('Location: index.php');
exit;