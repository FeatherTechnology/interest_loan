<?php
require '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';

$status = [
    1 => 'Loan Entry',
    2 => 'Loan Entry',
    3 => 'Loan Approval',
    4 => 'Loan Issued',
    5 => 'Loan Approval',
    6 => 'Loan Approval',
    7 => 'Collection',
    8 => 'Loan Issue',
    9 => 'Loan Issue',
    10 => 'Closed',
    11 => 'Closed',
    12 => 'NOC',
    13 => 'NOC',
    14 => 'NOC'
];

$update_doc_list_arr = array();
$cus_id = $_POST['cus_id'];

$sub_status = [];

if (isset($_POST["sub_status_arr"])) {
    $sub_status = is_array($_POST["sub_status_arr"]) ? $_POST["sub_status_arr"] : explode(',', $_POST["sub_status_arr"]);
}

$qry = $pdo->query("SELECT le.cus_id, le.id as loan_entry_id, le.loan_id, lc.loan_category, le.loan_date, le.loan_amount, cs.closed_date, cs.status as c_sts, cs.sub_status FROM loan_entry le 
LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.id 
LEFT JOIN loan_category lc ON lcc.loan_category = lc.id 
JOIN loan_issue li ON le.id = li.loan_entry_id  
LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id 
WHERE cs.cus_id = '$cus_id' AND li.balance_amount = 0 ");

if ($qry->rowCount() > 0) {
    $i = 1;
    while ($loanInfo = $qry->fetch(PDO::FETCH_ASSOC)) {
        $loanDate = new DateTime($loanInfo['loan_date']);
        $loanInfo['loan_date'] = $loanDate->format('d-m-Y');

        $loanInfo['loan_amount'] = moneyFormatIndia($loanInfo['loan_amount']);

        $closedDate = new DateTime($loanInfo['closed_date']);
        $loanInfo['closed_date'] = $closedDate->format('d-m-Y');

        $originalStatus = $loanInfo['c_sts']; // Convert numeric status to its string representation for display 
        $loanInfo['c_sts'] = isset($status[$originalStatus]) ? $status[$originalStatus] : '';

        $loanCustomerStatus = loanCustomerStatus($pdo, $loanInfo['loan_entry_id'], $sub_status);
        $loanInfo['sub_status']  = $loanCustomerStatus;

        $loanInfo['action'] = '<div class="dropdown">
        <button type="button" class="btn btn-outline-secondary"><i class="fa">&#xf107;</i></button>
        <div class="dropdown-content">';

        if ($originalStatus <= '12') {
            $loanInfo['action'] .= "<a href='#' class='doc-update' value='" . $loanInfo['loan_entry_id'] . "' title='update details'>Update</a>";
        }
        
        $loanInfo['action'] .= "<a href='#' class='doc-print' value='" . $loanInfo['loan_entry_id'] . "' title='print'>Print</a>";

        $loanInfo['action'] .= "</div></div>";

        $update_doc_list_arr[] = $loanInfo; // Append to the array
    }
}

$pdo = null; //Close Connection.
echo json_encode($update_doc_list_arr);

function loanCustomerStatus($pdo, $loan_entry_id, $sub_status = [])
{
    $qry1 = $pdo->query("SELECT le.loan_date, cs.status as cs_status, cs.sub_status as sub_sts
    FROM loan_entry le
    JOIN customer_status cs ON le.id = cs.loan_entry_id
    WHERE le.id = '$loan_entry_id' ORDER BY le.id DESC");

    if ($qry1->rowCount() > 0) {
        $row1 = $qry1->fetch(PDO::FETCH_ASSOC);
        $cs_status = $row1['cs_status'];
        $sub_sts = $row1['sub_sts'];

        $i = 1;
        $status = '';
        if ($cs_status == '1' || $cs_status == '2') {
            $status = 'Loan Entry';
        } elseif ($cs_status == '3') {
            $status = 'In Approval';
        } elseif ($cs_status == '4') {
            $status = 'In Loan Issue';
        } elseif ($cs_status == '5') {
            $status = 'Cancel';
        } elseif ($cs_status == '6') {
            $status = 'Revoke';
        } elseif ($cs_status == '7') {
            $status = isset($sub_status[$i - 1]) ? $sub_status[$i - 1] : 'Null';
        } elseif ($cs_status == '8') {
            $status = 'Cancel';
        } elseif ($cs_status == '9') {
            $status = 'Revoke';
        } elseif ($cs_status == '10') {
            if ($sub_sts == '1') {
                $status = 'Consider';
            } elseif ($sub_sts == '2') {
                $status = 'Rejected';
            }
        } elseif ($cs_status == '11') {
            $status = 'Closed';
        } elseif ($cs_status == '12') {
            $status = 'In NOC';
        } elseif ($cs_status == '13') {
            $status = 'NOC Completed';
        } elseif ($cs_status == '14') {
            $status = 'Removed From NOC';
        }
        return $status;
    }

    return ''; // Default return value if no conditions match
}
