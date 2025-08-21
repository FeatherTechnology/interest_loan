<?php
require "../../ajaxconfig.php";

$id = $_POST['id'];

try {
    $qry = $pdo->prepare("UPDATE `loan_category_creation` SET status=0 WHERE id = :id");
    $qry->bindParam(':id', $id, PDO::PARAM_INT);
    $qry->execute();

    $result = '0'; // Disabled successfully
} catch (PDOException $e) {
    $result = '-1'; // General error
}

$pdo = null; // Close Connection

echo json_encode($result);