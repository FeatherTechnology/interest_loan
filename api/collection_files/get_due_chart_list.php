<?php
require '../../ajaxconfig.php';
?>
<table class="table custom-table table-responsive" id='dueChartListTable'>

    <?php
    $le_id = $_POST['le_id'];
    $curDateChecker = true;

    $loanStart = $pdo->query("SELECT * , due_startdate_calc, maturity_date_calc, due_method , interest_rate_calc , interest_calculate FROM loan_entry  WHERE id = '$le_id' ");
    $loanFrom = $loanStart->fetch();
    //If Due method is Monthly, Calculate penalty by checking the month has ended or not
    $due_startdate_calc = $loanFrom['due_startdate_calc'];
    $maturity_date_calc = $loanFrom['maturity_date_calc'];
    $interest_rate_calc = $loanFrom['interest_rate_calc'];
    $interest_calculate = $loanFrom['interest_calculate'];
    $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_startdate_calc);
    $end_date_obj = DateTime::createFromFormat('Y-m-d', $maturity_date_calc);
    $maturity_month_obj = new DateTime($maturity_date_calc);
    $i = 1;

    if ($loanFrom['due_method'] == 'Monthly') {
        //If Monthly Add one month interval to loop from start date to end date.
        $interval = new DateInterval('P1M'); // Create a one month interval
    }

    //Looping the Due month.
    $dueMonth[] = $due_startdate_calc;
    while ($start_date_obj < $end_date_obj) {
        $start_date_obj->add($interval);
        $dueMonth[] = $start_date_obj->format('Y-m-d');
    }

    $issueDate = $pdo->query("SELECT  le.interest_amnt_calc,  le.loan_amnt_calc, li.issue_date FROM loan_issue li 
    JOIN loan_entry le ON li.loan_entry_id = le.id
    JOIN customer_status cs ON cs.loan_entry_id = li.loan_entry_id   
    WHERE li.loan_entry_id = '$le_id' and cs.status >= 7 ");
    $loanIssue = $issueDate->fetch();

    //If Due method is Monthly, Calculate penalty by checking the month has ended or not
    //(For monthly interest total amount will not be there, so take principals)
    $loan_amount = intVal($loanIssue['loan_amnt_calc']);
    $interest_amnt_calc = intVal($loanIssue['interest_amnt_calc']);
    $princ_amt_1 = $loanIssue['loan_amnt_calc'];
    $issue_date = $loanIssue['issue_date'];
    ?>

    <thead>
        <tr>
            <th>Due No</th>
            <th style="width: 80px;">Due Date</th>
            <th>Principal Amount</th>
            <th>Interest Amount</th>
            <th>Pending Amount</th>
            <th>Payable Amount</th>
            <th style="width: 100px;">Paid Date</th>
            <th>Paid Interest Amount</th>
            <th>Balance Interest Amount</th>
            <th>Paid Principal Amount</th>
            <th>Balance principal Amount</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td> </td>
            <td><?php
                if ($loanFrom['due_method'] == 'Monthly') {
                    //For Monthly.
                    echo date('m-Y', strtotime($issue_date));
                } else {
                    //For Weekly && Day.
                    echo date('d-m-Y', strtotime($issue_date));
                } ?>
            </td>
            <td><?php echo $loan_amount; ?></td>
            <td><?php echo $interest_amnt_calc; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $loan_amount; ?></td>
            <td></td>
        </tr>
        <?php
        $issued = date('Y-m-d', strtotime($issue_date));
        if ($loanFrom['due_method'] == 'Monthly') {

            $run = $pdo->query("SELECT c.collection_id, c.loan_amount, c.interest_amount , c.pending_amount, c.payable_amount, c.collection_date, c.principal_amount_track, c.interest_amount_track, c.balance_amount, c.fine_charge_track, c.principal_waiver
            FROM `collection` c
            LEFT JOIN users u ON c.insert_login_id = u.id
            WHERE c.`loan_entry_id` = '$le_id' AND ( c.principal_waiver!='' OR c.principal_amount_track != '' OR c.interest_amount_track != '')
            AND (
                (
                    ( 
                        (
                            YEAR(c.collection_date) = YEAR('$due_startdate_calc') AND MONTH(c.collection_date) < MONTH('$due_startdate_calc')
                        ) OR (
                            YEAR(c.collection_date) < YEAR('$due_startdate_calc')
                        )
                    )
                ) 
            )");
        }

        //For showing data before due start date
        $totalPaid = 0;
        $totalPreClose = 0;
        $totalPaidPrinc = 0;
        $waiver = 0;
        $last_bal_amt = 0;
        $balance_amount = $loan_amount;

        if ($run->rowCount() > 0) {
            while ($row = $run->fetch()) {
                $waiver = $waiver + intVal($row['principal_waiver']);

                $PcollectionAmnt = intVal($row['principal_amount_track']);
                $IcollectionAmnt = intVal($row['interest_amount_track']);
                if ($last_bal_amt != 0) {
                    $balance_amount = $last_bal_amt - $PcollectionAmnt - $waiver;
                } else {
                    $balance_amount = $last_bal_amt - $PcollectionAmnt - $waiver;
                }

        ?>
                <tr>
                    <td><?php $pendingMinusCollection = (intVal($row['pending_amount'])); ?></td>
                    <td><?php $payableMinusCollection = (intVal($row['payable_amount'])); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['collection_date'])); ?></td>

                    <!-- for collected amt -->

                    <td>
                        <?php if ($PcollectionAmnt > 0) {
                            $totalPaidPrinc += $PcollectionAmnt;

                            echo $PcollectionAmnt;
                        } elseif ($row['principal_waiver'] > 0) {
                            $totalPreClose += $row['principal_waiver'];

                            echo $row['principal_waiver'];
                        } ?>
                    </td>
                    <td>
                        <?php if ($IcollectionAmnt > 0) {
                            echo $IcollectionAmnt;
                        } ?>
                    </td>

                    <td><?php echo $balance_amount; ?></td>
                    <td><?php if ($row['principal_waiver'] > 0) {
                            echo $row['principal_waiver'];
                        } else {
                            echo '0';
                        } ?></td>
                </tr>
                <?php
                $last_bal_amt = $balance_amount;
            }
        } else {
            $last_bal_amt = $loan_amount;
        }

        //For showing collection after due start date
        $waiver = 0;
        $totalPaidPrinc = 0;
        $jj = 0;
        $last_int_amt = $interest_amnt_calc;
        $last_princ_amt = $last_bal_amt;

        $lastCusdueMonth = '1970-00-00';
        foreach ($dueMonth as $cusDueMonth) {
            if ($loanFrom['due_method'] == 'Monthly') {
                //Query for Monthly.
                $run = $pdo->query("SELECT c.collection_id, c.loan_amount,  c.interest_amount , c.pending_amount, c.payable_amount, c.collection_date, c.principal_amount_track, c.interest_amount_track, c.balance_amount, c.fine_charge_track, c.principal_waiver  FROM `collection` c 
                LEFT JOIN users u ON c.insert_login_id = u.id 
                WHERE c.loan_entry_id = $le_id AND ( c.principal_amount_track != '' OR c.interest_amount_track != '' 
                OR c.principal_waiver != '') 
                AND MONTH(c.collection_date) = MONTH('$cusDueMonth') 
                AND YEAR(c.collection_date) = YEAR('$cusDueMonth');");
            }

            if ($run->rowCount() > 0) {
                while ($row = $run->fetch()) {

                    if ($loanFrom['due_method'] == 'Monthly') {
                        $principal_amount_track = intVal($row['principal_amount_track']);
                        $interest_amount_track = intVal($row['interest_amount_track']);
                    }

                    $waiver = intVal($row['principal_waiver']);
                    $balance_amount = intVal($last_princ_amt) - $principal_amount_track - $waiver;
                ?>
                    <tr>
                        <?php
                        if ($loanFrom['due_method'] == 'Monthly') { //this is for monthly loan to check lastcusduemonth comparision
                            if (date('Y-m', strtotime($lastCusdueMonth)) != date('Y-m', strtotime($row['collection_date']))) {
                                // this condition is to check whether the same month has collection again. if yes the no need to show month name and due amount and serial number
                        ?>
                                <td><?php echo $i;
                                    $i++; ?>
                                </td>

                                <td>
                                    <?php
                                    if ($loanFrom['due_method'] == 'Monthly') {
                                        //For Monthly.
                                        echo date('m-Y', strtotime($cusDueMonth));
                                    } else {
                                        //For Weekly && Day.
                                        echo date('d-m-Y', strtotime($cusDueMonth));
                                    }
                                    ?>
                                </td>

                            <?php } else { ?>
                                <td></td>
                                <td></td>
                            <?php }
                        } else { //this is for weekly and daily loan to check lastcusduemonth comparision
                            if (date('Y-m-d', strtotime($lastCusdueMonth)) != date('Y-m-d', strtotime($row['collection_date']))) {
                                // this condition is to check whether the same month has collection again. if yes the no need to show month name and due amount and serial number
                            ?>
                                <td><?php echo $i;
                                    $i++; ?></td>
                                <td><?php
                                    if ($loanFrom['due_method'] == 'Monthly') {
                                        //For Monthly.
                                        echo date('m-Y', strtotime($cusDueMonth));
                                    } else {
                                        //For Weekly && Day.
                                        echo date('d-m-Y', strtotime($cusDueMonth));
                                    }
                                    ?></td>
                            <?php } else { ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        <?php }
                        } ?>

                        <!-- Else part end  -->
                        <td><?php echo $last_princ_amt; ?></td>

                        <!-- Interest Calculation based on reduced principal -->
                        <td>
                            <?php
                            $interest_rate_calc = $loanFrom['interest_rate_calc'];
                            $current_principal = $last_princ_amt;
                            $interest_calculate = $loanFrom['interest_calculate']; // 'Month' or 'Days'

                            // Interest calculation
                            if ($interest_calculate == 'Month') {
                                $int = $current_principal * ($interest_rate_calc / 100);
                            } else if ($interest_calculate == 'Days') {
                                $int = ($current_principal * ($interest_rate_calc / 100) / 30);
                            } else {
                                $int = 0; // default fallback
                            }

                            // Round up to next multiple of 5
                            $curInterest = ceil($int / 5) * 5;
                            if ($curInterest < $int) {
                                $curInterest += 5;
                            }

                            echo $curInterest;
                            ?>
                        </td>

                        <!-- Pending Amount -->
                        <td>
                            <?php $pendingMinusCollection = (intVal($row['pending_amount']));
                            if ($pendingMinusCollection != '') {
                                echo $pendingMinusCollection;
                            } else {
                                echo 0;
                            } ?>
                        </td>

                        <!-- Payable Amount -->
                        <td>
                            <?php $payableMinusCollection = (intVal($row['payable_amount']));
                            if ($payableMinusCollection != '') {
                                echo $payableMinusCollection;
                            } else {
                                echo 0;
                            } ?>
                        </td>

                        <!-- Paid Date -->
                        <td>
                            <?php echo date('d-m-Y', strtotime($row['collection_date'])); ?>
                        </td>

                        <!-- Paid Interest Amount -->
                        <td>
                            <?php $paidInterestMinusCollection = (intVal($row['interest_amount_track']));
                            if ($paidInterestMinusCollection != '') {
                                echo $paidInterestMinusCollection;
                            } else {
                                echo 0;
                            } ?>
                        </td>

                        <!-- Balance Interest Amount (Payable - Paid) -->
                        <td>
                            <?php $balanceInterestAmount = $payableMinusCollection - $paidInterestMinusCollection;
                            if ($paidInterestMinusCollection != '') {
                                echo $balanceInterestAmount;
                            } else {
                                echo 0;
                            } ?>
                        </td>

                        <!-- Paid Principal Amount -->
                        <td>
                            <?php $paidPrincipalAmount = (intVal($row['principal_amount_track']));
                            if ($paidPrincipalAmount != '') {
                                echo $paidPrincipalAmount;
                            } else {
                                echo 0;
                            } ?>
                        </td>

                        <!-- Balance Principal Amount -->
                        <td>
                            <?php
                            $balancePrincipaltAmount = $balance_amount;
                            echo $balancePrincipaltAmount != '' ? $balancePrincipaltAmount : 0;
                            $last_princ_amt = $balancePrincipaltAmount;
                            ?>
                        </td>

                        <!-- Action -->
                        <td>
                            <a class='print_due_coll' id="" value="<?php echo $row['collection_id']; ?>"> <i class="fa fa-print" aria-hidden="true"></i> </a>
                        </td>
                    </tr>

                <?php $lastCusdueMonth = date('d-m-Y', strtotime($cusDueMonth)); //assign this cusDueMonth to check if coll date is already showed before
                }
            } else {
                ?>
                <tr>
                    <td><?php echo $i; ?></td>

                    <td><?php
                        if ($loanFrom['due_method'] == 'Monthly') {
                            //For Monthly.
                            echo date('m-Y', strtotime($cusDueMonth));
                        } ?>
                    </td>
                    <?php
                    if ($loanFrom['due_method'] == 'Monthly') {
                        if (date('Y-m', strtotime($cusDueMonth)) <=  date('Y-m')) { ?>
                            <td>
                                <?php echo $last_princ_amt; ?>
                            </td>

                            <!-- Interest Calculation based on reduced principal -->
                            <td>
                                <?php
                                $interest_rate_calc = $loanFrom['interest_rate_calc'];
                                $current_principal = $last_princ_amt;
                                $interest_calculate = $loanFrom['interest_calculate']; // 'Month' or 'Days'

                                // Interest calculation
                                if ($interest_calculate == 'Month') {
                                    $int = $current_principal * ($interest_rate_calc / 100);
                                } else if ($interest_calculate == 'Days') {
                                    $int = ($current_principal * ($interest_rate_calc / 100) / 30);
                                } else {
                                    $int = 0; // default fallback
                                }

                                // Round up to next multiple of 5
                                $curInterest = ceil($int / 5) * 5;
                                if ($curInterest < $int) {
                                    $curInterest += 5;
                                }

                                echo $curInterest;
                                ?>
                            </td>

                            <!-- Pending Calculation -->
                            <td>
                                <?php
                                $pendingval = ceil(pendingCalculation($loanFrom, $curInterest, $pdo, $le_id));
                                echo $pendingval;
                                ?>
                            </td>

                            <td>
                                <?php
                                $payable = ceil(payableCalculation($loanFrom, $curInterest, $pdo, $le_id));
                                echo ceilAmount($payable);
                                ?>
                            </td>


                        <?php } else if (date('Y-m', strtotime($cusDueMonth)) >  date('Y-m') && $curDateChecker == true) { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php
                            $curDateChecker = false; //set to false because, pending and payable only need one month after current month
                        } else {
                        ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php
                        }
                    } else {
                        if (date('Y-m-d', strtotime($cusDueMonth)) <=  date('Y-m-d')) { ?>
                            <td>
                                <?php
                                ?>
                            </td>
                            <td>
                                <?php
                                ?>
                            </td>
                        <?php } else if (date('Y-m-d', strtotime($cusDueMonth)) >  date('Y-m-d') && $curDateChecker == true) { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php
                            $curDateChecker = false; //set to false because, pending and payable only need one month after current month
                        } else {
                        ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                    <?php
                        }
                    }
                    ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            <?php
                $i++;
            }
        }

        $currentMonth = date('Y-m-d');
        //The collection_date column is using datetime datatype if check only date it not return record.
        //Using between is useful to check last year to current year. 
        $startTime = '00:00:00'; //Set starting time of clock
        $endTime = '23:59:59'; //set end time of clock 
        $currentMonth = $currentMonth . ' ' . $endTime;
        if ($loanFrom['due_method'] == 'Monthly') {
            $maturity_date_calc = $maturity_month_obj->modify('+1 month')->format('Y-m-01');
            $maturity_date_calc = $maturity_date_calc . ' ' . $startTime;
            //Query for Monthly.
            $run = $pdo->query("SELECT c.collection_id, c.pending_amount, c.payable_amount, c.collection_date, c.principal_amount_track ,c.interest_amount_track, c.balance_amount, c.fine_charge_track, c.principal_waiver
            FROM `collection` c
            LEFT JOIN users u ON c.insert_login_id = u.id
            WHERE c.`loan_entry_id` = '$le_id' AND (c.principal_waiver!='')
            AND 
            (
                (c.collection_date BETWEEN '$maturity_date_calc' AND '$currentMonth') 
            )");
        }

        if ($run->rowCount() > 0) {
            $waiver = 0;
            while ($row = $run->fetch()) {
                $waiver = intVal($row['principal_waiver']);
                $balance_amount = $balance_amount  - $waiver;
            ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td><?php $pendingMinusCollection = (intVal($row['pending_amount']));
                        if ($pendingMinusCollection != '') {
                            echo $pendingMinusCollection;
                        } else {
                            echo 0;
                        } ?>
                    </td>

                    <td><?php $payableMinusCollection = (intVal($row['payable_amount']));
                        if ($payableMinusCollection != '') {
                            echo $payableMinusCollection;
                        }
                        ?>
                    </td>

                    <td><?php echo date('d-m-Y', strtotime($row['collection_date'])); ?></td>

                    <td>
                        <?php if ($PcollectionAmnt > 0) {
                            echo $PcollectionAmnt;
                        } elseif ($row['principal_waiver'] > 0) {
                            echo $row['principal_waiver'];
                        } ?>
                    </td>

                    <td>
                        <?php if ($IcollectionAmnt > 0) {
                            echo $IcollectionAmnt;
                        } ?>
                    </td>

                    <td><?php echo $balance_amount; ?></td>

                    <td><?php if ($row['principal_waiver'] > 0) {
                            echo $row['principal_waiver'];
                        } else {
                            echo '0';
                        } ?></td>
                </tr>

        <?php
                $i++;
            }
        }
        ?>

    </tbody>
</table>


<?php

function pendingCalculation($loanFrom, $curInterest, $pdo, $le_id)
{
    $pending_amount = getTillDateInterest($loanFrom, $curInterest, $pdo, 'pendingmonth', $le_id);
    return $pending_amount;
}

function getTillDateInterest($loanFrom, $curInterest, $pdo, $data, $le_id)
{
    if ($data == 'forstartmonth') {
        $issued_date = new DateTime(date('Y-m-d', strtotime($loanFrom['loan_date'])));
        $cur_date = new DateTime(date('Y-m-d'));

        $result = dueAmtCalculation($pdo, $issued_date, $cur_date, $curInterest, $loanFrom, '', $le_id);
        $cur_amt = ceil($result / 5) * 5;
        if ($cur_amt < $result) {
            $cur_amt += 5;
        }
        return $cur_amt;
    }

    if ($data == 'curmonth') {
        $issued_date = new DateTime(date('Y-m-d', strtotime($loanFrom['loan_date'])));
        $cur_date = new DateTime(date('Y-m-d'));
        return dueAmtCalculation($pdo, $issued_date, $cur_date, $curInterest, $loanFrom, '', $le_id);
    }

    if ($data == 'pendingmonth') {
        $issued_date = new DateTime(date('Y-m-d', strtotime($loanFrom['loan_date'])));
        $cur_date = new DateTime(date('Y-m-d'));
        $cur_date->modify('-2 months');
        $cur_date->modify('last day of this month');

        if ($issued_date->format('m') <= $cur_date->format('m')) {
            return dueAmtCalculation($pdo, $issued_date, $cur_date, $curInterest, $loanFrom, 'pending', $le_id);
        }
        return 0;
    }

    return $curInterest;
}

function dueAmtCalculation($pdo, $start_date, $end_date, $interest_amount, $loanFrom, $status, $le_id)
{
    $start = $start_date->format('Y-m-d');
    $start = new DateTime($start);
    $end = $end_date->format('Y-m-d');
    $end = new DateTime($end);

    $interest_rate_calc = $loanFrom['interest_rate_calc'];
    $interest_calculate = $loanFrom['interest_calculate'];

    $result = 0;
    $qry = $pdo->query("SELECT principal_amount_track FROM `collection` WHERE loan_entry_id = '" . $le_id . "' and principal_amount_track != '' ORDER BY collection_date ASC ");
    if ($qry->rowCount() > 0) {

        while ($start->format('m') <= $end->format('m')) {
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
        }
    } else {
        while ($start->format('m') <= $end->format('m')) {

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
        }
    }
    return $result;
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

function payableCalculation($loanFrom, $curInterest, $pdo, $le_id)
{
    $issued_date = new DateTime(date('Y-m-d', strtotime($loanFrom['loan_date'])));
    $cur_date = new DateTime(date('Y-m-d'));
    $last_month = clone $cur_date;
    $last_month->modify('-1 month'); // last month same date 
    $result = 0;
    $st_date = clone $issued_date;
    while ($st_date->format('m') <= $last_month->format('m')) {
        $end_date = clone $st_date;
        $end_date->modify('last day of this month');
        $start = clone $st_date; //because the function calling below will change the root of starting date

        $result += dueAmtCalculation($pdo, $start, $end_date, $curInterest, $loanFrom, 'payable', $le_id);

        $st_date->modify('+1 month');
        $st_date->modify('first day of this month');
    }

    return $result;
}

function ceilAmount($amt)
{
    $cur_amt = ceil($amt / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
    if ($cur_amt < $amt) {
        $cur_amt += 5;
    }
    return $cur_amt;
}

$pdo = null; //Close Connection