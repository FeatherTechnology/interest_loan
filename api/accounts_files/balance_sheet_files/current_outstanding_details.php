<?php
require "../../../ajaxconfig.php";
$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $userwhere = " AND insert_login_id = '" . $_POST['user_id'] . "' " : $userwhere = ''; //for user based

if ($type == 'today') {

    $where = " DATE(li.created_on) <= CURRENT_DATE and cs.status = 7 $userwhere";
    $coll_where = " DATE(c.created_date) <=  CURRENT_DATE and cs.status = 7 $userwhere ";
    $ac_c_e_where = " DATE(created_on) <=  CURRENT_DATE $userwhere ";
} else if ($type == 'day') {

    $to_date = $_POST['to_date'];
    $where = " (DATE(li.created_on) <= '$to_date' ) and cs.status = 7 $userwhere ";
    $coll_where = " (DATE(c.created_date) <= '$to_date' ) and cs.status = 7 $userwhere ";
    $ac_c_e_where = " (DATE(created_on) <= '$to_date' ) $userwhere ";
} else if ($type == 'month') {

    // Get the selected month (format: YYYY‑MM) and compute the previous month
    $selectedMonth = $_POST['month'];  // e.g., '2025-06'
    // Extract year and month parts
    $year  = date('Y', strtotime($selectedMonth));
    $month = date('m', strtotime($selectedMonth));
    // Build your filter clause
    $where = "(
        YEAR(li.created_on) < '$year'
        OR (
            YEAR(li.created_on) = '$year'
            AND MONTH(li.created_on) <= '$month'
        )
    ) AND cs.status = 7 $userwhere";

    $coll_where = "(
        YEAR(c.created_date) < '$year'
        OR (
            YEAR(c.created_date) = '$year'
            AND MONTH(c.created_date) <= '$month'
        )
    ) AND cs.status = 7 $userwhere";

    $ac_c_e_where = "(
        YEAR(created_on) < '$year'
        OR (
            YEAR(created_on) = '$year'
            AND MONTH(created_on) <= '$month'
        )
    ) $userwhere";
}

$result = array();

// <------------------------------------------------------------------------ Pre Outstanding Debit ----------------------------------------------------------->

// Query 1
$qry = $pdo->query("SELECT COALESCE(SUM(li.loan_amnt), 0) AS li_loan_amt_db 
FROM `loan_issue` li 
LEFT JOIN customer_status cs ON li.loan_entry_id = cs.loan_entry_id 
WHERE $where AND li.balance_amount = 0 AND cs.status = 7");

$li_loan_amt_db = ($qry->rowCount() > 0) ? $qry->fetch(PDO::FETCH_ASSOC)['li_loan_amt_db'] : 0;

// Query 2
$qry = $pdo->query("SELECT COALESCE(SUM(c.principal_amount_track), 0) AS c_princ_amt_track 
FROM `collection` c 
LEFT JOIN customer_status cs ON c.loan_entry_id = cs.loan_entry_id 
WHERE $coll_where AND cs.status = 7");

$c_princ_amt_track = ($qry->rowCount() > 0) ? $qry->fetch(PDO::FETCH_ASSOC)['c_princ_amt_track'] : 0;

// Opening Outstanding Calculation
$pre_outstanding_db = intval($li_loan_amt_db) - intval($c_princ_amt_track);
$result[0]['pre_outstanding_db'] = $pre_outstanding_db;

// <------------------------------------------------------------------------ Pre Accounts Collect Debit --------------------------------------------------------->

// Query 3
$qry = $pdo->query("SELECT COALESCE(SUM(collection_amnt), 0) AS coll_amnt_db
FROM `accounts_collect_entry` WHERE $ac_c_e_where");

$pre_acc_c_db = ($qry->rowCount() > 0) ? $qry->fetch(PDO::FETCH_ASSOC)['coll_amnt_db'] : 0;

$result[0]['pre_acc_c_db'] = $pre_acc_c_db;

$pdo = null; //Close connection.
echo json_encode($result);
