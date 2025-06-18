<?php
require "../../../ajaxconfig.php";

$type = $_POST['type'];

if ($_POST['user_id'] != '') {
    $userwhere = " AND insert_login_id = '" . $_POST['user_id'] . "' "; //for user based    
    $lelcuserswhere = " AND li.insert_login_id = '" . $_POST['user_id'] . "' "; //for user based    
} else {
    $userwhere = '';
    $lelcuserswhere = '';
}

if ($type == 'today') {
    $where = " DATE(created_on) = CURRENT_DATE $userwhere";
    $collwhere = " DATE(created_date) = CURRENT_DATE $userwhere";
    $lelcwhere = " DATE(li.issue_date) = CURRENT_DATE $lelcuserswhere";
} else if ($type == 'day') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $where = " (DATE(created_on) >= '$from_date' && DATE(created_on) <= '$to_date' ) $userwhere ";
    $collwhere = " (DATE(created_date) >= '$from_date' && DATE(created_date) <= '$to_date' ) $userwhere ";
    $lelcwhere = " (DATE(li.issue_date) >= '$from_date' && DATE(li.issue_date) <= '$to_date' ) $lelcuserswhere ";
} else if ($type == 'month') {
    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));
    $where = " (MONTH(created_on) = '$month' AND YEAR(created_on) = $year) $userwhere";
    $collwhere = " (MONTH(created_date) = '$month' AND YEAR(created_date) = $year) $userwhere";
    $lelcwhere = " (MONTH(li.issue_date) = '$month' AND YEAR(li.issue_date) = $year) $lelcuserswhere";
}

$result = array();

$qry = $pdo->query("SELECT COALESCE(le.interest_amnt_calc, 0) AS interest, COALESCE(le.doc_charge_calculate, 0) AS doc_charges, COALESCE(le.processing_fees_calculate, 0) AS proc_charges FROM loan_issue li JOIN loan_entry le ON li.loan_entry_id = le.id WHERE $lelcwhere  GROUP BY le.id"); // Group by each loan entry

// Initialize totals
$total_interest = 0;
$total_doc_charges = 0;
$total_proc_charges = 0;

// Loop through all rows and accumulate
while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
    $total_interest += $row['interest'];
    $total_doc_charges += $row['doc_charges'];
    $total_proc_charges += $row['proc_charges'];
}

$qry2 = $pdo->query("SELECT COALESCE(SUM(penalty_track),0) AS penalty, COALESCE(SUM(fine_charge_track),0) AS fine FROM `collection` WHERE $collwhere "); //Collection 

if ($qry2->rowCount() > 0) {
    $row = $qry2->fetch(PDO::FETCH_ASSOC);
    $penalty = $row['penalty'];
    $fine = $row['fine'];
}

$qry3 = $pdo->query("SELECT COALESCE(SUM(amount),0) AS oi_dr FROM `other_transaction` WHERE trans_cat ='8' AND type = '1' AND $where "); //Other Income 

if ($qry3->rowCount() > 0) {
    $oicr = $qry3->fetch(PDO::FETCH_ASSOC)['oi_dr'];
}

$qry4 = $pdo->query("SELECT COALESCE(SUM(amount),0) AS exp_dr FROM `expenses` WHERE $where "); //Expenses

if ($qry4->rowCount() > 0) {
    $expdr = $qry4->fetch(PDO::FETCH_ASSOC)['exp_dr'];
}

$result[0]['interest'] = $total_interest;
$result[0]['doc_charges'] = $total_doc_charges;
$result[0]['proc_charges']  = $total_proc_charges;
$result[0]['penalty'] = $penalty;
$result[0]['fine'] = $fine;
$result[0]['oicr'] = $oicr;
$result[0]['expdr']  = $expdr;

$pdo = null; //Close connection.
echo json_encode($result);
