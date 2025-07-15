<?php
include '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';

@session_start();
$user_id = $_SESSION['user_id'];

$to_date = $_POST['to_date'];

$status = [1 => 'customer profile', 2 => 'Loan Calculation', 3 => 'In Approval', 4 => 'In Loan Issue', 5 => 'Cancel', 6 => 'Revoke', 7 => 'Current', 8 => 'Cancel', 9 => 'Revoke', 10 => 'In Closed', 11 => 'Closed', 12 => 'In NOC', 13 => 'NOC Completed', 14 => 'NOC Removed'];

$column = [
    'li.id',
    'lnc.linename',
    'le.loan_id',
    'li.issue_date',
    'le.maturity_date_calc',
    'cc.cus_id',
    'cc.aadhar_number',
    'cc.first_name',
    'anc.areaname',
    'bc.branch_name',
    'cc.mobile1',
    'lc.loan_category',
    'ac.agent_name',
    'le.loan_amount',
    'li.id',
    'le.due_period_calc',
    'li.id',
    'c.principal_amount_track',
    'c.interest_amount_track',
    'cs.status',
    'li.id'
];

$query = "SELECT li.id, lnc.linename, le.loan_id, li.issue_date, le.maturity_date_calc, cc.cus_id, cc.aadhar_number, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname, bc.branch_name, cc.mobile1, lc.loan_category, ac.agent_name, le.loan_amount, le.due_period_calc, c.principal_amount_track, c.interest_amount_track , 
cs.status , le.interest_calculate , le.interest_rate_calc , le.due_startdate_calc
FROM loan_issue li  
JOIN loan_entry le ON li.loan_entry_id = le.id
JOIN customer_creation cc ON le.cus_id = cc.cus_id
JOIN line_name_creation lnc ON cc.line = lnc.id
JOIN area_name_creation anc ON cc.area = anc.id
JOIN branch_creation bc ON lnc.branch_id = bc.id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
LEFT JOIN agent_creation ac ON le.agent_id_calc = ac.id
    LEFT JOIN (
    SELECT 
        loan_entry_id, 
        SUM(principal_amount_track) AS principal_amount_track, 
        SUM(interest_amount_track) AS interest_amount_track 
    FROM 
        collection 
    WHERE 
        DATE(collection_date) <= DATE('$to_date')
    GROUP BY 
        loan_entry_id
) c ON li.loan_entry_id = c.loan_entry_id
JOIN customer_status cs ON li.loan_entry_id = cs.loan_entry_id
WHERE cs.status = 7 AND DATE(li.issue_date) <= DATE('$to_date')";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (
        lnc.linename LIKE '%$search%' OR
        le.loan_id LIKE '%$search%' OR
        li.issue_date LIKE '%$search%' OR
        le.maturity_date_calc LIKE '%$search%' OR
        cc.cus_id LIKE '%$search%' OR
        cc.aadhar_number LIKE '%$search%' OR
        cc.first_name LIKE '%$search%' OR
        anc.areaname LIKE '%$search%' OR
        bc.branch_name LIKE '%$search%' OR
        cc.mobile1 LIKE '%$search%' OR
        lc.loan_category LIKE '%$search%'
    )";
}

$query .= " GROUP BY li.loan_entry_id ";

$orderColumn = $_POST['order'][0]['column'] ?? null;
$orderDir = $_POST['order'][0]['dir'] ?? 'ASC';

if ($orderColumn !== null) {
    $query .= " ORDER BY " . $column[$orderColumn] . " " . $orderDir;
}

$statement = $pdo->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? -1;
if ($length != -1) {
    $query .= " LIMIT $start, $length";
}

$statement = $pdo->prepare($query);

$statement->execute();

$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

foreach ($result as $row) {
    $sub_array = [];

    // Balance Amount 
    if ($row['principal_amount_track'] != '') {
        $balance_amount = intval($row['loan_amount']) - intval($row['principal_amount_track']);
    } else {
        $balance_amount = intval($row['loan_amount']);
    }

    // Get interest calculation method and rate
    $interest_rate_calc = isset($row['interest_rate_calc']) ? floatval($row['interest_rate_calc']) : 0;
    $interest_calculate = isset($row['interest_calculate']) ? $row['interest_calculate'] : '';

    // Interest calculation
    if ($interest_calculate == 'Month') {
        $int = $balance_amount * ($interest_rate_calc / 100);
    } else if ($interest_calculate == 'Days') {
        $int = ($balance_amount * ($interest_rate_calc / 100) / 30);
    } else {
        $int = 0;
    }

    // Round up to next multiple of 5
    $curInterest = ceil($int / 5) * 5;
    if ($curInterest < $int) {
        $curInterest += 5;
    }

    $due_amount = $curInterest;

    if ($interest_calculate == 'Month') {

        $start = new DateTime($row['due_startdate_calc']);
        $end = new DateTime($to_date);
        $days_diff = $start->diff($end)->days; // This gives total number of days difference

        // Total interest = prorated for days, assuming each month = 30 days
        $total_interest = ($due_amount / 30) * $days_diff;
        $total = ceil($total_interest / 5) * 5;
        if ($total < $total_interest) {
            $total += 5;
        }

        // Interest already paid
        $interest_paid = isset($row['interest_amount_track']) ? intval($row['interest_amount_track']) : 0;

        // Pending interest
        $pending_interest = $total - $interest_paid;
    } else if ($interest_calculate == 'Days') {

        // Calculate difference in days between due_startdate_calc and to_date
        $start = new DateTime($row['due_startdate_calc']);
        $end = new DateTime($to_date);
        $days_diff = $start->diff($end)->days; // This gives total number of days difference

        // Total interest up to to_date (daily)
        $total_interest = $days_diff * $due_amount; // due_amount must be per-day interest
        $total = ceil($total_interest / 5) * 5;
        if ($total < $total_interest) {
            $total += 5;
        }

        // Interest already paid
        $interest_paid = isset($row['interest_amount_track']) ? intval($row['interest_amount_track']) : 0;

        // Pending interest = total - paid
        $pending_interest = $total - $interest_paid;
    }

    $sub_array[] = $sno;
    $sub_array[] = $row['linename'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['issue_date']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_date_calc']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['aadhar_number'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['areaname'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $row['loan_category'];
    $sub_array[] = $row['agent_name'];
    $sub_array[] = moneyFormatIndia($row['loan_amount']);
    $sub_array[] = moneyFormatIndia($due_amount);
    $sub_array[] = $row['due_period_calc'];
    $sub_array[] = moneyFormatIndia($balance_amount);
    $sub_array[] = moneyFormatIndia($balance_amount);
    $sub_array[] = moneyFormatIndia($pending_interest);
    $sub_array[] = 'Present';
    $sub_array[] = $status[$row['status']];
    $data[] = $sub_array;
    $sno++;
}

function count_all_data($pdo)
{
    $query = "SELECT id FROM customer_status csts WHERE csts.status >= 7 AND csts.status <=10 ";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = [
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data,
];

echo json_encode($output);
$pdo = null; // Close Connection