<?php
include '../../ajaxconfig.php';

if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
} else {
    $to_date = date('Y-m-d');
}

$column = array(
    'le.id',
    'lnc.linename',
    'le.loan_id',
    'le.loan_date',
    'le.due_startdate_calc',
    'le.maturity_date_calc',
    'le.aadhar_number',
    'le.cus_id',
    'cc.first_name',
    'cc.mobile1',
    'anc.areaname',
    'lc.loan_category',
    'ac.agent_name',
    'fi.fam_name',
    'fi.fam_relationship',
    'fi.fam_mobile',
    'le.loan_amount',
    'le.interest_amnt_calc',
    'le.due_period_calc',
    'le.id',
    'le.id',
    'le.id',
    'le.id',
    'le.id',
    'le.id',
    'le.id',
    'le.id',
    'le.id'
);

$nointerestIds = [];
$nointerestQuery = $pdo->query("SELECT id FROM loan_entry WHERE interest_rate_calc = 0 ");
while ($row = $nointerestQuery->fetch(PDO::FETCH_ASSOC)) $nointerestIds[] = $row['id'];
$nointerestStr = !empty($nointerestIds) ? implode(',', $nointerestIds) : 0;

$qry = " SELECT le.id FROM loan_entry le
LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id
LEFT JOIN collection c ON c.loan_entry_id = le.id
LEFT JOIN (
    SELECT loan_entry_id, MAX(collection_date) AS last_collection_date
    FROM collection
    GROUP BY loan_entry_id
) sub ON c.loan_entry_id = sub.loan_entry_id
WHERE le.due_startdate_calc <= '$to_date' AND (cs.status = 7
AND (cs.status != 'Due Nil' OR (cs.status = 'Due Nil' AND sub.last_collection_date > '$to_date'))) OR (cs.status > 7 AND cs.in_closed_date >= '$to_date')";

$run = $pdo->query($qry);

$loan_entry_id_list = [];

while ($row = $run->fetch()) {
    $loan_entry_id_list[] = $row['id'];
}

if (!empty($loan_entry_id_list)) {
    // Convert array to comma separated list
    $loan_entry_id_list = implode(',', $loan_entry_id_list);
} else {
    // No values → fallback to 0
    $loan_entry_id_list = 0;
}

$query = "SELECT lnc.linename, 
le.loan_id, 
le.loan_date, 
le.due_startdate_calc, 
le.maturity_date_calc, 
le.aadhar_number, 
le.cus_id, 
cc.first_name, 
cc.mobile1, 
anc.areaname,
lc.loan_category, 
ac.agent_name, 
fi.fam_name, 
fi.fam_relationship, 
fi.fam_mobile, 
le.loan_amount, 
le.interest_amnt_calc,
le.due_period_calc, 
cs.status,
c.principal_amount_track,
c.interest_amount_track, 
le.interest_calculate, 
le.interest_rate_calc,
le.id as loan_entry_id
FROM
    loan_entry le
    LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id
    LEFT JOIN customer_creation cc ON le.cus_id = cc.cus_id
    LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
    LEFT JOIN area_name_creation anc ON cc.area = anc.id
    LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.id
    LEFT JOIN loan_category lc ON lcc.loan_category = lc.id
    LEFT JOIN agent_creation ac ON le.agent_id_calc = ac.id
    LEFT JOIN guarantor_info gi ON le.id = gi.loan_entry_id
    LEFT JOIN family_info fi ON gi.family_info_id = fi.id
    LEFT JOIN loan_issue li ON le.id = li.loan_entry_id AND li.balance_amount = 0
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
    ) c ON le.id = c.loan_entry_id

WHERE
    le.id IN ($loan_entry_id_list) AND le.id NOT IN ($nointerestStr) ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (lnc.linename LIKE '%" . $_POST['search'] . "%'
                    OR le.loan_id LIKE '%" . $_POST['search'] . "%'
                    OR le.loan_date LIKE '%" . $_POST['search'] . "%'
                    OR le.aadhar_number LIKE '%" . $_POST['search'] . "%'
                    OR le.cus_id LIKE '%" . $_POST['search'] . "%'
                    OR cc.first_name LIKE '%" . $_POST['search'] . "%'
                    OR cc.mobile1 LIKE '%" . $_POST['search'] . "%'
                    OR anc.areaname LIKE '%" . $_POST['search'] . "%'
                    OR lc.loan_category LIKE '%" . $_POST['search'] . "%'
                    OR ac.agent_name LIKE '%" . $_POST['search'] . "%'
                    OR fi.fam_name LIKE '%" . $_POST['search'] . "%'
                    OR fi.fam_relationship LIKE '%" . $_POST['search'] . "%'
                    OR fi.fam_mobile LIKE '%" . $_POST['search'] . "%'
                    OR le.loan_amount LIKE '%" . $_POST['search'] . "%') ";
    }
}

if (isset($_POST['order'])) {
    $col = $column[$_POST['order'][0]['column']];
    $dir = $_POST['order'][0]['dir'];
    $query .= " ORDER BY CAST($col AS UNSIGNED) $dir ";
} else {
    $query .= " ";
}

$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $pdo->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $pdo->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    if (strtotime($row['maturity_date_calc']) < strtotime($to_date)) {

        $end   = strtotime($row['maturity_date_calc']);
        $start = strtotime($row['due_startdate_calc']);
        $search_date = strtotime($to_date);

        $start_date = new DateTime($row['due_startdate_calc']);
        $to_dt      = new DateTime($to_date);
        $maturity_dt = new DateTime($row['maturity_date_calc']);

        $months = 0;
        $diff_days = 0;
        $diff_weeks = 0;
        $od_months = 0;

        // OD days from maturity → to_date
        $od_diff_days = $maturity_dt->diff($to_dt)->days;

        // ------------------------------------ MONTHLY LOGIC ------------------------------------ //

        if (($row['interest_calculate'] == 'Month')) {

            // PENDING
            $months = (date('Y', $end) - date('Y', $start)) * 12
                + (date('m', $end) - date('m', $start))
                + 1;

            if (
                date('m', $search_date) == date('m', $end) &&
                date('Y', $search_date) == date('Y', $end)
            ) {
                $months -= 1;
            }

            $pending_month = $months;

            // ---------------- MONTHLY OD (DECIMAL MONTHS) ---------------- //
            $days_in_maturity_month = (int)$maturity_dt->format('t');
            $od_months = round($od_diff_days / $days_in_maturity_month, 2);
        }

        // ------------------------------------ DAILY LOGIC ------------------------------------ //

        else {

            // PENDING 
            $months = $start_date->diff($maturity_dt)->days + 1;
            $pending_month = $months;

            // DAILY OD (days)
            $od_months = $od_diff_days;
        }
    } else {

        $end   = strtotime($to_date);
        $start = strtotime($row['due_startdate_calc']);

        $start_date = new DateTime($row['due_startdate_calc']);
        $end_date   = new DateTime($to_date);

        $months = 0;
        $diff_days = 0;
        $diff_weeks = 0;
        $od_months = 0;

        // ------------------------------------ MONTHLY LOGIC ------------------------------------ //

        if (($row['interest_calculate'] == 'Month')) {

            $months = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)) + 1;
            $pending_month = $months - 1;
        }
        // ------------------------------------ DAILY LOGIC ------------------------------------ //

        else {
            $months = $start_date->diff($end_date)->days;
            $months += 1;
            $pending_month = $months - 1;
        }
    }

    // Balance Amount 
    if ($row['principal_amount_track'] != '') {
        $balance_amt = intval($row['loan_amount']) - intval($row['principal_amount_track']);
    } else {
        $balance_amt = intval($row['loan_amount']);
    }

    $loan_entry_id = $row['loan_entry_id'];

    // Prepare loan_arr and response for calculation
    $loan_arr = [
        'loan_date' => $row['loan_date'],
        'interest_calculate' => $row['interest_calculate'],
        'interest_rate_calc' => $row['interest_rate_calc']
    ];

    $response = [
        'interest_calculate' => $row['interest_calculate'],
        'interest_amount' => floatval($row['interest_amnt_calc'])
    ];

    // Interest already paid
    $interest_paid = getPaidInterest($pdo, $loan_entry_id, $to_date);
    $pending_amount = pendingCalculation($pdo, $loan_arr, $response, $loan_entry_id, $to_date) - $interest_paid;
    $payable_amount = payableCalculation($pdo, $loan_arr, $response, $loan_entry_id, $to_date) - $interest_paid;
    $balance_interest = calculateNewInterestAmt($loan_arr['interest_rate_calc'], $balance_amt, $response['interest_calculate']);

    if ($pending_amount < 0) {
        $pending_amount = 0;
    }

    if ($payable_amount < 0) {
        $payable_amount = 0;
    }

    $pending_amount = ceilAmount($pending_amount);
    $payable_amount = ceilAmount($payable_amount);
    $balance_interest = ceilAmount($balance_interest);

    $paid_due = $row['interest_amount_track'] / $row['interest_amnt_calc'];
    $balance_due = (float)$row['due_period_calc'] - $paid_due;
    $pending_due = max(0, $pending_amount / $row['interest_amnt_calc']);

    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['linename'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = date('d-m-Y', strtotime($row['due_startdate_calc']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_date_calc']));
    $sub_array[] = $row['aadhar_number'];
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['first_name'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $row['areaname'];
    $sub_array[] = $row['loan_category'];
    $sub_array[] = $row['agent_name'];
    $sub_array[] = $row['fam_name'];
    $sub_array[] = $row['fam_relationship'];
    $sub_array[] = $row['fam_mobile'];
    $sub_array[] = moneyFormatIndia($row['loan_amount']);
    $sub_array[] = moneyFormatIndia($row['interest_amnt_calc']);
    $sub_array[] = $row['due_period_calc'];
    $sub_array[] = !empty($balance_amt) ? moneyFormatIndia($balance_amt) : 0;
    $sub_array[] = !empty($balance_interest) ? moneyFormatIndia($balance_interest) : 0;
    $sub_array[] = !empty($balance_due) && $balance_due >= 0 ? number_format($balance_due, 1, '.', '') : 0;
    $sub_array[] = !empty($pending_amount) ? moneyFormatIndia($pending_amount) : 0;
    $sub_array[] = !empty($pending_due) && $pending_due >= 0 ? number_format($pending_due, 1, '.', '') : 0;
    $sub_array[] = !empty($od_months) ? ($od_months) : 0;
    $sub_array[] = !empty($payable_amount) ? moneyFormatIndia($payable_amount) : 0;
    $sub_array[] = 'Present';
    $payable_amount = max(0, $payable_amount);
    $pending_amount = max(0, $pending_amount);

    if ($payable_amount == 0  && $pending_amount == 0  && $balance_amt == 0) {
        $sub_array[] = 'Due Nil';
    } 
    else if ($pending_amount == 0 && ((($row['interest_calculate'] === 'Month') && date('Y-m', strtotime($row['maturity_date_calc'])) >= date('Y-m', strtotime($to_date))) || (($row['interest_calculate'] != 'Month') && strtotime($row['maturity_date_calc']) >= strtotime($to_date))) && $balance_amt != 0) {
        $sub_array[] = 'Current';
    } 
    else if ($pending_amount > 0 &&  (
        (($row['interest_calculate'] === 'Month') && date('Y-m', strtotime($row['maturity_date_calc'])) >= date('Y-m', strtotime($to_date))) || (($row['interest_calculate'] != 'Month') && strtotime($row['maturity_date_calc']) > strtotime($to_date))
    )) {
        $sub_array[] = 'Pending';
    } 
    else if ((($balance_amt  > 0) && ((($row['interest_calculate'] === 'Month') && date('Y-m', strtotime($row['maturity_date_calc'])) < date('Y-m', strtotime($to_date))) || (($row['interest_calculate'] != 'Month') && strtotime($row['maturity_date_calc']) < strtotime($to_date)))
    )) {
        $sub_array[] = 'OD';
    } else {
        $sub_array[] = 'No Result';
    }

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($pdo)
{
    $query = $pdo->query(" SELECT COUNT(*) AS count_result FROM loan_entry le LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id
    WHERE cs.status >= 7 ");
    $statement = $query->fetch();
    return intval($statement['count_result']);
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function moneyFormatIndia($num)
{
    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $explrestunits = "";
    if (strlen((string)$num) > 3) {
        $lastthree = substr((string)$num, -3);
        $restunits = substr((string)$num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $index => $value) {
            if ($index == 0) {
                $explrestunits .= (int)$value . ",";
            } else {
                $explrestunits .= $value . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    $thecash = $isNegative ? "-" . $thecash : $thecash;
    $thecash = $thecash == 0 ? "0" : $thecash;
    return $thecash;
}

function pendingCalculation($pdo, $loan_arr, $response, $loan_entry_id, $to_date)
{
    $pending = getTillDateInterest($loan_arr, $response, $pdo, 'pendingmonth', $loan_entry_id, $to_date);
    return $pending;
}

function getTillDateInterest($loan_arr, $response, $pdo, $data, $loan_entry_id, $to_date)
{

    if ($data == 'forstartmonth') {

        //get the loan isued month's date count
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

        // Use given to_date or fallback to today
        $cur_date = new DateTime($to_date ?? date('Y-m-d'));

        $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $response['interest_amount'], $loan_arr, '', $loan_entry_id);

        // Use your clean rounding logic
        $cur_amt = ceilAmount($result);

        $result = $cur_amt;

        return $result;
    }
    if ($data == 'curmonth') {

        // Use given to_date or fallback to today
        $cur_date = new DateTime($to_date ?? date('Y-m-d'));

        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

        $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $response['interest_amount'], $loan_arr, 'TDI', $loan_entry_id);
        return $result;
    }
    if ($data == 'pendingmonth') {
        //for pending value check, goto 2 months before
        //bcoz last month value is on payable, till date int will be on cur date
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

        // Use given to_date or fallback to today
        $cur_date = new DateTime($to_date ?? date('Y-m-d'));

        $cur_date->modify('-2 months');
        $cur_date->modify('last day of this month');
        $result = 0;

        if ($issued_date->format('Y-m') <= $cur_date->format('Y-m')) {
            $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $response['interest_amount'], $loan_arr, 'pending', $loan_entry_id);
        }
        return $result;
    }

    return $response;
}

function payableCalculation($pdo, $loan_arr, $response, $loan_entry_id, $to_date)
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

            $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, 'payable', $loan_entry_id);

            $st_date->modify('first day of next month');
        }
    } elseif ($response['interest_calculate'] == "Days") {
        $last_date = clone $cur_date;
        $last_date->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_date->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date;

            $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, 'payable', $loan_entry_id);
            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
    }

    return $result;
}

function dueAmtCalculation($pdo, $start_date, $end_date, $interest_amount, $loan_arr, $status, $loan_entry_id)
{
    $start = new DateTime($start_date->format('Y-m-d'));
    $end = new DateTime($end_date->format('Y-m-d'));

    if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    } else {
        $to_date = date('Y-m-d');
    }

    $interest_calculate = $loan_arr['interest_calculate'];
    $int_rate = $loan_arr['interest_rate_calc'];
    $result = 0;
    $monthly_interest_data = [];

    $loanRow = $pdo->query("SELECT loan_amount FROM loan_entry WHERE id = '" . $loan_entry_id . "'")->fetch(PDO::FETCH_ASSOC);
    $default_balance = $loanRow['loan_amount'];

    $collections = $pdo->query("SELECT principal_amount_track,principal_waiver, collection_date FROM collection 
        WHERE loan_entry_id = '" . $loan_entry_id . "' AND (principal_amount_track != '' OR principal_waiver != '') AND DATE(collection_date) <= DATE('$to_date') ORDER BY collection_date ASC")->fetchAll();

    if (!empty($collections)) {

        // <---------------------------------------------------------------- IF COLLECTIONS EXIST ------------------------------------------------------------>

        $collection_index = 0;
        $current_balance = $default_balance;

        while ($start <= $end) {
            $today_str = $start->format('Y-m-d');
            $month_key = $start->format('Y-m-01');
            $paid_principal_today = 0;
            $paid_principal_waiver = 0;

            while ($collection_index < count($collections)) {
                $collection = $collections[$collection_index];
                $collection_date = (new DateTime($collection['collection_date']))->format('Y-m-d');
                if ($collection_date == $today_str) {
                    $paid_principal_today += (float)$collection['principal_amount_track'];
                    $paid_principal_waiver += (float)$collection['principal_waiver'];
                    $collection_index++;
                } else {
                    break;
                }
            }

            $current_balance = max(0, $current_balance - ($paid_principal_today + $paid_principal_waiver));

            $interest_today = calculateNewInterestAmt($int_rate, $current_balance, $interest_calculate);

            if ($interest_calculate === 'Days') {
                $result += $interest_today;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $interest_today;
            } else {
                $days_in_month = (int)$start->format('t');
                $daily_interest = $interest_today / $days_in_month;
                $result += $daily_interest;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $daily_interest;
            }

            $start->modify('+1 day');
        }
    } else {
        $monthly_interest_data = [];

        if ($interest_calculate == 'Month') {
            while ($start->format('Y-m') <= $end->format('Y-m')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $interest_amount / intval($start->format('t'));

                if ($status != 'pending') {
                    if ($start->format('Y-m') != $end->format('Y-m')) {
                        $new_end_date = clone $start;
                        $new_end_date->modify('last day of this month');
                        $cur_result = (($start->diff($new_end_date))->days + 1) * $dueperday;
                    } else {
                        $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    }
                } else {
                    $new_end = clone $start;
                    $new_end->modify("last day of this month");
                    $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                }

                $result += $cur_result;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $cur_result;
                $start->setDate($start->format('Y'), $start->format('m'), 1);
                $start->modify('+1 month');
            }
        } else if ($interest_calculate == 'Days') {
            while ($start->format('Y-m-d') <= $end->format('Y-m-d')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $interest_amount;
                $result += $dueperday;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $dueperday;

                $start->modify('+1 day');
            }
        }
    }
    return $result;
}

function getPaidInterest($pdo, $loan_entry_id, $to_date)
{
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(interest_amount_track), 0) + COALESCE(SUM(interest_waiver), 0) AS int_paid
        FROM collection 
        WHERE loan_entry_id = :loan_entry_id 
        AND (interest_amount_track != '' and interest_amount_track IS NOT NULL OR interest_waiver != '' and interest_waiver IS NOT NULL) AND  
        DATE(collection_date) <= DATE('$to_date')");

    $stmt->execute([':loan_entry_id' => $loan_entry_id]);

    $int_paid = $stmt->fetch(PDO::FETCH_ASSOC)['int_paid'] ?? 0;

    return floatval($int_paid);
}

function calculateNewInterestAmt($interest_rate_calc, $balance_amt, $interest_calculate)
{
    //to calculate current interest amount based on current balance_amt value//bcoz interest will be calculated based on current balance_amt amt only for interest loan
    if ($interest_calculate == 'Month') {
        $int = $balance_amt * ($interest_rate_calc / 100);
    } else if ($interest_calculate == 'Days') {
        $int = ($balance_amt * ($interest_rate_calc / 100) / 30);
    }

    // Use your clean rounding logic
    $curInterest = ceilAmount($int);

    $response = $curInterest;

    return $response;
}

function ceilAmount($amt)
{
    // Round the amount to avoid floating point precision errors.
    $amt = round($amt, 2);  // Round to two decimal places (or adjust as needed)
    $cur_amt = ceil($amt / 5) * 5;
    // If cur_amt is exactly equal to amt (with small floating point tolerance), don't increment.
    if (abs($cur_amt - $amt) < 0.01) {
        return $cur_amt;
    }
    if ($cur_amt < $amt) {
        $cur_amt += 5;
    }
    return $cur_amt;
}

// Close the database pdoion
$pdo = null;
