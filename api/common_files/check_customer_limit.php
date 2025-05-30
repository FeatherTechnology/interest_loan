<?php
require "../../ajaxconfig.php";

$loan_entry_id = $_POST['loan_entry_id'];
$result = array();

$qry = $pdo->query("SELECT le.loan_amount, cc.cus_limit  FROM loan_entry le JOIN customer_creation cc ON cc.cus_id = le.cus_id WHERE le.id = '$loan_entry_id'");

if ($qry->rowCount() > 0) {
    $row = $qry->fetch();

    $loan_amount = $row['loan_amount'];
    $cus_limit = $row['cus_limit'];

    if (empty($cus_limit)) {
        $result = 1; // Customer limit is empty
    } elseif ($cus_limit < $loan_amount) {
        $result = 2; // Customer limit is less than loan amount
    } else {
        $result = 3; // All good
    }
}

$pdo = null; // Close connection
echo json_encode($result);
?>
