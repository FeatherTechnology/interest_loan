<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$collection_mode = $_POST['collection_mode'];
$bank_id = $_POST['bank_id'];
$trans_category = $_POST['trans_category'];
$other_trans_name = $_POST['other_trans_name'];
$cat_type = $_POST['cat_type'];
$other_ref_id = $_POST['other_ref_id'];
$other_trans_id = $_POST['other_trans_id'];
$other_amnt = $_POST['other_amnt'];
$other_remark = $_POST['other_remark'];

$qry = $pdo->query("INSERT INTO `other_transaction`( `collection_mode`, `bank_id`, `trans_cat`, `name`, `type`, `ref_id`, `trans_id`, `amount`, `remark`, `insert_login_id`, `created_on`) VALUES ('$collection_mode','$bank_id','$trans_category','$other_trans_name','$cat_type','$other_ref_id','$other_trans_id','$other_amnt','$other_remark','$user_id', now() )");

if ($qry) {
    $result = 1;
} else {
    $result = 2;
}

$pdo = null; // Close connection.
echo json_encode($result);
