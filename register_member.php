<?php

require_once 'config.php';
require_once 'fpdf/fpdf.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $training_plan_id = $_POST['training_plan_id'];
    $photo_path = $_POST['photo_path'];
    $trainer_id = 0;

    if ($photo_path == "") {
        $photo_path = 'member_photos/guest.png';
    }

    $sql = "INSERT INTO members 
        (first_name, last_name, email, phone_number, photo_path, training_plan_id, trainer_id)
        VALUES(?, ?, ?, ?, ?, ?, ?)";

    $run = $conn->prepare($sql);
    $run->bind_param("sssssii", $first_name, $last_name, $email, $phone_number, $photo_path, $training_plan_id, $trainer_id);
    $run->execute();

    $_SESSION['success_message'] = 'Clan teretane je uspjesno dodan!';
    header('location: admin_dashboard.php');
    exit();
}