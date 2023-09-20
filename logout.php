<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

setcookie("PHPSESSID", "", time() - 3600, "/");
header('location: index.php');
exit();