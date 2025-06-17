<?php
require "../../../ajaxconfig.php";
require_once '../../../include/views/money_format_india.php';

$loan_issue_list_arr = array();
$cash_type = $_POST['cash_type'];
$bank_id = $_POST['bank_id'];

if ($cash_type == '1') {
    $cndtn = "and li.payment_mode = '1' ";
} elseif ($cash_type == '2') {
    $cndtn = " and  li.bank_name = '$bank_id' ";
}

//collection_mode = 1 - cash; 2 to 5 - bank;
$current_date = date('Y-m-d');
if ($cash_type == 1) {
    $qry = $pdo->query("SELECT b.name, c.linename,COUNT(DISTINCT li.loan_entry_id) AS no_of_loans, COALESCE(SUM(li.cash),0) AS issueAmnt 
    FROM loan_issue li
    JOIN users b ON li.insert_login_id = b.id 
    JOIN line_name_creation c ON b.line = c.id 
    WHERE DATE(li.issue_date) = '$current_date' $cndtn
    GROUP BY b.name, c.linename, li.insert_login_id; ");
} else {
    $qry = $pdo->query("SELECT b.name, c.linename,COUNT(DISTINCT li.loan_entry_id) AS no_of_loans, COALESCE(SUM(cheque_val) + SUM(transaction_val),0) AS issueAmnt 
    FROM loan_issue li
    JOIN users b ON li.insert_login_id = b.id 
    JOIN line_name_creation c ON b.line = c.id 
    WHERE DATE(li.issue_date) = '$current_date' $cndtn
    GROUP BY b.name, c.linename, li.insert_login_id;");
}

if ($qry->rowCount() > 0) {
    while ($data = $qry->fetch(PDO::FETCH_ASSOC)) {
        $data['no_of_loans'] = ($data['no_of_loans']) ? $data['no_of_loans'] : 0;
        $data['issueAmnt'] = ($data['issueAmnt']) ? moneyFormatIndia($data['issueAmnt']) : 0;
        $loan_issue_list_arr[] = $data;
    }
}

$pdo = null; // Close connection.
echo json_encode($loan_issue_list_arr);
