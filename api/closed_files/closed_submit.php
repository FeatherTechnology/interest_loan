<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];

$loan_entry_id = $_POST['loan_entry_id'];
$sub_status = $_POST['sub_status'];
$remark = $_POST['remark'];

$qry = $pdo->query("UPDATE `customer_status` SET `status`='11',`sub_status`='$sub_status',`closed_date`=now(),`remark`='$remark',`update_login_id`='$user_id',`updated_on`=now() WHERE `loan_entry_id`='$loan_entry_id' ");

if ($qry) {
    $result = 1; //success
} else {
    $result = 2; //failed

}

$pdo = null; //Close Connection
echo json_encode($result);
