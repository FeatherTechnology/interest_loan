<?php
require '../../ajaxconfig.php';

$cus_id = null; //  define a default value to avoid "undefined variable" warning

if (isset($_POST['cus_id'])) {
    $cus_id =  $_POST['cus_id'];
    $le_arr = array();
    $qry = $pdo->query("SELECT li.loan_entry_id as le_id FROM loan_issue li JOIN customer_status cs ON li.loan_entry_id = cs.loan_entry_id  where li.cus_id = '$cus_id' and cs.status = 7  ORDER BY li.loan_entry_id DESC ");

    while ($row = $qry->fetch()) {
        $le_arr[] = $row['le_id'];
    }
} else {
    $le_id = $_POST['le_id'];
    $le_arr[] = $le_id;
}

$loan_arr = array();
$coll_arr = array();
$response = array(); //Final array to return

$le_id = 0;
$i = 0;

foreach ($le_arr as $le_id) {

    $response['le_id'][$i] = $le_id;
    $result = $pdo->query("SELECT * FROM `loan_entry` WHERE id = $le_id ");

    if ($result->rowCount() > 0) {
        $loan_arr = $result->fetch();

        $response['loan_amount'] = intVal($loan_arr['loan_amnt_calc']);
        $response['interest_amount'] = intVal($loan_arr['interest_amnt_calc']);
        $response['interest_calculate'] = $loan_arr['interest_calculate'];
    }

    $coll_arr = array();
    $result = $pdo->query("SELECT * FROM `collection` WHERE loan_entry_id ='" . $le_id . "' ");
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {
            $coll_arr[] = $row;
        }
        $paid_amount = 0;
        $total_paid_princ = 0;
        $total_paid_int = 0;
        $principal_waiver = 0;
        foreach ($coll_arr as $tot) {
            $total_paid_princ += intVal($tot['principal_amount_track']);
            $total_paid_int += intVal($tot['interest_amount_track']);
            $principal_waiver += intVal($tot['principal_waiver']); //get pre closure value to subract to get balance amount
        }
        //total paid amount will be all records again cp id should be summed
        $response['paid_amount'] =  $total_paid_princ;
        $response['total_paid_int'] = $total_paid_int;
        $response['principal_waiver'] = $principal_waiver;

        //total amount subracted by total paid amount and subracted with pre closure amount will be balance_amount to be paid
        $response['balance_amount'] = $response['loan_amount'] - $response['paid_amount'] - $principal_waiver;

        $response['interest_amount'] = calculateNewInterestAmt($loan_arr['interest_rate_calc'], $response['balance_amount'], $response['interest_calculate']); // Interest Amount 

        $response = calculateOthers($loan_arr, $response, $pdo, $le_id);
    } else {
        //If collection table dont have rows means there is no payment against that request, so total paid will be 0
        $response['paid_amount'] = 0;
        $response['total_paid_int'] = 0;
        $response['principal_waiver'] = 0;

        //If in collection table, there is no payment means balance_amount amount still remains total amount
        $response['balance_amount'] = $response['loan_amount'];

        $response['interest_amount'] = calculateNewInterestAmt($loan_arr['interest_rate_calc'], $response['balance_amount'], $response['interest_calculate']);

        $response = calculateOthers($loan_arr, $response, $pdo, $le_id);
    }

    //To get the collection charges
    $result = $pdo->query("SELECT SUM(fine_charge) as fine_charge FROM `fine_charges` WHERE loan_entry_id = '" . $le_id . "' ");
    $row = $result->fetch();
    if ($row['fine_charge'] != null) {

        $fine_charges = $row['fine_charge'];

        $result = $pdo->query("SELECT SUM(fine_charge_track) as fine_charge_track,SUM(fine_charge_waiver) as fine_charge_waiver FROM `collection` WHERE loan_entry_id = '" . $le_id . "' ");
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $fine_charge_track = $row['fine_charge_track'];
            $fine_charge_waiver = $row['fine_charge_waiver'];
        } else {
            $fine_charge_track = 0;
            $fine_charge_waiver = 0;
        }

        $response['fine_charge'] = $fine_charges - $fine_charge_track - $fine_charge_waiver;
    } else {
        $response['fine_charge'] = 0;
    }

    $response['payable_as_req'][$i] = $response['payable_amount'];
    $response['pending_as_req'][$i] = $response['pending_amount'];
    $response['till_Date_Int'][$i] = $response['till_date_int'];
    $response['Principal_Waiver'][$i] = $response['principal_waiver'];
    $response['Interest_Amount'][$i] = $response['interest_amount'];
    $response['Penalty'][$i] = $response['penalty'];
    $response['Fine_Charge'][$i] = $response['fine_charge'];
    $response['Paid_Amount'][$i] = $response['paid_amount'];

    //Pending Check
    if ($response['pending_amount'] > 0 && $response['count_of_month'] != 0) {
        $response['pending_customer'][$i] = true;
    } else {
        $response['pending_customer'][$i] = false;
    }

    //OD check
    if ($response['od'] == true) {
        $response['od_customer'][$i] = true;
    } else {
        $response['od_customer'][$i] = false;
    }

    //Due nill Check
    if ($response['due_nil'] == true) {
        $response['due_nil_customer'][$i] = true;
    } else {
        $response['due_nil_customer'][$i] = false;
    }

    $response['balAmnt'][$i] =  $response['balance_amount'];
    $response['loanAmount'][$i] =  $response['loan_amount'];

    $i++;
}

$response['sub_status_customer'] = checkStatusOfCustomer($response, $loan_arr);

$response['sub_status_customer'] = $response['sub_status_customer'] ?? false;

function calculateOthers($loan_arr, $response, $pdo, $le_id)
{
    $interest_details = calculateInterestLoan($loan_arr, $response, $pdo, $le_id);
    $all_data = array_merge($response, $interest_details);
    $response = $all_data;

    if ($response['pending_amount'] < 0) {
        $response['pending_amount'] = 0;
    }

    return $response;
}

function calculateInterestLoan($loan_arr, $response, $pdo, $le_id)
{

    $due_start_from = $loan_arr['loan_date'];
    $maturity_month = $loan_arr['maturity_date_calc'];

    //Convert Date to Year and month, because with date, it will use exact date to loop months, instead of taking end of month
    $due_start_from = date('Y-m', strtotime($due_start_from));
    $maturity_month = date('Y-m', strtotime($maturity_month));

    // Create a DateTime object from the given date
    $maturity_month = new DateTime($maturity_month);
    // Subtract one month from the date
    $maturity_month->modify('-1 month');
    // Format the date as a string
    $maturity_month = $maturity_month->format('Y-m');

    //If Due method is Monthly, Calculate penalty by checking the month has ended or not
    $current_date = date('Y-m');

    $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
    $end_date_obj = DateTime::createFromFormat('Y-m', $maturity_month);
    $current_date_obj = DateTime::createFromFormat('Y-m', $current_date);

    $interval = new DateInterval('P1M'); // Create a one month interval

    //condition start
    $count = 0;

    while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) { // per month payable amount

        $start_date_obj->add($interval); //increase one month to loop again
        $count++; //Count represents how many months are exceeded
    }


    $res['count_of_month'] = $count;
    //To check over due, if current date is greater than maturity minth, then i will be OD
    if ($current_date_obj > $end_date_obj) {
        $res['od'] = true;
    } else {
        $res['od'] = false;
    }

    //To check whether due has been nil with other charges

    $qry = $pdo->query("SELECT  c.principal_waiver , c.principal_amount_track, pc.penalty, pc.paid_amnt AS paid_amntpc, pc.waiver_amnt AS waiver_amntpc, cc.fine_charge, cc.paid_amnt AS paid_amntcc, cc.waiver_amnt AS waiver_amntcc 
    FROM ( SELECT loan_entry_id, SUM(principal_waiver) AS principal_waiver,SUM(principal_amount_track) AS principal_amount_track 
    FROM collection 
    WHERE loan_entry_id = '$le_id' ) c 
    LEFT JOIN ( SELECT loan_entry_id, SUM(penalty) AS penalty, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt 
    FROM penalty_charges 
    WHERE loan_entry_id = '$le_id' 
    GROUP BY loan_entry_id ) pc ON c.loan_entry_id = pc.loan_entry_id 
    LEFT JOIN ( SELECT loan_entry_id, SUM(fine_charge) AS fine_charge, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt 
    FROM fine_charges 
    WHERE loan_entry_id = '$le_id' 
    GROUP BY loan_entry_id ) cc ON c.loan_entry_id = cc.loan_entry_id;");

    $row = $qry->fetch();

    $principal_waiver = intVal($row['principal_waiver']);

    $principal_waiver = $row['principal_amount_track'] + $row['principal_waiver'];

    //if sum value is null or empty then assign 0 to it
    if ($row['penalty'] == '' or $row['penalty'] == null) {
        $row['penalty'] = 0;
    }
    if ($row['paid_amntpc'] == '' or $row['paid_amntpc'] == null) {
        $row['paid_amntpc'] = 0;
    }
    if ($row['waiver_amntpc'] == '' or $row['waiver_amntpc'] == null) {
        $row['waiver_amntpc'] = 0;
    }
    if ($row['fine_charge'] == '' or $row['fine_charge'] == null) {
        $row['fine_charge'] = 0;
    }
    if ($row['paid_amntcc'] == '' or $row['paid_amntcc'] == null) {
        $row['paid_amntcc'] = 0;
    }
    if ($row['waiver_amntcc'] == '' or $row['waiver_amntcc'] == null) {
        $row['waiver_amntcc'] = 0;
    }

    $curr_penalty = $row['penalty'] - $row['paid_amntpc'] - $row['waiver_amntpc'];
    $curr_charges = $row['fine_charge'] - $row['paid_amntcc'] - $row['waiver_amntcc'];

    $qry = $pdo->query("SELECT SUM(loan_amnt_calc) as principal_amt_cal from loan_entry WHERE id =$le_id");
    $row = $qry->fetch();

    $total_for_nil = $row['principal_amt_cal'];

    $due_nil_check = intVal($total_for_nil) - intVal($principal_waiver);

    if ($due_nil_check == 0) {
        if ($curr_penalty > 0 || $curr_charges > 0) {
            $res['due_nil'] = true;
        } else {
            $res['due_nil'] = false;
        }
    } else {
        $res['due_nil'] = false;
    }


    if ($count > 0) {
        $interest_paid = getPaidInterest($pdo, $le_id);

        $res['payable_amount'] = payableCalculation($loan_arr, $response, $pdo, $le_id) - $interest_paid;
        $res['till_date_int'] = getTillDateInterest($loan_arr, $response, $pdo, 'curmonth', $le_id) - $interest_paid;
        $res['pending_amount'] = pendingCalculation($loan_arr, $response, $pdo, $le_id) - $interest_paid;

        if ($res['pending_amount'] < 0) {
            $res['pending_amount'] = 0;
        }
        if ($res['payable_amount'] < 0) {
            $res['payable_amount'] = 0;
        }

        $res['penalty'] = getPenaltyCharges($pdo, $le_id);
    } else {
        //in this calculate till date interest when month are not crossed for due starting month
        $res['till_date_int'] = getTillDateInterest($loan_arr, $response, $pdo, 'forstartmonth', $le_id);
        $res['pending_amount'] = 0;
        $res['payable_amount'] = 0;
        $res['penalty'] = 0;
    }

    $res['payable_amount'] = ceilAmount($res['payable_amount']);
    $res['pending_amount'] = ceilAmount($res['pending_amount']);
    $res['till_date_int'] = ceilAmount($res['till_date_int']);
    return $res;
}

function getPaidInterest($pdo, $le_id)
{
    $qry = $pdo->query("SELECT COALESCE(SUM(interest_amount_track), 0) + COALESCE(SUM(interest_waiver), 0) AS int_paid FROM `collection` WHERE loan_entry_id = '$le_id' and (interest_amount_track != '' and interest_amount_track IS NOT NULL OR interest_waiver != '' and interest_waiver IS NOT NULL) ");
    $int_paid = $qry->fetch()['int_paid'];
    return intVal($int_paid);
}

function payableCalculation($loan_arr, $response, $pdo, $le_id)
{
    $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
    $cur_date = new DateTime(date('Y-m-d'));
    $result = 0;

    if ($response['interest_calculate'] == "Month") {
        $last_month = clone $cur_date;
        $last_month->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_month->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date; // Due to mutation in function

            $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, 'payable', $le_id);

            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
    } elseif ($response['interest_calculate'] == "Days") {
        $last_date = clone $cur_date;
        $last_date->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_date->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date;

            $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, 'payable', $le_id);
            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
    }

    return $result;
}

function getTillDateInterest($loan_arr, $response, $pdo, $data, $le_id)
{

    if ($data == 'forstartmonth') {

        //get the loan isued month's date count
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

        //current month's total date
        $cur_date = new DateTime(date('Y-m-d'));

        $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $response['interest_amount'], $loan_arr, '', $le_id);

        //to increase till date Interest to nearest multiple of 5
        $cur_amt = ceil($result / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
        if ($cur_amt < $result) {
            $cur_amt += 5;
        }
        $result = $cur_amt;

        return $result;
    }
    if ($data == 'curmonth') {
        $cur_date = new DateTime(date('Y-m-d'));
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));


        $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $response['interest_amount'], $loan_arr, '', $le_id);
        return $result;
    }
    if ($data == 'pendingmonth') {
        //for pending value check, goto 2 months before
        //bcoz last month value is on payable, till date int will be on cur date
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
        $cur_date = new DateTime(date('Y-m-d'));
        $cur_date->modify('-2 months');
        $cur_date->modify('last day of this month');
        $result = 0;

        if ($issued_date->format('m') <= $cur_date->format('m')) {
            $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $response['interest_amount'], $loan_arr, 'pending', $le_id);
        }
        return $result;
    }

    return $response;
}

function pendingCalculation($loan_arr, $response, $pdo, $le_id)
{
    $pending_amount = getTillDateInterest($loan_arr, $response, $pdo, 'pendingmonth', $le_id);
    return $pending_amount;
}

function getPenaltyCharges($pdo, $le_id)
{
    // to get overall penalty paid till now to show pending penalty amount
    $result = $pdo->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE loan_entry_id = '" . $le_id . "' ");
    $row = $result->fetch();
    if ($row['penalty'] == null) {
        $row['penalty'] = 0;
    }
    if ($row['penalty_waiver'] == null) {
        $row['penalty_waiver'] = 0;
    }
    //to get overall penalty raised till now for this req id
    $result1 = $pdo->query("SELECT SUM(penalty) as penalty FROM `penalty_charges` WHERE loan_entry_id = '" . $le_id . "' ");
    $row1 = $result1->fetch();
    if ($row1['penalty'] == null) {
        $penalty = 0;
    } else {
        $penalty = $row1['penalty'];
    }

    return $penalty - $row['penalty'] - $row['penalty_waiver'];
}

function ceilAmount($amt)
{
    $cur_amt = ceil($amt / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
    if ($cur_amt < $amt) {
        $cur_amt += 5;
    }
    return $cur_amt;
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

function dueAmtCalculation($pdo, $start_date, $end_date, $interest_amount, $loan_arr, $status, $le_id)
{
    $start = new DateTime($start_date->format('Y-m-d'));
    $end = new DateTime($end_date->format('Y-m-d'));

    $interest_calculate = $loan_arr['interest_calculate'];
    $interest_rate_calc = $loan_arr['interest_rate_calc'];

    $result = 0;
    $monthly_interest_data = [];

    $loanRow = $pdo->query("SELECT loan_amnt_calc FROM loan_entry WHERE id = '$le_id'")->fetch(PDO::FETCH_ASSOC);
    $default_balance = $loanRow['loan_amnt_calc'];

    $collections = $pdo->query("SELECT principal_amount_track, collection_date , principal_waiver FROM collection 
        WHERE loan_entry_id = '$le_id'  AND (principal_amount_track != '' OR principal_waiver != '') ORDER BY collection_date ASC")->fetchAll();

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

            $interest_today = calculateNewInterestAmt($interest_rate_calc, $current_balance, $interest_calculate);

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

        if ($interest_calculate === 'Month') {
            while ($start->format('Y-m') <= $end->format('Y-m')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $interest_amount / intval($start->format('t'));

                if ($status != 'pending') {
                    if ($start->format('m') != $end->format('m')) {
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
                $start->modify('+1 month');
                $start->modify('first day of this month');
            }
        } elseif ($interest_calculate === 'Days') {
            while ($start->format('Y-m-d') <= $end->format('Y-m-d')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $interest_amount;
                $result += $dueperday;
                $monthly_interest_data[$month_key] = ($monthly_interest_data[$month_key] ?? 0) + $dueperday;

                $start->modify('+1 day');
            }
        }
    }

    // <------------------------------------------------------------------- Penalty Logic ----------------------------------------------------------------->

    if ($status === 'pending') {
        $penaltyRow = $pdo->query("SELECT overdue_type, overdue_penalty 
        FROM loan_category_creation 
        WHERE id = '" . $loan_arr['loan_category'] . "' ")->fetch(PDO::FETCH_ASSOC);

        $penalty_val  = $penaltyRow['overdue_penalty'] ?? 0;
        $overdue_type = strtolower(trim($penaltyRow['overdue_type'] ?? 'percentage'));

        $monthly_unpaid = [];
        $monthly_first_date = [];

        $current_month = date('Y-m'); // current month key

        foreach ($monthly_interest_data as $penalty_date => $cur_result) {
            $month_key = date('Y-m', strtotime($penalty_date));
            // skip current month
            if ($month_key === $current_month) {
                continue;
            }

            $paid_interest   = getPaidInterest($pdo, $le_id);
            $unpaid_interest = max(0, $cur_result - $paid_interest);

            if ($unpaid_interest > 0) {
                if (!isset($monthly_unpaid[$month_key])) {
                    $monthly_unpaid[$month_key] = 0;
                    $monthly_first_date[$month_key] = $penalty_date;
                }
                $monthly_unpaid[$month_key] += $unpaid_interest;
            }
        }

        // Step 2: Apply penalty only for past months
        foreach ($monthly_unpaid as $month => $unpaid) {
            if ($unpaid > 0 && $penalty_val > 0) {
                $penalty = ($overdue_type === 'rupee') ? round($penalty_val) : round(($unpaid * $penalty_val) / 100);

                $first_date = $monthly_first_date[$month];

                $checkPenalty = $pdo->query("SELECT 1 FROM penalty_charges 
                WHERE penalty_date = '$first_date' 
                AND loan_entry_id = '" . $le_id . "'");

                if ($checkPenalty->rowCount() == 0) {
                    $pdo->query("INSERT INTO penalty_charges 
                    (loan_entry_id, penalty_date, penalty, created_date) 
                    VALUES ('$le_id', '$first_date', $penalty, NOW())");
                }
            }
        }
    }
    return $result;
}

function checkStatusOfCustomer($response, $loan_arr)
{

    if ($response) {
        for ($i = 0; $i < count($response['pending_customer']); $i++) {
            if (date('Y-m-d', strtotime($loan_arr['due_startdate_calc'])) > date('Y-m-d')) {
                $response['sub_status_customer'][$i] = 'Current';
            } else {
                if ($response['pending_customer'][$i]) {
                    $response['sub_status_customer'][$i] = 'Pending';
                } else if ($response['od_customer'][$i]) {
                    $response['sub_status_customer'][$i] = 'OD';
                } else if ($response['due_nil_customer'][$i]) {
                    $response['sub_status_customer'][$i] = 'Due Nil';
                } else {
                    $response['sub_status_customer'][$i] = 'Current';
                }
            }
        }

        return $response['sub_status_customer'];
    }
}

$pdo = null; //Close Connection.
echo json_encode($response);
