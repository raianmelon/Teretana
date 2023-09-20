<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $member_id = $_POST['member_id'];

    $sql = 'DELETE FROM members WHERE member_id = ?';

    $run = $conn->prepare($sql);
    $run->bind_param("i", $member_id);
    $run->execute();
    $message = "";

    if ($run->execute()) {
        $message = "Clan je uspjesno obrisan!";
    } else {
        $message = "Clan nije obrisan";
    }

    $_SESSION['success_message'] = $message;
    header('location: admin_dashboard.php');
}