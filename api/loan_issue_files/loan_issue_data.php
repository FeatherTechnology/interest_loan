<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$qry = $pdo->query("SELECT cc.aadhar_number	, cc.cus_id,  cc.first_name, cc.last_name, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname, cc.mobile1, cc.pic, le.id, le.loan_id, lc.loan_category, le.loan_amount, le.benefit_method, le.due_method, le.due_period, le.interest_calculate, le.due_calculate, le.interest_rate_calc, le.due_period_calc, le.doc_charge_calc, le.processing_fees_calc, le.loan_amnt_calc, le.doc_charge_calculate, le.processing_fees_calculate, le.net_cash_calc, le.interest_amnt_calc, le.loan_date, le.due_startdate_calc , le.maturity_date_calc , le.cus_data
FROM loan_entry le 
JOIN customer_creation cc ON le.cus_id = cc.cus_id 
JOIN area_name_creation anc ON cc.area = anc.id 
LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.id
LEFT JOIN loan_category lc ON lcc.loan_category = lc.id
WHERE le.id ='$id' ");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null; //Close connection.

echo json_encode($result);
