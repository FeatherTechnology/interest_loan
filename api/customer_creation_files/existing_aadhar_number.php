<?php
require "../../ajaxconfig.php";

$aadhar_number = $_POST['aadhar_number'] ?? '';

$qry = $pdo->prepare("SELECT aadhar_number FROM customer_creation WHERE aadhar_number = ?");
$success = $qry->execute([$aadhar_number]);

$result = ["exists" => false];

if ($success) {
    $row = $qry->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['aadhar_number'] > 0) {
        $result["exists"] = true;
    }
}

$pdo = null; // Close Connection
echo json_encode($result);
