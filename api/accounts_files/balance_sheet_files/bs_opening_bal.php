<?php
require "../../../ajaxconfig.php";

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $userwhere = " AND insert_login_id = '" . $_POST['user_id'] . "' " : $userwhere = ''; //for user based

if ($type == 'today') {
    $current_date = date('Y-m-d');
    $where = " DATE(created_on) <='$current_date' - INTERVAL 1 DAY $userwhere";
    $lewhere = " DATE(li.issue_date) <='$current_date' - INTERVAL 1 DAY $userwhere";
} else if ($type == 'day') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $where = " DATE(created_on) <= DATE('$from_date') - INTERVAL 1 DAY $userwhere";
    $lewhere = " (DATE(li.issue_date) <= DATE('$from_date') - INTERVAL 1 DAY $userwhere ";
} else if ($type == 'month') {
    // Get the selected month (format: YYYY‑MM) and compute the previous month
    $selectedMonth = $_POST['month'];  // e.g., '2025-06'
    $previousMonth = date('Y-m', strtotime('-1 month', strtotime($selectedMonth)));
    // Extract year and month parts
    $year  = date('Y', strtotime($previousMonth));
    $month = date('m', strtotime($previousMonth));
    // Build your filter clause
    $where = "(
        YEAR(created_on) < '$year'
        OR (
            YEAR(created_on) = '$year'
            AND MONTH(created_on) <= '$month'
        )
    ) $userwhere";
    $lewhere = "(
        YEAR(li.issue_date) < '$year'
        OR (
            YEAR(li.issue_date) = '$year'
            AND MONTH(li.issue_date) <= '$month'
        )
    ) $userwhere";
}

$op_data = array();
$op_data[0]['hand_cash'] = 0;
$op_data[0]['bank_cash'] = 0;

// <------------------------------------------------------------------ Collection Credit Hand Cash --------------------------------------------------------->

$c_cr_hc_qry = $pdo->query("SELECT SUM(collection_amnt) AS coll_cr_hc_amnt FROM accounts_collect_entry WHERE collection_mode = 1 AND $where ");
if ($c_cr_hc_qry->rowCount() > 0) {
    $c_cr_hc = $c_cr_hc_qry->fetch()['coll_cr_hc_amnt'];
} else {
    $c_cr_hc = 0;
}

// <------------------------------------------------------------------ Collection Credit Bank Cash --------------------------------------------------------->

$c_cr_bc_qry = $pdo->query("SELECT SUM(collection_amnt) AS coll_cr_bc_amnt FROM accounts_collect_entry WHERE collection_mode = 2 AND $where ");
if ($c_cr_bc_qry->rowCount() > 0) {
    $c_cr_bc = $c_cr_bc_qry->fetch()['coll_cr_bc_amnt'];
} else {
    $c_cr_bc = 0;
}

// <------------------------------------------------------------------ Loan Issue Debit Hand Cash --------------------------------------------------------->

$li_db_hc_qry = $pdo->query("SELECT COALESCE(SUM(cash),0) AS li_db_hc_amt FROM loan_issue WHERE  $where ");
if ($li_db_hc_qry->rowCount() > 0) {
    $li_db_hc = $li_db_hc_qry->fetch()['li_db_hc_amt'];
} else {
    $li_db_hc = 0;
}

// <------------------------------------------------------------------ Loan Issue Debit Bank Cash --------------------------------------------------------->

$li_db_bc_qry = $pdo->query("SELECT COALESCE(SUM(cheque_val) + SUM(transaction_val),0) AS li_db_bc_amnt FROM loan_issue WHERE $where");
if ($li_db_bc_qry->rowCount() > 0) {
    $li_db_bc = $li_db_bc_qry->fetch()['li_db_bc_amnt'];
} else {
    $li_db_bc = 0;
}

// <---------------------------------------------------------------------- Expense Debit Hand Cash --------------------------------------------------------->

$ex_db_hc_qry = $pdo->query("SELECT SUM(amount) AS exp_db_hc_amnt FROM expenses WHERE collection_mode = 1 AND $where ");
if ($ex_db_hc_qry->rowCount() > 0) {
    $ex_db_hc = $ex_db_hc_qry->fetch()['exp_db_hc_amnt'];
} else {
    $ex_db_hc = 0;
}

// <---------------------------------------------------------------------- Expense Debit Bank Cash --------------------------------------------------------->

$ex_db_bc_qry = $pdo->query("SELECT SUM(amount) AS exp_db_bc_amnt FROM expenses WHERE collection_mode = 2 AND $where ");
if ($ex_db_bc_qry->rowCount() > 0) {
    $ex_db_bc = $ex_db_bc_qry->fetch()['exp_db_bc_amnt'];
} else {
    $ex_db_bc = 0;
}

// <---------------------------------------------------------------- Other Transaction Credit Hand Cash ----------------------------------------------------->

$ot_cr_hc_qry = $pdo->query("SELECT SUM(amount) AS ot_cr_hc_amnt FROM other_transaction WHERE collection_mode = 1 AND type = 1 AND $where ");
if ($ot_cr_hc_qry->rowCount() > 0) {
    $ot_cr_hc = $ot_cr_hc_qry->fetch()['ot_cr_hc_amnt'];
} else {
    $ot_cr_hc = 0;
}

// <---------------------------------------------------------------- Other Transaction Debit Hand Cash ----------------------------------------------------->

$ot_db_hc_qry = $pdo->query("SELECT SUM(amount) AS ot_db_hc_amnt FROM other_transaction WHERE collection_mode = 1 AND type = 2 AND $where "); //Hand Cash //debit
if ($ot_db_hc_qry->rowCount() > 0) {
    $ot_db_hc = $ot_db_hc_qry->fetch()['ot_db_hc_amnt'];
} else {
    $ot_db_hc = 0;
}

// <---------------------------------------------------------------- Other Transaction Credit Bank Cash ----------------------------------------------------->

$ot_cr_bc_qry = $pdo->query("SELECT SUM(amount) AS ot_cr_bc_amnt FROM other_transaction WHERE collection_mode = 2 AND type = 1 AND $where "); //Bank Cash //credit
if ($ot_cr_bc_qry->rowCount() > 0) {
    $ot_cr_bc = $ot_cr_bc_qry->fetch()['ot_cr_bc_amnt'];
} else {
    $ot_cr_bc = 0;
}

// <---------------------------------------------------------------- Other Transaction Debit Bank Cash ----------------------------------------------------->

$ot_db_bc_qry = $pdo->query("SELECT SUM(amount) AS ot_db_bc_amnt FROM other_transaction WHERE collection_mode = 2 AND type = 2 AND $where "); //Bank Cash //debit
if ($ot_db_bc_qry->rowCount() > 0) {
    $ot_db_bc = $ot_db_bc_qry->fetch()['ot_db_bc_amnt'];
} else {
    $ot_db_bc = 0;
}

// <-------------------------------------------------------------- Document Charge and Processing Fess ----------------------------------------------------------->

$qry = $pdo->query("SELECT  COALESCE(le.doc_charge_calculate, 0) AS doc_charges, COALESCE(le.processing_fees_calculate, 0) AS proc_charges 
FROM loan_issue li JOIN loan_entry le ON li.loan_entry_id = le.id 
WHERE $lewhere GROUP BY le.id");

$total_doc_charges_cr = 0;
$total_proc_charges_cr = 0;

while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
    $total_doc_charges_cr += $row['doc_charges'];
    $total_proc_charges_cr += $row['proc_charges'];
}

// Calculate cash balances
$hand_cash_credit = intval($c_cr_hc) + intval($ot_cr_hc) + intval($total_doc_charges_cr) + intval($total_proc_charges_cr);
$hand_cash_debit  = intval($ex_db_hc) + intval($ot_db_hc) + intval($li_db_hc);

$bank_cash_credit = intval($c_cr_bc) + intval($ot_cr_bc);
$bank_cash_debit  = intval($ex_db_bc) + intval($ot_db_bc) + intval($li_db_bc);

// Final opening balance
$op_data[0]['hand_cash'] = $hand_cash_credit - $hand_cash_debit;
$op_data[0]['bank_cash'] = $bank_cash_credit - $bank_cash_debit;
$op_data[0]['opening_balance'] = $op_data[0]['hand_cash'] + $op_data[0]['bank_cash'];

$pdo = null; // Close DB connection
echo json_encode($op_data);
