<?php
require  '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$le_id = $_POST['le_id'];
$records = array();

$qry = $pdo->query("SELECT le.loan_category
FROM loan_entry le
JOIN customer_status cs ON le.id = cs.loan_entry_id
WHERE le.id = '$le_id' AND cs.status = 7 ");

if ($qry->rowCount() > 0) {
    $row = $qry->fetch();
    $records['loan_category'] = $row["loan_category"];
}

$qry1 = $pdo->query("SELECT collection_access FROM users WHERE id = '$user_id' ");

if ($qry1->rowCount() > 0) {
    $row1 = $qry1->fetch();
    $records['collection_access'] = $row1["collection_access"]; //1=YES, 2 =NO//
}

$pdo = null; //Close Connection
echo json_encode($records);
