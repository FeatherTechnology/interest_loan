<?php
require "../../ajaxConfig.php";
$le_id = $_POST['le_id'];
$row = array();

$sub_sts = ['' => '', 1 => 'Consider', 2 => 'Reject'];

$qry = $pdo->query("SELECT sub_status, remark FROM customer_status WHERE loan_entry_id = '$le_id' ");

if($qry->rowCount()>0){
    $row = $qry->fetch(PDO::FETCH_ASSOC);
    $row['sub_status'] = $sub_sts[$row['sub_status']];
}

$pdo = null; //Close Connection.
echo json_encode($row);
?>