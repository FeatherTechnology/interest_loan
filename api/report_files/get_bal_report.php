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

$query = "SELECT li.id, le.id as loan_entry_id, lnc.linename, le.loan_id, li.issue_date, le.maturity_date_calc, cc.cus_id, cc.aadhar_number, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname, bc.branch_name, cc.mobile1, lc.loan_category, ac.agent_name, le.loan_amount, le.due_period_calc, c.principal_amount_track, c.interest_amount_track , cs.status , le.interest_calculate , le.interest_rate_calc , le.due_startdate_calc , le.interest_amnt_calc
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
WHERE cs.status >= 7 AND DATE(li.issue_date) <= DATE('$to_date')
AND (
    IFNULL(c.principal_amount_track, 0) < le.loan_amount
    OR (
        IFNULL(c.principal_amount_track, 0) = le.loan_amount
        AND (
            DATE((
                SELECT MAX(col.collection_date)
                FROM collection col
                WHERE col.loan_entry_id = li.loan_entry_id
            )) > DATE('$to_date')
        )
    )
)";

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

// echo $query;

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

    $le_id = $row['loan_entry_id'];

    // Prepare loan_arr and response for calculation
    $loan_arr = [
        'loan_date' => $row['issue_date'],
        'interest_calculate' => $row['interest_calculate'],
        'interest_rate_calc' => $row['interest_rate_calc']
    ];

    $response = [
        'interest_calculate' => $row['interest_calculate'],
        'interest_amount' => floatval($row['interest_amnt_calc'])
    ];

    // Pass the report date to override today in payableCalculation
    $payable_interest = payableCalculation($loan_arr, $response, $pdo, $le_id, $to_date);

    // Interest already paid
    $interest_paid = getPaidInterest($pdo, $le_id , $to_date);

    // Pending interest
    $pending_interest = ceilAmount($payable_interest) - $interest_paid;
    if ($pending_interest < 0) $pending_interest = 0;

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

function payableCalculation($loan_arr, $response, $pdo, $le_id, $to_date = null)
{
    $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

    // Use given to_date or fallback to today
    $cur_date = new DateTime($to_date ?? date('Y-m-d'));

    $result = 0;

    if ($response['interest_calculate'] == "Month") {
        // Calculate till the last completed month before $to_date
        $last_month = clone $cur_date;
        $last_month->modify('first day of this month');
        $last_month->modify('-1 day'); // Last day of previous month

        $st_date = clone $issued_date;

        while ($st_date <= $last_month) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');

            // Prevent overshooting
            if ($end_date > $last_month) {
                $end_date = clone $last_month;
            }

            $start = clone $st_date;
            $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, $le_id);

            $st_date->modify('first day of next month');
        }
    } elseif ($response['interest_calculate'] == "Days") {
        // Calculate till the last day of previous month
        $last_date = clone $cur_date;
        $last_date->modify('first day of this month');
        $last_date->modify('-1 day');

        $st_date = clone $issued_date;

        while ($st_date <= $last_date) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');

            // Prevent overshooting
            if ($end_date > $last_date) {
                $end_date = clone $last_date;
            }

            $start = clone $st_date;
            $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, $le_id);

            $st_date->modify('first day of next month');
        }
    }

    return $result;
}

function dueAmtCalculation($pdo, $start_date, $end_date, $interest_amount, $loan_arr, $le_id)
{
    $start = new DateTime($start_date->format('Y-m-d'));
    $end = new DateTime($end_date->format('Y-m-d'));

    $interest_calculate = $loan_arr['interest_calculate'];
    $interest_rate_calc = $loan_arr['interest_rate_calc'];

    $result = 0;
    $monthly_interest_data = [];

    // Get default principal
    $loanRow = $pdo->query("SELECT loan_amnt_calc FROM loan_entry WHERE id = '$le_id'")->fetch(PDO::FETCH_ASSOC);
    $default_balance = (float)$loanRow['loan_amnt_calc'];

    // Fetch collection entries
    $collections = $pdo->query("SELECT principal_amount_track, collection_date 
        FROM collection 
        WHERE loan_entry_id = '$le_id' AND principal_amount_track != '' 
        ORDER BY collection_date ASC")->fetchAll();

    // If collections exist, calculate dynamically
    if (!empty($collections)) {
        $collection_index = 0;
        $current_balance = $default_balance;

        while ($start <= $end) {
            $today_str = $start->format('Y-m-d');
            $month_key = $start->format('Y-m-01');
            $paid_principal_today = 0;

            // Apply principal payments made on this day
            while ($collection_index < count($collections)) {
                $collection = $collections[$collection_index];
                $collection_date = (new DateTime($collection['collection_date']))->format('Y-m-d');

                if ($collection_date == $today_str) {
                    $paid_principal_today += (float)$collection['principal_amount_track'];
                    $collection_index++;
                } else {
                    break;
                }
            }

            $current_balance -= $paid_principal_today;

            // Recalculate interest for the day
            $interest_today = calculateNewInterestAmt($interest_rate_calc, $current_balance, $interest_calculate);

            if ($interest_calculate === 'Days') {
                $result += $interest_today;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $interest_today;
            } else {
                // For "Month" mode, distribute monthly interest daily
                $days_in_month = (int)$start->format('t');
                $daily_interest = $interest_today / $days_in_month;
                $result += $daily_interest;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $daily_interest;
            }

            $start->modify('+1 day');
        }
    } else {
        // No collections: Use flat logic
        if ($interest_calculate === 'Month') {
            while ($start <= $end) {
                $month_key = $start->format('Y-m-d');
                $days_in_month = (int)$start->format('t');
                $due_per_day = $interest_amount / $days_in_month;

                $period_end = clone $start;
                $period_end->modify('last day of this month');
                if ($period_end > $end) {
                    $period_end = clone $end;
                }

                $days = ($start->diff($period_end)->days) + 1;
                $cur_result = $due_per_day * $days;

                $result += $cur_result;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $cur_result;

                $start->modify('first day of next month');
            }
        } elseif ($interest_calculate === 'Days') {
            while ($start <= $end) {
                $month_key = $start->format('Y-m-d');
                $result += $interest_amount;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $interest_amount;
                $start->modify('+1 day');
            }
        }
    }

    return $result;
}

function getPaidInterest($pdo, $le_id , $to_date)
{
    $stmt = $pdo->prepare("SELECT SUM(interest_amount_track) as int_paid 
        FROM collection 
        WHERE loan_entry_id = :le_id 
        AND interest_amount_track IS NOT NULL AND interest_amount_track > 0 AND  
        DATE(collection_date) <= DATE('$to_date')");

    $stmt->execute([':le_id' => $le_id]);

    $int_paid = $stmt->fetch(PDO::FETCH_ASSOC)['int_paid'] ?? 0;

    return floatval($int_paid);
}

function calculateNewInterestAmt($interest_rate_calc, $balance_amount, $interest_calculate)
{
    //to calculate current interest amount based on current balance_amount value//bcoz interest will be calculated based on current balance_amount amt only for interest loan
    if ($interest_calculate == 'Month') {
        $int = $balance_amount * ($interest_rate_calc / 100);
    } else if ($interest_calculate == 'Days') {
        $int = ($balance_amount * ($interest_rate_calc / 100) / 30);
    }

    $curInterest = ceil($int / 5) * 5; //to increase Interest to nearest multiple of 5
    if ($curInterest < $int) {
        $curInterest += 5;
    }
    $response = $curInterest;

    return $response;
}

function ceilAmount($amt)
{
    $cur_amt = ceil($amt / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
    if ($cur_amt < $amt) {
        $cur_amt += 5;
    }
    return $cur_amt;
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