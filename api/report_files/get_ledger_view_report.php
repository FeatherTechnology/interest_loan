<?php
include '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';
$inputDate = $_POST['toDate'];
$loan_category = $_POST['loan_category'];
$to_date = date('Y-m-d', strtotime($inputDate)) . ' 23:59:59';

?>

<style>
    table.custom-table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
    }

    table.custom-table th {
        border-top: 1px solid white;
        border-left: 1px solid white;
        text-align: center;
        vertical-align: middle;
        padding: 8px;
    }

    table.custom-table th[colspan] {
        text-align: center;
    }
</style>

<table class="table custom-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 50px;">S.No</th>
            <th rowspan="2" style="width: 70px;">Cus ID</th>
            <th rowspan="2" style="width: 130px;">Customer Name</th>
            <th rowspan="2" style="width: 90px;">Loan ID</th>
            <th rowspan="2" style="width: 100px;">Loan Date</th>
            <th rowspan="2" style="width: 100px;">Maturity Date</th>
            <th rowspan="2" style="width: 130px;">Principal Balance</th>

            <?php
            $todate = new DateTime($to_date);
            $startDate = clone $todate;
            $startDate->modify('-11 months');
            $months = generateMonths($startDate, $todate);

            foreach ($months as $month) {
                echo '<th colspan="2" style="width: 160px;">' . date('M', strtotime($month)) . '</th>';
            }
            ?>
            <th colspan="2" style="width: 160px;">Paid Amount</th>
            <th rowspan="2" style="width: 150px;">Chart</th>
        </tr>
        <tr>
            <?php
            foreach ($months as $month) {
                echo '<th style="width: 80px;">Principal</th> <th style="width: 80px;">Interest</th>';
            }
            ?>
            <th style="width: 80px;">Principal</th>
            <th style="width: 80px;">Interest</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $query = "SELECT 
        li.loan_entry_id, 
        cc.cus_id, 
        CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, 
        le.loan_id, 
        li.issue_date, 
        le.maturity_date_calc, 
        c.principal_amount_track, 
        c.interest_amount_track, 
        le.loan_amount  
    FROM loan_issue li
    JOIN loan_entry le ON li.loan_entry_id = le.id
    JOIN customer_creation cc ON le.cus_id = cc.cus_id
    LEFT JOIN (
        SELECT 
            loan_entry_id, 
            SUM(principal_amount_track) AS principal_amount_track, 
            SUM(interest_amount_track) AS interest_amount_track,
            SUM(balance_amount) AS balance_amount
        FROM collection 
        WHERE collection_date <= '$to_date'
        GROUP BY loan_entry_id
    ) c ON li.loan_entry_id = c.loan_entry_id
    WHERE 
    li.issue_date <= '$to_date'
    AND (
        (IFNULL(c.principal_amount_track, 0) != le.loan_amount)
        OR (
            (IFNULL(c.principal_amount_track, 0) = le.loan_amount)
            AND EXISTS (
                SELECT 1 FROM collection col 
                WHERE col.loan_entry_id = li.loan_entry_id 
                AND DATE(col.collection_date) = DATE('" . date('Y-m-d', strtotime($inputDate)) . "')
            )
        )
    )
    AND le.loan_category = '$loan_category'
    GROUP BY li.loan_entry_id 
    ORDER BY li.id ASC";

        $dailyData = $pdo->prepare($query);
        $dailyData->execute();
        $i = 1;
        $total_balance_amount = 0;
        $total_paid_principal = 0;
        $total_paid_interest = 0;

        while ($dailyInfo = $dailyData->fetch()) {
            // Reset row-level totals
            $row_total_principal = 0;
            $row_total_interest = 0;

        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $dailyInfo['cus_id']; ?></td>
                <td><?php echo $dailyInfo['cus_name']; ?></td>
                <td><?php echo $dailyInfo['loan_id']; ?></td>
                <td><?php echo date('d-m-Y', strtotime($dailyInfo['issue_date'])); ?></td>
                <td><?php echo date('d-m-Y', strtotime($dailyInfo['maturity_date_calc'])); ?></td>
                <td>
                    <?php
                    $balance_amount = intval($dailyInfo['loan_amount']) - intval($dailyInfo['principal_amount_track']);
                    echo moneyFormatIndia($balance_amount);
                    ?>
                </td>
                <?php
                for ($j = 0; $j < count($months); $j++) {
                    $coll_qry = $pdo->query(" SELECT COALESCE(SUM(principal_amount_track), 0) AS principal_amount_track, 
                                COALESCE(SUM(interest_amount_track), 0) AS interest_amount_track 
                                FROM collection WHERE loan_entry_id = '" . $dailyInfo['loan_entry_id'] . "' 
                                AND MONTH(collection_date) = MONTH('" . $months[$j] . "') 
                                AND YEAR(collection_date) = YEAR('" . $months[$j] . "')
                                AND collection_date <= '$to_date'");

                    $coll_row = $coll_qry->fetch();

                    echo '<td>' . moneyFormatIndia($coll_row['principal_amount_track']) . '</td>';
                    echo '<td>' . moneyFormatIndia($coll_row['interest_amount_track']) . '</td>';

                    // Row-wise totals
                    $row_total_principal += $coll_row['principal_amount_track'];
                    $row_total_interest += $coll_row['interest_amount_track'];
                }

                // Print per-loan total paid principal & interest
                echo '<td>' . moneyFormatIndia($row_total_principal) . '</td>';
                echo '<td>' . moneyFormatIndia($row_total_interest) . '</td>';

                $total_paid_principal += $row_total_principal;
                $total_paid_interest += $row_total_interest;
                $total_balance_amount += $balance_amount;

                ?>
                <td>
                    <input type="button"
                        class="btn btn-primary due-chart"
                        value="Due Chart"
                        data-cusid="<?php echo $dailyInfo['cus_id']; ?>"
                        data-loanid="<?php echo $dailyInfo['loan_entry_id']; ?>">
                </td>

            </tr>
        <?php
        }
        ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="6"><b>Total</b></td>
            <td><b><?php echo moneyFormatIndia($total_balance_amount); ?></b></td>

            <?php
            // Leave empty cells for each month's principal and interest columns
            for ($j = 0; $j < count($months); $j++) {
                echo "<td></td><td></td>";
            }
            ?>

            <td><b><?php echo moneyFormatIndia($total_paid_principal); ?></b></td>
            <td><b><?php echo moneyFormatIndia($total_paid_interest); ?></b></td>
        </tr>
    </tfoot>

</table>

<?php

// Function to loop through months
function generateMonths($start, $end)
{
    $months = [];
    $currentDate = clone $start;

    while ($currentDate <= $end) {
        $months[] = $currentDate->format('Y-m-d');
        $currentDate->modify('+1 month');
    }

    return $months;
}

$pdo = null; // Close Connection