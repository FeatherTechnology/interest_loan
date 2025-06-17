<?php
require "../../../ajaxconfig.php";

$op_data = array();
$op_data[0]['hand_cash'] = 0;
$op_data[0]['bank_cash'] = 0;
$current_date = date('Y-m-d');

// <------------------------------------------------------------------ Collection Credit Hand Cash --------------------------------------------------------->

$c_cr_hc_qry = $pdo->query("SELECT SUM(collection_amnt) AS coll_cr_amnt FROM accounts_collect_entry WHERE collection_mode = 1 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY ");
if ($c_cr_hc_qry->rowCount() > 0) {
    $c_cr_hc = $c_cr_hc_qry->fetch()['coll_cr_amnt'];
} else {
    $c_cr_hc = 0;
}

// <------------------------------------------------------------------ Collection Credit Bank Cash --------------------------------------------------------->

$c_cr_bc_qry = $pdo->query("SELECT SUM(collection_amnt) AS coll_cr_amnt FROM accounts_collect_entry WHERE collection_mode = 2 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY ");
if ($c_cr_bc_qry->rowCount() > 0) {
    $c_cr_bc = $c_cr_bc_qry->fetch()['coll_cr_amnt'];
} else {
    $c_cr_bc = 0;
}

// <------------------------------------------------------------------ Loan Issue Debit Hand Cash --------------------------------------------------------->

$li_db_hc_qry = $pdo->query("SELECT COALESCE(SUM(cash),0) AS li_db_hc_amt FROM loan_issue WHERE DATE(created_on)<= '$current_date' - INTERVAL 1 DAY ");
if ($li_db_hc_qry->rowCount() > 0) {
    $li_db_hc = $li_db_hc_qry->fetch()['li_db_hc_amt'];
} else {
    $li_db_hc = 0;
}

// <------------------------------------------------------------------ Loan Issue Debit Bank Cash --------------------------------------------------------->

$li_db_bc_qry = $pdo->query("SELECT COALESCE(SUM(cheque_val) + SUM(transaction_val),0) AS li_db_bc_amnt FROM loan_issue WHERE DATE(created_on) <= '$current_date' - INTERVAL 1 DAY");
if ($li_db_bc_qry->rowCount() > 0) {
    $li_db_bc = $li_db_bc_qry->fetch()['li_db_bc_amnt'];
} else {
    $li_db_bc = 0;
}

// <---------------------------------------------------------------------- Expense Debit Hand Cash --------------------------------------------------------->

$ex_db_hc_qry = $pdo->query("SELECT SUM(amount) AS exp_db_hc_amnt FROM expenses WHERE collection_mode = 1 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY"); 
if ($ex_db_hc_qry->rowCount() > 0) {
    $ex_db_hc = $ex_db_hc_qry->fetch()['exp_db_hc_amnt'];
} else {
    $ex_db_hc = 0;
}

// <---------------------------------------------------------------------- Expense Debit Bank Cash --------------------------------------------------------->

$ex_db_bc_qry = $pdo->query("SELECT SUM(amount) AS exp_db_bc_amnt FROM expenses WHERE collection_mode = 2 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY "); 
if ($ex_db_bc_qry->rowCount() > 0) {
    $ex_db_bc = $ex_db_bc_qry->fetch()['exp_db_bc_amnt'];
} else {
    $ex_db_bc = 0;
}

// <---------------------------------------------------------------- Other Transaction Credit Hand Cash ----------------------------------------------------->

$ot_cr_hc_qry = $pdo->query("SELECT SUM(amount) AS ot_cr_hc_amnt FROM other_transaction WHERE collection_mode = 1 AND type = 1 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY "); 
if ($ot_cr_hc_qry->rowCount() > 0) {
    $ot_cr_hc = $ot_cr_hc_qry->fetch()['ot_cr_hc_amnt'];
} else {
    $ot_cr_hc = 0;
}

// <---------------------------------------------------------------- Other Transaction Debit Hand Cash ----------------------------------------------------->

$ot_db_hc_qry = $pdo->query("SELECT SUM(amount) AS ot_db_hc_amnt FROM other_transaction WHERE collection_mode = 1 AND type = 2 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY"); //Hand Cash //debit
if ($ot_db_hc_qry->rowCount() > 0) {
    $ot_db_hc = $ot_db_hc_qry->fetch()['ot_db_hc_amnt'];
} else {
    $ot_db_hc = 0;
}

// <---------------------------------------------------------------- Other Transaction Credit Bank Cash ----------------------------------------------------->

$ot_cr_bc_qry = $pdo->query("SELECT SUM(amount) AS ot_amnt FROM other_transaction WHERE collection_mode = 2 AND type = 1 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY ");
if ($ot_cr_bc_qry->rowCount() > 0) {
    $ot_cr_bc = $ot_cr_bc_qry->fetch()['ot_amnt'];
} else {
    $ot_cr_bc = 0;
}

// <---------------------------------------------------------------- Other Transaction Debit Bank Cash ----------------------------------------------------->

$ot_db_bc_qry = $pdo->query("SELECT SUM(amount) AS ot_amnt FROM other_transaction WHERE collection_mode = 2 AND type = 2 AND DATE(created_on) <= '$current_date' - INTERVAL 1 DAY"); 
if ($ot_db_bc_qry->rowCount() > 0) {
    $ot_db_bc = $ot_db_bc_qry->fetch()['ot_amnt'];
} else {
    $ot_db_bc = 0;
}

$hand_cash_credit = intval($c_cr_hc) + intval($ot_cr_hc);
$hand_cash_debit = intval($ex_db_hc) + intval($ot_db_hc) + intval($li_db_hc);
$bank_cash_credit = intval($c_cr_bc) + intval($ot_cr_bc);
$bank_cash_debit = intval($ex_db_bc) + intval($ot_db_bc) + intval($li_db_bc);

$op_data[0]['hand_cash'] = intval($hand_cash_credit) - intval($hand_cash_debit);
$op_data[0]['bank_cash'] = intval($bank_cash_credit) - intval($bank_cash_debit);
$op_data[0]['opening_balance'] = $op_data[0]['hand_cash'] + $op_data[0]['bank_cash'];

$pdo = null; // Close connection.
echo json_encode($op_data);
