<?php
require '../../ajaxconfig.php';
$le_id = $_POST['le_id'];

$qry = $pdo->query("SELECT lcc.profit_type , le.due_method , lcc.due_type ,  CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name,  le.cus_id , le.loan_id FROM loan_entry le LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.loan_category LEFT JOIN customer_creation cc ON cc.cus_id = le.cus_id where le.id = '$le_id' ");

$row = $qry->fetch();
$cus_name = $row['cus_name'];
$cus_id = $row['cus_id'];
$loan_id = $row['loan_id'];
$profit_type = $row['profit_type'];
$due_method = $row['due_method'];

if ($profit_type == 'Calculation') {
    $response['cus_name'] = $row['cus_name'];
    $response['cus_id'] = $row['cus_id'];
    $response['loan_id'] = $row['loan_id'];
    $response['due_method'] = 'Monthly';
    $response['loan_type'] = $row['due_type'];
}

$pdo = null; //Close Connection
echo json_encode($response);
