<?php
require '../../ajaxconfig.php';
@session_start();
$cus_name = $_POST['cus_name'];
$area = $_POST['area'];
$mobile = $_POST['mobile'];
$loan_category = $_POST['loan_category'];
$loan_amount = $_POST['loan_amount'];
$user_id = $_SESSION['user_id'];

$new_promotion_id = isset($_POST['new_promotion_id']) ? $_POST['new_promotion_id'] : '';

$qry = $pdo->query("INSERT INTO `customer_data`(`cus_name`,`area`, `mobile`,`loan_category`,`loan_amount`,`insert_login_id`,`created_on`) VALUES ('$cus_name','$area','$mobile', '$loan_category','$loan_amount','$user_id',now())");

if ($qry) {
    $result = 1; // Insert
} else {
    $result = 0; //Failed
}

$pdo = null; // Close Connection
echo json_encode($result);
