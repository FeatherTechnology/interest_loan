<?php
require '../../ajaxconfig.php';
$le_id = $_POST['le_id'];

$qry = $pdo->query("SELECT lcc.profit_type , le.due_method , lcc.due_type FROM loan_entry le LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.loan_category     where le.id = '$le_id' ");

$row = $qry->fetch();
$profit_type = $row['profit_type'];
$due_method = $row['due_method'];

if ($profit_type == 'Calculation') {
    $response['due_method'] = 'Monthly';
    $response['loan_type'] = $row['due_type'];
}

$pdo = null; //Close Connection
echo json_encode($response);
