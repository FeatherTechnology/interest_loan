<?php
require "../../ajaxconfig.php";
$cus_id = $_POST['cus_id'];
$row = '';

$qry = $pdo->query("SELECT MAX(id) as id FROM customer_creation WHERE cus_id = '$cus_id' ");

if ($qry->rowCount() > 0) {
    $row = $qry->fetchObject();
}

$result = array();

$qry2 = $pdo->query("SELECT cc.cus_id, cc.aadhar_number , cc.first_name, cc.last_name, anc.areaname , lnc.linename, bc.branch_name, cc.mobile1, cc.pic
FROM customer_creation cc 
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
LEFT JOIN area_name_creation anc ON cc.area = anc.id
LEFT JOIN area_creation ac ON cc.line = ac.line_id
LEFT JOIN branch_creation bc ON ac.branch_id = bc.id

WHERE cc.id = '$row->id' ");
if ($qry2->rowCount() > 0) {
    $result = $qry2->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; // Close Connection
echo json_encode($result);
