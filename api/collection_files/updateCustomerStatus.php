<?php
require '../../ajaxconfig.php';

$userid = $_POST['userid'];
$le_id = $_POST['le_id'];
$sub_status_customer = $_POST['sub_status_customer'];
$curdate = date('Y-m-d');
$query = $pdo->query("UPDATE `customer_status` SET `collection_status`='$sub_status_customer', `update_login_id`='$userid', `created_on`=NOW() WHERE `loan_entry_id`='$le_id' ");

echo json_encode($query ? 1 : 2);
