<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$le_id = $_POST['le_id'];
$cus_id = $_POST['cus_id'];
$status = $_POST['status'];
$sub_status = $_POST['sub_status'];
$loan_amount = $_POST['loan_amount'];
$paid_amount = $_POST['paid_amount'];
$balance_amount = $_POST['balance_amount'];
$interest_amount = $_POST['interest_amount'];
$pending_amount = $_POST['pending_amount'];
$payable_amount = $_POST['payable_amount'];
$penalty = $_POST['penalty'];
$fine_charge = $_POST['fine_charge'];
$interest_amount_track = $_POST['interest_amount_track'];
$penalty_track = $_POST['penalty_track'];
$fine_charge_track = $_POST['fine_charge_track'];
$principal_amount_track = $_POST['principal_amount_track'];
$total_paid_track = $_POST['total_paid_track'];
$interest_waiver = $_POST['interest_waiver'];
$penalty_waiver = $_POST['penalty_waiver'];
$fine_charge_waiver = $_POST['fine_charge_waiver'];
$principal_waiver = $_POST['principal_waiver'];
$total_waiver = $_POST['total_waiver'];
$collection_date = date('Y-m-d', strtotime($_POST['collection_date']));
$collection_mode = $_POST['collection_mode'];
$bank_id = $_POST['bank_id'];
$trans_id = $_POST['trans_id'];


try {

    // Begin transaction
    $pdo->beginTransaction();

    $myStr = 'COL';
    $collection_id = '';
    $selectIC = $pdo->query("SELECT collection_id FROM `collection` WHERE collection_id != '' ORDER BY id DESC LIMIT 1 FOR UPDATE");

    if ($selectIC->rowCount() > 0) {
        $row = $selectIC->fetch();
        $ac2 = $row["collection_id"];
        $appno2 = ltrim(strstr($ac2, '-'), '-');
        $appno2 = intval($appno2) + 1;
        $collection_id = $myStr . "-" . $appno2;
    } else {
        $collection_id = $myStr . "-101";
    }

    $qry = $pdo->query("INSERT INTO `collection`( `loan_entry_id`, `cus_id`, `collection_status`, `coll_sub_status`, `loan_amount`, `paid_amount`, `balance_amount`, `interest_amount`, `pending_amount`, `payable_amount`, `penalty`, `fine_charge`, `interest_amount_track`, `penalty_track`, `fine_charge_track`, `principal_amount_track`, `total_paid_track`, `interest_waiver`, `penalty_waiver`, `fine_charge_waiver`, `principal_waiver`, `total_waiver`, `collection_date`, `collection_id`, `collection_mode`, `bank_id`, `transaction_id`, `insert_login_id`, `created_date`) VALUES ('$le_id','$cus_id','$status','$sub_status','$loan_amount','$paid_amount','$balance_amount','$interest_amount','$pending_amount','$payable_amount','$penalty','$fine_charge','$interest_amount_track','$penalty_track','$fine_charge_track','$principal_amount_track','$total_paid_track','$interest_waiver','$penalty_waiver','$fine_charge_waiver','$principal_waiver','$total_waiver','" . $collection_date . ' ' . date('H:i:s') . "','$collection_id','$collection_mode','$bank_id','$trans_id',
    '$user_id',current_timestamp )");

    if ($qry) {
        $collection_id = $collection_id;
        $result = '1';
    } else {
        $result = '2';
    }

    if (($penalty_track != '' AND $penalty_track > 0) or ($penalty_waiver != '' AND $penalty_waiver > 0)) {
        $qry1 = $pdo->query("INSERT INTO `penalty_charges`(`loan_entry_id`, `paid_date`, `paid_amnt`, `waiver_amnt`, `created_date`) VALUES ('$le_id','$collection_date','$penalty_track','$penalty_waiver', current_timestamp) ");
    }

    if ($fine_charge_track != '' or $fine_charge_waiver != '') {
        $qry2 = $pdo->query("INSERT INTO `fine_charges`(`loan_entry_id`, `paid_date`, `paid_amnt`, `waiver_amnt`) VALUES ('$le_id','$collection_date','$fine_charge_track','$fine_charge_waiver')");
    }

    $check = intval($principal_waiver) - intval($balance_amount);

    if (($principal_amount_track != '' or $interest_amount_track != '')) {
        $check = intVal($principal_amount_track) + intVal($principal_waiver) - intval($balance_amount);
    }

    $penalty_check = intval($penalty_track) + intval($penalty_waiver) - intval($penalty);
    $fine_charge_check = intval($fine_charge_track) + intval($fine_charge_waiver) - intval($fine_charge);

    if ($check == 0 && $penalty_check == 0 && $fine_charge_check == 0) {
        $closedQry = $pdo->query("UPDATE `customer_status` SET `collection_status`='Closed', `status`='8',`update_login_id`='$user_id',`updated_on`=now() WHERE `loan_entry_id`='$le_id' "); //balance is zero change the customer status as 8, moved to closed.
        if ($closedQry) {
            $result = '3';
        }
    }

    $pdo->commit(); //  Commit
} catch (Exception $e) {
    $pdo->rollBack(); //  Rollback on error
    echo json_encode(['result' => 'error', 'message' => $e->getMessage()]);
    exit;
}

$pdo = null; //Close Connection
echo json_encode(['result' => $result, 'collection_id' => $collection_id]); // Return collection ID in response
