<?php
require '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';
@session_start();
$user_id = $_SESSION['user_id'];
$cus_id = $_POST['cus_id'];

$sub_sts = ['' => '', 1 => 'Consider', 2 => 'Reject'];

$loan_list_arr = array();
$qry = $pdo->query("SELECT le.id as le_id, le.cus_id, le.loan_id, lc.loan_category, li.issue_date, cs.closed_date, le.loan_amount, cs.sub_status
FROM loan_entry le
JOIN customer_creation cc ON le.cus_id = cc.cus_id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
JOIN customer_status cs ON le.id = cs.loan_entry_id
JOIN loan_issue li ON le.id = li.loan_entry_id
JOIN users u ON FIND_IN_SET(cc.line, u.line)
JOIN users urs ON FIND_IN_SET(le.loan_category, urs.loan_category)
WHERE li.balance_amount = 0 AND le.cus_id = '$cus_id' AND (cs.status = 12 OR cs.status = 13) AND u.id ='$user_id' AND urs.id ='$user_id' ORDER BY le.id DESC ");

if ($qry->rowCount() > 0) {
    while ($loanInfo = $qry->fetch(PDO::FETCH_ASSOC)) {
        $loanInfo['issue_date'] = date('d-m-Y', strtotime($loanInfo['issue_date']));
        $loanInfo['closed_date'] = date('d-m-Y', strtotime($loanInfo['closed_date']));
        $loanInfo['loan_amount'] = moneyFormatIndia($loanInfo['loan_amount']);

        $loanInfo['status'] = 'Closed';
        $loanInfo['sub_status'] = $sub_sts[$loanInfo['sub_status']];

        $loanInfo['action'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'><a href='#' class='noc-summary' value='" . $loanInfo['le_id'] . "' title='Edit details'>NOC Summary</a><a href='#' data-toggle='modal' data-target='#closed_remark_model' id='remark_view' value='" . $loanInfo['le_id'] . "' title='Edit details'>Remark View</a></div></div>";

        $loan_list_arr[] = $loanInfo; // Append to the array
    }
}

$pdo = null; //Close Connection.
echo json_encode($loan_list_arr);
