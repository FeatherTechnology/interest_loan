<?php
require "../../ajaxconfig.php";
$result = array();

$type = $_POST['type'];
$id = $_POST['id'];

if ($type == '1' || $type == '2') {
    $cndtn = "le.id = '$id'";
    $joncndtn = "fi.id = gi.family_info_id";
} else {
    $cndtn = "fi.id = '$id'";
    $joncndtn = "cc.cus_id = fi.cus_id";
}

$qry = $pdo->query("SELECT CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, fi.id, fi.fam_name, fi.fam_relationship FROM loan_entry le 
LEFT JOIN customer_creation cc ON cc.cus_id = le.cus_id
LEFT JOIN guarantor_info gi ON  gi.loan_entry_id = le.id
LEFT JOIN family_info fi ON $joncndtn  WHERE $cndtn ");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null;
echo json_encode($result);
