<?php
require '../../ajaxconfig.php';

$cus_profile_id = $_POST['cus_profile_id'];

$qry = $pdo->query(" SELECT li.balance_amount FROM loan_issue li
    WHERE li.loan_entry_id = '$cus_profile_id' 
    ORDER BY li.id DESC
    LIMIT 1");

if ($qry->rowCount() > 0) {
    $result = $qry->fetch(PDO::FETCH_ASSOC);
} else {
    $result = ['balance_amount' => null]; // Default to 0 if no records found
}

$pdo = null; // Close connection
echo json_encode($result);
