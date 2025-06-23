<?php
require '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';

$cus_id = isset($_POST['cus_id']) ? $_POST['cus_id'] : null;
$aadhar_number = isset($_POST['aadhar_number']) ? trim($_POST['aadhar_number']) : '';
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$area = isset($_POST['area']) ? trim($_POST['area']) : '';
$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';

$sub_status = [];

if (isset($_POST["sub_status_arr"])) {
    $sub_status = is_array($_POST["sub_status_arr"]) ? $_POST["sub_status_arr"] : explode(',', $_POST["sub_status_arr"]);
}

$loan_list_arr = array();

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

$whereClause = "WHERE 1"; // Initial WHERE clause

if (!empty($cus_id)) {
    $whereClause .= " AND le.cus_id = '$cus_id'";
} elseif (!empty($aadhar_number)) {
    $whereClause .= " AND le.cus_id IN (SELECT cus_id FROM customer_creation WHERE aadhar_number LIKE '%$aadhar_number%')";
} elseif (!empty($first_name)) {
    $whereClause .= " AND le.cus_id IN (SELECT cus_id FROM customer_creation WHERE first_name LIKE '%$first_name%')";
} elseif (!empty($area)) {
    $whereClause .= " AND le.cus_id IN (
        SELECT cc.cus_id
        FROM customer_creation cc
        LEFT JOIN area_name_creation anc ON cc.area = anc.id
        WHERE anc.areaname LIKE '%$area%'
    )";
} elseif (!empty($mobile)) {
    $whereClause .= " AND le.cus_id IN (SELECT cus_id FROM customer_creation WHERE mobile1 LIKE '%$mobile%')";
}

$qry = $pdo->query("SELECT le.id as loan_entry_id, le.cus_id, le.loan_date, le.loan_id, lc.loan_category, le.loan_amount, cs.status, cs.sub_status
    FROM loan_entry le
    JOIN loan_category_creation lcc ON le.loan_category = lcc.id
    JOIN loan_category lc ON lcc.loan_category = lc.id
    JOIN customer_status cs ON le.id = cs.loan_entry_id
    $whereClause ORDER BY le.id DESC");

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $response = array();
        $loanDate = new DateTime($row['loan_date']);
        $response['loan_date'] = $loanDate->format('d-m-Y');

        $response['loan_id'] = $row['loan_id'];
        $response['loan_category'] = $row['loan_category'];
        $response['loan_amount'] = moneyFormatIndia($row['loan_amount']);
        $response['status'] = $status[$row['status']];

        // Calculate loan customer status and store in variable
        $loanCustomerStatus = loanCustomerStatus($pdo, $row['loan_entry_id'], $sub_status);
        $response['sub_status']  = $loanCustomerStatus;

        $response['info'] = "<div class='dropdown'>
            <button class='btn btn-outline-secondary'>
                <i class='fa'>&#xf107;</i>
            </button>
            <div class='dropdown-content'>";

        $response['info'] .=  "<a href='#' class='customer-profile' value='" . $row['loan_entry_id'] . "'>Customer Profile</a>";
        $response['info'] .=  "  <a href='#' class='loan-calculation' value='" . $row['loan_entry_id'] . "'>Loan Calculation</a>";

        if ($row['status'] >= '7') {
            $response['info'] .=  " <a href='#' class='documentation' value='" . $row['loan_entry_id'] . "'>Documentation</a>";
        }

        if ($row['status'] >= '10') {
            $response['info'] .=  " <a href='#' class='closed-remark' value='" . $row['loan_entry_id'] . "'>Remark View</a>";
        }

        if ($row['status'] == '12' || $row['status'] == '13') {
            $response['info'] .=  " <a href='#' class='noc-summary' value='" . $row['loan_entry_id'] . "'>Noc Summary</a>";
        }

        $response['info'] .=  "  </div> </div>";

        if ($row['status'] < 7) { // Condition: Less than 7
            $response['charts'] = "<div class='dropdown'>
                <button class='btn btn-outline-secondary' disabled>
                    <i class='fa'>&#xf107;</i>
                </button>
                <div class='dropdown-content'>
                    <a href='#' class='due-chart' value='" . $row['loan_entry_id'] . "' style='pointer-events: none; color: gray;'>Due Chart</a>
                    <a href='#' class='penalty-chart' value='" . $row['loan_entry_id'] . "' style='pointer-events: none; color: gray;'>Penalty Chart</a>
                    <a href='#' class='fine-chart' value='" . $row['loan_entry_id'] . "' style='pointer-events: none; color: gray;'>Fine Chart</a>
                </div>
            </div>";
        } else {
            $response['charts'] = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'>
                    <i class='fa'>&#xf107;</i>
                </button>
                <div class='dropdown-content'>
                    <a href='#' class='due-chart' value='" . $row['loan_entry_id'] . "'>Due Chart</a>
                    <a href='#' class='penalty-chart' value='" . $row['loan_entry_id'] . "'>Penalty Chart</a>
                    <a href='#' class='fine-chart' value='" . $row['loan_entry_id'] . "'>Fine Chart</a>
                </div>
            </div>";
        }

        $loan_list_arr[] = $response;
    }
}

echo json_encode($loan_list_arr);

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
            $status = 'NOC Removed';
        }
        return $status;
    }
    return ''; // Default return value if no conditions match
}

$pdo = null; // Close Connection
