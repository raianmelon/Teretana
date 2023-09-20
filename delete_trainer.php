<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $trainer_id = $_POST['trainer_id'];

    $sql = 'DELETE FROM trainers WHERE trainer_id = ?';

    $run = $conn->prepare($sql);
    $run->bind_param("i", $trainer_id);
    $run->execute();
    $message = "";

    if ($run->execute()) {
        $message = "Trener je uspjesno obrisan!";
    } else {
        $message = "Trener nije obrisan";
    }

    $_SESSION['success_message'] = $message;
    header('location: admin_dashboard.php');
}