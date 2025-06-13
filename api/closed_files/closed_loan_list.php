<?php
require '../../ajaxconfig.php';
@session_start();

$user_id = $_SESSION['user_id'];
$cus_id = $_POST['cus_id'];
$loan_list_arr = array();

$status = [10 => 'Closed', 11 => 'Closed'];
$sub_status = ['' => '', 1 => 'Consider', 2 => 'Reject'];

$qry = $pdo->query("SELECT le.id as le_id, le.cus_id, le.loan_id, lc.loan_category, le.loan_date, cs.closed_date, le.loan_amount, cs.status, cs.sub_status
FROM loan_entry le
JOIN customer_creation cc ON cc.cus_id = le.cus_id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
JOIN customer_status cs ON le.id = cs.loan_entry_id
JOIN users u ON FIND_IN_SET(cc.line, u.line)
JOIN users us ON FIND_IN_SET(le.loan_category, us.loan_category)
WHERE le.cus_id = '$cus_id' AND u.id ='$user_id' AND us.id ='$user_id' AND (cs.status = 10 || cs.status = 11) ORDER BY le.id DESC");

if ($qry->rowCount() > 0) {
    while ($loanInfo = $qry->fetch(PDO::FETCH_ASSOC)) {
        $loanDate = new DateTime($loanInfo['loan_date']);
        $loanInfo['loan_date'] = $loanDate->format('d-m-Y');
        $closedDate = new DateTime($loanInfo['closed_date']);
        $loanInfo['closed_date'] = $closedDate->format('d-m-Y');

        $loanInfo['charts'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'><a href='#' class='due-chart' value='" . $loanInfo['le_id'] . "'>Due Chart</a><a href='#' class='penalty-chart' value='" .

        $loanInfo['le_id'] . "'>Penalty Chart</a><a href='#' class='fine-chart' value='" . $loanInfo['le_id'] . "'>Fine Chart</a></div></div>";

        $loanInfo['action'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
        <div class='dropdown-content'>";

        if ($loanInfo['status'] == '10') {

            $loanInfo['action'] .= "<a href='#' class='closed-view' value='" . $loanInfo['le_id'] . "'>Close</a>";
        }

        $loanInfo['action'] .= "</div></div>";
        $loanInfo['status'] = $status[$loanInfo['status']];
        $loanInfo['sub_status'] = $sub_status[$loanInfo['sub_status']];
        $loan_list_arr[] = $loanInfo;
    }
}

$pdo = null; //Close Connection.
echo json_encode($loan_list_arr);
