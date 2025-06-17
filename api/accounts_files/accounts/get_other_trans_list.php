<?php
require "../../../ajaxconfig.php";
require_once '../../../include/views/money_format_india.php';

@session_start();
$user_id = $_SESSION['user_id'];

$trans_cat = ["1" => 'Deposit', "2" => 'Investment', "3" => 'EL', "4" => 'Exchange', "5" => 'Bank Deposit', "6" => 'Bank Withdrawal', "7" => 'Loan Advance', "8" => 'Other Income'];

$cash_type = ["1" => 'Hand Cash', "2" => 'Bank Cash'];
$crdr = ["1" => 'Credit', "2" => 'Debit'];
$trans_list_arr = array();


$qry = $pdo->query("SELECT ot.*, b.name AS transname, d.name as username, e.bank_name as bank_namecash FROM `other_transaction` ot 
JOIN other_trans_name b ON ot.name =b.id 
LEFT JOIN users d ON ot.user_name = d.id 
LEFT JOIN bank_creation e ON ot.bank_id = e.id 
WHERE ot.insert_login_id = '$user_id' AND DATE(ot.created_on) = CURDATE() ");

if ($qry->rowCount() > 0) {
    while ($result = $qry->fetch()) {
        $result['collection_mode'] = $cash_type[$result['collection_mode']];
        $result['bank_namecash'] = $result['bank_namecash'];
        $result['trans_cat'] = $trans_cat[$result['trans_cat']];
        $result['name'] = $result['transname'];
        $result['type'] = $crdr[$result['type']];
        $result['username'] = $result['username'];
        $result['amount'] = moneyFormatIndia($result['amount']);
        $result['action'] = "<span class='icon-trash-2 transDeleteBtn' value='" . $result['id'] . "'></span>";
        $trans_list_arr[] = $result;
    }
}

$pdo = null; // Close connection.
echo json_encode($trans_list_arr);


