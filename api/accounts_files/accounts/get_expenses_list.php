<?php
require "../../../ajaxconfig.php";
require_once '../../../include/views/money_format_india.php';

@session_start();
$user_id = $_SESSION['user_id'];

$exp_cat = ["1" => 'Pooja', "2" => 'Vehicle', "3" => 'Fuel', "4" => 'Stationary', "5" => 'Press', "6" => 'Food', "7" => 'Rent', "8" => 'EB', "9" => 'Mobile bill', "10" => 'Office Maintenance', "11" => 'Salary', "12" => 'Tax & Auditor', "13" => 'Int Less', "14" => 'Agent Incentive', "15" => 'Common', "16" => 'Other'];

$cash_type = ["1" => 'Hand Cash', "2" => 'Bank Cash'];

$exp_list_arr = array();

$qry = $pdo->query("SELECT e.*, bc.branch_name, b.bank_name, ac.agent_name FROM expenses e 
LEFT JOIN branch_creation bc ON e.branch = bc.id 
LEFT JOIN bank_creation b ON e.bank_id = b.id 
LEFT JOIN agent_creation ac ON e.agent_id = ac.id 
WHERE e.insert_login_id = '$user_id' AND DATE(e.created_on) = CURDATE() ");

if ($qry->rowCount() > 0) {
    while ($result = $qry->fetch()) {
        $result['collection_mode'] = $cash_type[$result['collection_mode']];
        $result['bank_id'] = $result['bank_name'];
        $result['branch'] = $result['branch_name'];
        $result['agent_id'] = $result['agent_name'];
        $result['expenses_category'] = $exp_cat[$result['expenses_category']];
        $result['amount'] = moneyFormatIndia($result['amount']);
        $result['action'] = "<span class='icon-trash-2 expDeleteBtn' value='" . $result['id'] . "'></span>";
        $exp_list_arr[] = $result;
    }
}

$pdo = null; // Close connection.
echo json_encode($exp_list_arr);
