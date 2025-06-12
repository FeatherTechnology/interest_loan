<?php
require '../../ajaxconfig.php';
$collection_id = $_POST["collection_id"];

$qry = $pdo->query("SELECT * FROM `collection` WHERE collection_id ='" . strip_tags($collection_id) . "'");
$row = $qry->fetch();

extract($row); // Extracts the array values into variables

$qry = $pdo->query("SELECT le.loan_id, lc.loan_category, lnc.linename , cc.first_name, cc.last_name
FROM loan_entry le 
LEFT JOIN customer_creation cc ON cc.cus_id = le.cus_id
LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.id
LEFT JOIN loan_category lc ON lcc.loan_category = lc.id
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
WHERE le.id = '$loan_entry_id'");

$row = $qry->fetch();
$line_name = $row['linename'];
$loan_category = $row['loan_category'];
$loan_id = $row['loan_id'];
$cus_name = $row['first_name'] . ' ' . (!empty($row['last_name']) ? $row['last_name'] : '');

$interest_amount_track = intVal($interest_amount_track != '' ? $interest_amount_track : 0);
$principal_amount_track = intVal($principal_amount_track != '' ? $principal_amount_track : 0);
$penalty_track = intVal($penalty_track != '' ? $penalty_track : 0);
$fine_charge_track = intVal($fine_charge_track != '' ? $fine_charge_track : 0);
$net_received = $principal_amount_track + $penalty_track + $fine_charge_track;
$interest_balance = ($interest_amount - $interest_amount_track) < 0 ? 0 : $interest_amount - $interest_amount_track;
$loan_balance = getBalance($pdo, $loan_entry_id, $collection_date);

?>

<div class="frame" id="dettable" style="position: relative; width: 302px; height: auto; background-color: #ffffff;">
    <img class="group"  src="img/fav.png" style="display: block; margin: 20px auto; width: 150px; height: 91px;" />

    <table style="width: 100%; font-size: 18px; padding: 0 15px; font-family: Arial, sans-serif;">
        <tr>
            <td style="text-align:right;"><b>Receipt No :</b></td>
            <td><?php echo $collection_id; ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Date / Time :</td>
            <td><?php echo date('d-m-Y h:i A', strtotime($collection_date)); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Line / Area :</td>
            <td><?php echo $line_name; ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Customer ID :</td>
            <td><?php echo $cus_id; ?></td>
        </tr>
        <tr>
            <td style="text-align:right;"><b>Customer Name :</b></td>
            <td><b><?php echo $cus_name; ?></b></td>
        </tr>
        <tr>
            <td style="text-align:right;">Loan Category :</td>
            <td><?php echo $loan_category; ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Loan No :</td>
            <td><?php echo $loan_id; ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Paid Principal Amount :</td>
            <td><?php echo moneyFormatIndia($principal_amount_track); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Paid Interest Amount :</td>
            <td><?php echo moneyFormatIndia($interest_amount_track); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Penalty :</td>
            <td><?php echo moneyFormatIndia($penalty_track); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Fine :</td>
            <td><?php echo moneyFormatIndia($fine_charge_track); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;"><b>Net Received :</b></td>
            <td><b><?php echo moneyFormatIndia($net_received); ?></b></td>
        </tr>
        <tr>
            <td style="text-align:right;">Interest Balance :</td>
            <td><?php echo moneyFormatIndia($interest_balance); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;">Loan Balance :</td>
            <td><?php echo moneyFormatIndia($loan_balance); ?></td>
        </tr>
    </table>
</div>


<button type="button" name="printpurchase" onclick="poprint()" id="printpurchase" class="btn btn-primary">Print</button>

<script type="text/javascript">
    function poprint() {
        var Bill = document.getElementById("dettable").innerHTML;
        var printWindow = window.open('', '', 'height=1000;weight=1000;');
        printWindow.document.write('<html><head></head><body>');
        printWindow.document.write(Bill);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }
    setTimeout(() => {
        document.getElementById("printpurchase").click();

    }, 1500);
</script>

<?php
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

function getBalance($pdo, $loan_entry_id, $collection_date)
{
    $result = $pdo->query("SELECT * FROM `loan_entry` WHERE id = $loan_entry_id ");
    if ($result->rowCount() > 0) {
        $row = $result->fetch();
        $loan_arr = $row;

        $response['loan_amount'] = intVal($loan_arr['loan_amnt_calc']);
    }

    $coll_arr = array();
    $result = $pdo->query("SELECT * FROM `collection` WHERE loan_entry_id ='" . $loan_entry_id . "' and date(collection_date) <= date('" . $collection_date . "') ");
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {
            $coll_arr[] = $row;
        }
        $total_paid_princ = 0;
        $total_paid_int = 0;
        $principal_waiver = 0;
        foreach ($coll_arr as $tot) {
            $total_paid_princ += intVal($tot['principal_amount_track']);
            $total_paid_int += intVal($tot['interest_amount_track']);
            $principal_waiver += intVal($tot['principal_waiver']); //get pre closure value to subract to get balance amount
        }
        //total paid amount will be all records again request id should be summed
        $response['paid_amount'] =  $total_paid_princ;
        $response['total_paid_int'] = $total_paid_int;
        $response['balance'] = $response['loan_amount'] - $response['paid_amount'] - $principal_waiver;
    } else {
        $response['balance'] = $response['loan_amount'];
    }
    return $response['balance'];
}

$pdo = null; //Close Connection
?>

