<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$cus_id = $_POST['cus_id'];

$sub_status = [];

if (isset($_POST["sub_status"])) {
    $sub_status = is_array($_POST["sub_status"]) ? $_POST["sub_status"] : explode(',', $_POST["sub_status"]);
}

$bal_amt = explode(',', $_POST["bal_amt"]);

function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

$loan_list_arr = array();

$qry = $pdo->query("SELECT le.id as le_id, le.cus_id, le.loan_id, lc.loan_category, li.issue_date, le.loan_amount, us.collection_access
FROM loan_entry le
JOIN customer_creation cc ON le.cus_id = cc.cus_id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
JOIN customer_status cs ON le.id = cs.loan_entry_id
JOIN loan_issue li ON le.id = li.loan_entry_id  
LEFT JOIN users us ON us.id = '$user_id'
JOIN users u ON FIND_IN_SET(cc.line, u.line)
JOIN users urs ON FIND_IN_SET(le.loan_category, urs.loan_category)
WHERE le.cus_id = '$cus_id' AND cs.status = 7 AND u.id ='$user_id' AND urs.id ='$user_id' AND li.balance_amount = 0 ORDER BY le.id DESC ");

if ($qry->rowCount() > 0) {
    $curdate = date('Y-m-d');
    $i = 1;
    while ($loanInfo = $qry->fetch(PDO::FETCH_ASSOC)) {
        $loanInfo['issue_date'] = date('d-m-Y', strtotime($loanInfo['issue_date']));
        $loanInfo['loan_amount'] = moneyFormatIndia($loanInfo['loan_amount']);
        $loanInfo['bal_amount'] = moneyFormatIndia($bal_amt[$i - 1]);
        $loanInfo['status'] = 'Present';

        $loanInfo['sub_status'] = isset($sub_status[$i - 1]) ? $sub_status[$i - 1] : 'Null';

        $loanInfo['charts'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'><a href='#' class='due-chart' value='" . $loanInfo['le_id'] . "'>Due Chart</a><a href='#' class='penalty-chart' value='" . $loanInfo['le_id'] . "'>Penalty Chart</a><a href='#' class='fine-chart' value='" . $loanInfo['le_id'] . "'>Fine Chart</a></div></div>";

        $loanInfo['action'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i><div class='dropdown-content'><a href='#' class='pay-due' value='" . $loanInfo['le_id'] . "'>Pay Due</a>";

        if ($loanInfo['collection_access'] == 1) {
            $loanInfo['action'] .= "<a href='#' class='fine-form' value='" . $loanInfo['le_id'] . "'>Fine</a>";
        }
        $loanInfo['action'] .= "</div></div>";

        $loan_list_arr[] = $loanInfo; // Append to the array

        $i++;
    }
}

$pdo = null; //Close Connection.
echo json_encode($loan_list_arr);
