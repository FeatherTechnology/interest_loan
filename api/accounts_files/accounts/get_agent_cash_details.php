<?php
require "../../../ajaxconfig.php";

$id = $_POST['id'];
$cash_type = $_POST['collection_mode'];
if ($cash_type == '1') {
    $cndtn = "li.payment_mode = '1' ";
    $cndtn1 = "collection_mode = '1' ";
} elseif ($cash_type == '2') {
    $cndtn = "li.payment_mode != '1' ";
    $cndtn1 = "collection_mode = '2' ";
}

$row = array();

$qry = $pdo->query("SELECT COUNT(li.id) AS total_issued,
            SUM(
            CASE 
                WHEN li.payment_mode = '1' THEN COALESCE(li.cash, 0)
                ELSE COALESCE(li.cheque_val, 0) + COALESCE(li.transaction_val, 0)
            END
        ) AS total_amount FROM `loan_issue` li 
LEFT JOIN customer_status cs ON li.loan_entry_id = cs.loan_entry_id 
LEFT JOIN loan_entry le ON li.loan_entry_id = le.id 
WHERE le.referred_calc = '0' AND le.agent_id_calc = '$id' AND $cndtn AND li.issue_date > COALESCE( (SELECT created_on FROM expenses WHERE agent_id = '$id' AND $cndtn1 ORDER BY id DESC LIMIT 1), '1970-01-01 00:00:00' ) AND li.issue_date <= NOW()  ");

if ($qry->rowCount() > 0) {
    $row = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; //Close Connection.
echo json_encode($row);
