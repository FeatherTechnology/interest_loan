<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$loan_category = $_POST['loan_category'];
$loan_limit = $_POST['loan_limit'];
$status = 1;
$profit_type = $_POST['profit_type'];
$due_method = $_POST['due_method'];
$due_type = $_POST['due_type'];
$benefit_method = $_POST['benefit_method'];
$due_period = $_POST['due_period'];
$interest_calculate = $_POST['interest_calculate'];
$due_calculate = $_POST['due_calculate'];
$interest_rate_min = $_POST['interest_rate_min'];
$interest_rate_max = $_POST['interest_rate_max'];
$document_charge = $_POST['document_charge'];
$doc_charge_min = $_POST['doc_charge_min'];
$doc_charge_max = $_POST['doc_charge_max'];
$processing_fee_type = $_POST['processing_fee_type'];
$processing_fee_min = $_POST['processing_fee_min'];
$processing_fee_max = $_POST['processing_fee_max'];
$overdue_type = $_POST['overdue_type'];
$overdue_penalty = $_POST['overdue_penalty'];
$loan_category_creation_id = $_POST['id'];

$result = 0;
if ($loan_category_creation_id != '') {
    $qry = $pdo->query("UPDATE `loan_category_creation` SET `loan_category`='$loan_category',`loan_limit`='$loan_limit', `status`='$status', `profit_type`='$profit_type', `due_method`='$due_method',`due_type`='$due_type', `benefit_method`='$benefit_method', `due_period`='$due_period', `interest_calculate`='$interest_calculate', `due_calculate`='$due_calculate', `interest_rate_min`='$interest_rate_min',`interest_rate_max`='$interest_rate_max', `document_charge`='$document_charge',`doc_charge_min`='$doc_charge_min',`doc_charge_max`='$doc_charge_max', `processing_fee_type`='$processing_fee_type',  `processing_fee_max`='$processing_fee_max', `processing_fee_min`='$processing_fee_min',`processing_fee_max`='$processing_fee_max', `overdue_type`='$overdue_type', `overdue_penalty`='$overdue_penalty',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$loan_category_creation_id'");
    if ($qry) {
        $result = 1; //Update
    }
} else {
    $qry = $pdo->query("INSERT INTO `loan_category_creation`(`loan_category`, `loan_limit`, `status`, `profit_type`, `due_method`, `due_type`, `benefit_method`, `due_period`, `interest_calculate`, `due_calculate`, `interest_rate_min`, `interest_rate_max`, `document_charge`, `doc_charge_min`, `doc_charge_max`, `processing_fee_type`, `processing_fee_min`, `processing_fee_max`, `overdue_type`, `overdue_penalty`, `insert_login_id`,`created_on`) VALUES ('$loan_category','$loan_limit', '$status', '$profit_type', '$due_method','$due_type', '$benefit_method', '$due_period', '$interest_calculate', '$due_calculate', '$interest_rate_min','$interest_rate_max', '$document_charge', '$doc_charge_min','$doc_charge_max', '$processing_fee_type','$processing_fee_min','$processing_fee_max', '$overdue_type', '$overdue_penalty','$user_id',now())");
    if ($qry) {
        $result = 2; //Insert.
    }
}

$pdo = null; // Close Connection

echo json_encode($result);
