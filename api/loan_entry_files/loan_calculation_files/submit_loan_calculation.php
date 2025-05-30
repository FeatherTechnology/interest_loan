<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$loan_id_calc = $_POST['loan_id_calc'];
$loan_category_calc = $_POST['loan_category_calc'];
$loan_amount_calc = $_POST['loan_amount_calc'];
$benefit_method = $_POST['benefit_method'];
$due_method = $_POST['due_method'];
$due_period = $_POST['due_period'];
$interest_calculate = $_POST['interest_calculate'];
$due_calculate = $_POST['due_calculate'];
$interest_rate_calc = $_POST['interest_rate_calc'];
$due_period_calc = $_POST['due_period_calc'];
$doc_charge_calc = $_POST['doc_charge_calc'];
$processing_fees_calc = $_POST['processing_fees_calc'];
$loan_amnt_calc = $_POST['loan_amnt_calc'];
$doc_charge_calculate = $_POST['doc_charge_calculate'];
$processing_fees_calculate = $_POST['processing_fees_calculate'];
$net_cash_calc = $_POST['net_cash_calc'];
$interest_amnt_calc = $_POST['interest_amnt_calc'];
$loan_date_calc = $_POST['loan_date_calc'];
$due_startdate_calc = $_POST['due_startdate_calc'];
$maturity_date_calc = $_POST['maturity_date_calc'];
$referred_calc = $_POST['referred_calc'];
$agent_id_calc = $_POST['agent_id_calc'];
$agent_name_calc = $_POST['agent_name_calc'];
$id = $_POST['id'];
$cus_status = $_POST['cus_status'];

$status = 0;
if ($id != '') {
    $qry = $pdo->query("UPDATE `loan_entry` SET  `loan_id`='$loan_id_calc',`loan_category`='$loan_category_calc', `loan_amount`='$loan_amount_calc',`benefit_method`='$benefit_method',`due_method`='$due_method',`due_period`='$due_period',`interest_calculate`='$interest_calculate', `due_calculate`='$due_calculate',`interest_rate_calc`='$interest_rate_calc',`due_period_calc`='$due_period_calc',`doc_charge_calc`='$doc_charge_calc',`processing_fees_calc`='$processing_fees_calc',`loan_amnt_calc`='$loan_amnt_calc',`doc_charge_calculate`='$doc_charge_calculate',`processing_fees_calculate`='$processing_fees_calculate',`net_cash_calc`='$net_cash_calc',`interest_amnt_calc`='$interest_amnt_calc',`loan_date_calc`='$loan_date_calc',`due_startdate_calc`='$due_startdate_calc',`maturity_date_calc`='$maturity_date_calc', `referred_calc`='$referred_calc', `agent_id_calc`='$agent_id_calc',`agent_name_calc`='$agent_name_calc',`update_login_id`='$user_id',`updated_on`=NOW() WHERE `id`='$id'");

    if ($qry) {
        $status = 2;
        $qry = $pdo->query("UPDATE `customer_status` SET `status` = '$cus_status', `update_login_id` = '$user_id', `updated_on` = NOW() WHERE `loan_entry_id` = '$id' AND status = 1 ");
    }
}


$result = array('status' => $status);

$pdo = null; //Connection Close.
echo json_encode($result);
