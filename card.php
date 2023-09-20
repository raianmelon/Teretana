<?php
require_once 'config.php';
require_once 'fpdf/fpdf.php';

$member_id = $_GET['member_id'];

if (empty($member_id)) {
    header('Location: admin_dashboard.php');
    die();
}

$sql = "SELECT first_name, last_name, email, phone_number, created_at, photo_path FROM members WHERE member_id = ?";

$run = $conn->prepare($sql);
$run->bind_param("i", $member_id);
$run->execute();

$results = $run->get_result();

$results = $results->fetch_assoc();

$pdf = new FPDF('L', 'mm', array(80, 125));
;
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(40, 12, $member_id . " " . $results['first_name'] . " " . $results['last_name']);
if (!empty($results['photo_path'])) {
    $pdf->Image($results['photo_path'], 85, 12, 30, 40);
}
$pdf->Ln();
$pdf->Cell(40, 12, $results['email']);
$pdf->Ln();
$pdf->Cell(40, 12, $results['phone_number']);
$pdf->Ln();
$pdf->Cell(40, 12, $results['created_at']);

$pdf->Output();