<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$cus_id = $_POST['cus_id'];
$loan_entry_id = $_POST['loan_entry_id'];
$loan_amount_calc = $_POST['loan_amount_calc'];
$due_startdate_calc = $_POST['due_startdate_calc'];
$maturity_date_calc = $_POST['maturity_date_calc'];
$net_cash = $_POST['net_cash'];
$bal_net_cash = $_POST['bal_net_cash'];
$bal_amount = !empty($_POST['bal_amount']) ? $_POST['bal_amount'] : 0;
$payment_type = $_POST['payment_type'];
$payment_mode = $_POST['payment_mode'];
$bank_name = $_POST['bank_name'];
$transaction_id = $_POST['transaction_id'];
$chequeno = $_POST['chequeno'];
$cash = $_POST['cash'];
$chequeValue = $_POST['chequeValue'];
$chequeRemark = $_POST['chequeRemark'];
$transaction_remark = $_POST['transaction_remark'];
$transaction_value = $_POST['transaction_value'];

$issue_date = $_POST['issue_date'];
$issue_person = $_POST['issue_person'];
$issue_relationship = $_POST['issue_relationship'];

$qry = $pdo->query("INSERT INTO `loan_issue`(`cus_id`, `loan_entry_id`, `loan_amnt`, `net_cash`,`net_bal_cash`,`payment_type`, `payment_mode`, `bank_name`,`cash`,`cheque_val`,`transaction_val`, `transaction_id`, `cheque_no`,`cheque_remark`,`tran_remark`,`balance_amount`, `issue_date`, `issue_person`, `relationship`, `insert_login_id`, `created_on`) VALUES ('$cus_id','$loan_entry_id','$loan_amount_calc','$net_cash','$bal_net_cash','$payment_type','$payment_mode','$bank_name','$cash','$chequeValue','$transaction_value','$transaction_id','$chequeno','$chequeRemark','$transaction_remark','$bal_amount','$issue_date','$issue_person','$issue_relationship','$user_id',now())");

$qry2 = $pdo->query("UPDATE `loan_entry` SET `due_startdate_calc`='$due_startdate_calc',`maturity_date_calc`='$maturity_date_calc',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$loan_entry_id' ");

if ($payment_type == "1") {
    // Check if balance_amount is zero
    if ($bal_amount == 0) {
        $qry3 = $pdo->query("UPDATE `customer_status` SET `status`='7', `update_login_id`='$user_id',`updated_on`=now() WHERE `loan_entry_id`='$loan_entry_id' "); //Loan Issued.
    }
} else if ($payment_type == "2") {
    $qry3 = $pdo->query("UPDATE `customer_status` SET `status`='7', `update_login_id`='$user_id',`updated_on`=now() WHERE `loan_entry_id`='$loan_entry_id' "); //Loan Issued.
}

if ($qry) {
    $result = 1;
} else {
    $result = 0;
}

$pdo = null; //Connection Close.
echo json_encode($result);
