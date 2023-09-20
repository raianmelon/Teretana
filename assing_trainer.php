<?php

require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $member_id = $_POST['member_id'];
    $trainer_id = $_POST['trainer_id'];

    echo $member_id . " " . $trainer_id;

    $sql = 'UPDATE members SET trainer_id = ? WHERE member_id = ?';

    $run = $conn->prepare($sql);
    $run->bind_param("ii", $trainer_id, $member_id);
    $run->execute();
    $message = "";

    if ($run->execute()) {
        $message = "Trener je uspjesno dodjeljen!";
    } else {
        $message = "Trener nije dodjeljen";
    }

    $_SESSION['success_message'] = $message;
    header('location: admin_dashboard.php');
}