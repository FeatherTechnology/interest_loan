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
        $row = $result->fetch();
        $loan_arr = $row;

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

//for knowing the customer status for due followup screen
//this will give the customer's sub status in the order of Legal, Error, OD, Due Nill, Pending, Current

if ($cus_id != '') {
    $response['sub_status_customer'] = checkStatusOfCustomer($response, $loan_arr, $cus_id, $pdo);
}

$response['sub_status_customer'] = $response['sub_status_customer'] ?? false;

function calculateOthers($loan_arr, $response, $pdo, $le_id)
{
    if ($loan_arr['due_method'] == 'Monthly') {

        $interest_details = calculateInterestLoan($loan_arr, $response, $pdo, $le_id);
        $all_data = array_merge($response, $interest_details);
        $response = $all_data;
    }
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
    $qry = $pdo->query("SELECT SUM(interest_amount_track) as int_paid FROM `collection` WHERE loan_entry_id = '$le_id' and (interest_amount_track != '' and interest_amount_track IS NOT NULL) ");
    $int_paid = $qry->fetch()['int_paid'];
    return intVal($int_paid);
}

function payableCalculation($loan_arr, $response, $pdo, $le_id)
{
    $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
    $cur_date = new DateTime(date('Y-m-d'));
    $last_month = clone $cur_date;
    $last_month->modify('-1 month'); // last month same date 
    $result = 0;
    $st_date = clone $issued_date;
    while ($st_date->format('m') <= $last_month->format('m')) {
        $end_date = clone $st_date;
        $end_date->modify('last day of this month');
        $start = clone $st_date; //because the function calling below will change the root of starting date

        $result += dueAmtCalculation($pdo, $start, $end_date, $response['interest_amount'], $loan_arr, 'payable', $le_id);

        $st_date->modify('+1 month');
        $st_date->modify('first day of this month');
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
        // $result = (($issued_date->diff($cur_date))->days) * $issue_month_due;

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
    $start = $start_date->format('Y-m-d');
    $start = new DateTime($start);
    $end = $end_date->format('Y-m-d');
    $end = new DateTime($end);

    $interest_calculate = $loan_arr['interest_calculate'];
    $interest_rate_calc = $loan_arr['interest_rate_calc'];
    $loan_category = $loan_arr['loan_category'];

    $result = 0;
    $qry = $pdo->query("SELECT principal_amount_track FROM `collection` WHERE loan_entry_id = '" . $le_id . "' and principal_amount_track != '' ORDER BY collection_date ASC ");
    if ($qry->rowCount() > 0) {

        while ($start->format('m') <= $end->format('m')) {

            $penalty = 0;
            $start_for_penalty = $start->format('Y-m-d');

            $qry = $pdo->query("SELECT principal_amount_track as princ,balance_amount, collection_date FROM `collection` WHERE loan_entry_id = '" . $le_id . "' and principal_amount_track != '' and month(collection_date) = month('" . $start->format('Y-m-d') . "') and year(collection_date) = year('" . $start->format('Y-m-d') . "') ORDER BY collection_date ASC ");
            if ($qry->rowCount() > 0) {

                while ($row = $qry->fetch()) {
                    $princ = $row['princ'];
                    $balance_amount = $row['balance_amount'];
                    $collection_date = new DateTime($row['collection_date']);

                    $interest_amount = calculateNewInterestAmt($interest_rate_calc, $balance_amount, $interest_calculate);
                    $balance_amount = $balance_amount - $princ;
                    $dueperday = $interest_amount / intval($start->format('t'));
                    $cur_result = (($start->diff($collection_date))->days) * $dueperday;
                    $result += $cur_result;

                    unset($start); //unset to remove as obj // so can reinitialize
                    $start = new DateTime($collection_date->format('Y-m-d'));
                    unset($collection_date); //unset to remove as obj // so can reinitialize
                }
            } else {
                $qry = $pdo->query("SELECT principal_amount_track as princ,balance_amount, collection_date FROM `collection` WHERE loan_entry_id = '" . $le_id . "' and principal_amount_track != '' and month(collection_date) < month('" . $start->format('Y-m-d') . "') and year(collection_date) <= year('" . $start->format('Y-m-d') . "') ORDER BY collection_date ASC LIMIT 1");
                if ($qry->rowCount() > 0) {
                    $row = $qry->fetch();
                    $princ = $row['princ'];
                    $balance_amount = $row['balance_amount'];
                    $balance_amount = $balance_amount - $princ;
                } else {
                    $qry = $pdo->query("SELECT loan_amnt_calc from loan_entry where id = '" . $le_id . "' ");
                    $row = $qry->fetch();
                    $balance_amount = $row['loan_amnt_calc'];
                }
            }

            $interest_amount = calculateNewInterestAmt($interest_rate_calc, $balance_amount, $interest_calculate);
            $dueperday = $interest_amount / intval($start->format('t'));

            if ($start->format('m') != $end->format('m')) {
                $new_end = new DateTime($start->format("Y-m-t"));
                $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                $result += $cur_result;
                $start->modify("+1 month");
                $start->modify("first day of this month");
            } else {

                if ($status == 'payable' or $status == 'pending') {
                    $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    $result += $cur_result;
                } else {
                    $cur_result = (($start->diff($end))->days) * $dueperday;
                    $result += $cur_result;
                }
                $start->modify("+1 month");
                $start->modify("first day of this month");
            }

            if ($status == 'pending') {
                // Fetch penalty percentage or rupee amount from loan_category_creation
                $ovqry = $pdo->query("SELECT overdue_penalty AS overdue, overdue_type FROM `loan_category_creation` WHERE loan_category = '$loan_category'");
                $row = $ovqry->fetch();

                $penalty_val = $row['overdue'] ?? 0;
                $penalty_type = strtolower(trim($row['overdue_type'] ?? 'percentage')); // Default to 'percentage' if not set

                // Calculate paid interest for the month
                $paid_interest = getPaidInterest($pdo, $le_id);

                if ($paid_interest > 0) {
                    $cur_result =  $cur_result - $paid_interest;
                    if ($cur_result < 0) {
                        $cur_result = 0;
                    }
                }

                // Check if penalty already exists for the month
                $penalty_date = date('Y-m', strtotime($start_for_penalty));
                $checkPenalty = $pdo->query("SELECT 1 FROM penalty_charges WHERE penalty_date = '$penalty_date' AND loan_entry_id = '$le_id'");

                if ($checkPenalty->rowCount() == 0 && $cur_result > 0 && $penalty_val > 0) {
                    // Calculate penalty based on type
                    if ($penalty_type === 'rupee') {
                        $penalty = round($penalty_val);
                    } else { // Assume percentage by default
                        $penalty = round(($cur_result * $penalty_val) / 100);
                        echo $penalty;
                    }

                    // Insert new penalty record
                    $insertQry = $pdo->prepare("INSERT INTO penalty_charges (`loan_entry_id`, `penalty_date`, `penalty`, `created_date`) VALUES (?, ?, ?, NOW())");
                    $insertQry->execute([$le_id, $penalty_date, $penalty]);
                }
            }
        }
    } else {
        while ($start->format('m') <= $end->format('m')) {

            $penalty = 0;
            $start_for_penalty = $start->format('Y-m');

            $dueperday = $interest_amount / intval($start->format('t'));
            if ($status != 'pending') {
                if ($start->format('m') != $end->format('m')) {
                    $new_end_date = clone $start;
                    $new_end_date->modify('last day of this month');
                    $cur_result = (($start->diff($new_end_date))->days + 1) * $dueperday;
                    $result += $cur_result;
                } elseif ($end->format('Y-m-d') != date('Y-m-d')) {
                    $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    $result += $cur_result;
                } else {
                    $cur_result = (($start->diff($end))->days) * $dueperday;
                    $result += $cur_result;
                }
            } else {
                $new_end = clone $start;
                $new_end = $new_end->modify("last day of this month");
                $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                $result += $cur_result;
            }

            $start->modify('+1 month');

            $start->modify('first day of this month');

            if ($status == 'pending') {
                // Fetch penalty percentage or rupee amount from loan_category_creation
                $ovqry = $pdo->query("SELECT overdue_penalty AS overdue, overdue_type FROM `loan_category_creation` WHERE loan_category = '$loan_category'");
                $row = $ovqry->fetch();

                $penalty_val = $row['overdue'] ?? 0;
                $penalty_type = strtolower(trim($row['overdue_type'] ?? 'percentage')); // Default to 'percentage' if not set

                // Calculate paid interest for the month
                $paid_interest = getPaidInterest($pdo, $le_id);

                if ($paid_interest > 0) {
                    $cur_result =  $cur_result - $paid_interest;
                    if ($cur_result < 0) {
                        $cur_result = 0;
                    }
                }

                // Check if penalty already exists for the month
                $penalty_date = date('Y-m', strtotime($start_for_penalty));
                $checkPenalty = $pdo->query("SELECT 1 FROM penalty_charges WHERE penalty_date = '$penalty_date' AND loan_entry_id = '$le_id'");

                if ($checkPenalty->rowCount() == 0 && $cur_result > 0 && $penalty_val > 0) {
                    // Calculate penalty based on type
                    if ($penalty_type === 'rupee') {
                        $penalty = round($penalty_val);
                    } else { // Assume percentage by default
                        $penalty = round(($cur_result * $penalty_val) / 100);
                    }

                    // Insert new penalty record
                    $insertQry = $pdo->prepare("INSERT INTO penalty_charges (`loan_entry_id`, `penalty_date`, `penalty`, `created_date`) VALUES (?, ?, ?, NOW())");
                    $insertQry->execute([$le_id, $penalty_date, $penalty]);
                }
            }
        }
    }
    return $result;
}

function checkStatusOfCustomer($response, $loan_arr, $cus_id, $pdo)
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
