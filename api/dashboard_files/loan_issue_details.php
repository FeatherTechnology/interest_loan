<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$line_id = $_POST['lineId'];
$response = array();

//Total Loan Issue
$tot_le = "SELECT COALESCE(count(le.id),0) AS total_issue FROM `loan_entry` le JOIN customer_creation cc ON cc.cus_id = le.cus_id JOIN customer_status cs ON le.id = cs.loan_entry_id WHERE cs.status >= 4  AND cs.status NOT IN (5, 6, 8, 9) ";

//Total Loan Issued
$tot_li = "SELECT COALESCE(count(le.id),0) AS total_issued FROM `loan_entry` le JOIN customer_creation cc ON cc.cus_id = le.cus_id JOIN customer_status cs ON le.id = cs.loan_entry_id WHERE cs.status >= 7  ";

//Total Balance
$tot_bal = "SELECT COALESCE(count(le.id),0) AS total_balance FROM `loan_entry` le JOIN customer_creation cc ON cc.cus_id = le.cus_id JOIN customer_status cs ON le.id = cs.loan_entry_id WHERE cs.status = 4 ";

//Today Loan Issue
$today_le = "SELECT COALESCE(count(le.id),0) AS today_issue FROM `loan_entry` le JOIN customer_creation cc ON cc.cus_id = le.cus_id JOIN customer_status cs ON le.id = cs.loan_entry_id WHERE cs.status >= 4  AND cs.status NOT IN (5, 6, 8, 9) AND DATE(cs.updated_on) = CURDATE() ";

//Today Loan Issued
$today_li = "SELECT COALESCE(count(le.id),0) AS today_issued FROM `loan_entry` le JOIN customer_creation cc ON cc.cus_id = le.cus_id JOIN customer_status cs ON le.id = cs.loan_entry_id JOIN loan_issue li ON li.loan_entry_id = le.id WHERE cs.status >= 7  AND DATE(li.issue_date) = CURDATE() ";

//Today Balance
$today_bal = "SELECT COALESCE(count(le.id),0) AS today_balance FROM `loan_entry` le JOIN customer_creation cc ON cc.cus_id = le.cus_id JOIN customer_status cs ON le.id = cs.loan_entry_id WHERE cs.status = 4  AND DATE(cs.updated_on) = CURDATE() ";


if ($line_id != '') {
    $tot_le .= " AND cc.line IN($line_id) ";
    $tot_li .= " AND cc.line IN($line_id) ";
    $tot_bal .= " AND cc.line IN($line_id) ";
    $today_le .= " AND cc.line IN($line_id) ";
    $today_li .= " AND cc.line IN($line_id) ";
    $today_bal .= " AND cc.line IN($line_id) ";
} else {
    $tot_le .= " AND cc.insert_login_id = '$user_id'";
    $tot_li .= " AND cc.insert_login_id = '$user_id'";
    $tot_bal .= " AND cc.insert_login_id = '$user_id'";
    $today_le .= " AND cc.insert_login_id = '$user_id'";
    $today_li .= " AND cc.insert_login_id = '$user_id'";
    $today_bal .= " AND cc.insert_login_id = '$user_id'";
}

$qry = $pdo->query($tot_le);
$response['total_loan_issue'] = $qry->fetch()['total_issue'];

$qry = $pdo->query($tot_li);
$response['total_loan_issued'] = $qry->fetch()['total_issued'];

$qry = $pdo->query($tot_bal);
$response['total_loan_balance'] = $qry->fetch()['total_balance'];

$qry = $pdo->query($today_le);
$response['today_loan_issue'] = $qry->fetch()['today_issue'];

$qry = $pdo->query($today_li);
$response['today_loan_issued'] = $qry->fetch()['today_issued'];

$qry = $pdo->query($today_bal);
$response['today_loan_balance'] = $qry->fetch()['today_balance'];


$pdo = null; //Close Connection.
echo json_encode($response);
