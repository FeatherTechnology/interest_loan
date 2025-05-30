<?php
require "../../ajaxconfig.php";
@session_start();

$response = [];

$guarantor_name = $_POST['guarantor_name'];

// Basic sanitization (ensure it's an integer if it's an ID)
$family_info_id = intval($guarantor_name); // assuming it's an ID

$sql = "SELECT id , fam_name, fam_relationship, relation_type, fam_aadhar, fam_mobile FROM family_info  WHERE id = $family_info_id";
$qry = $pdo->query($sql);

if ($qry->rowCount() > 0) {
    $response['result'] = 1;
    $response['customer_data'] = $qry->fetch(PDO::FETCH_ASSOC);
} else {
    $response['result'] = 0;
    $response['message'] = 'Guarantor not found';
}


$pdo = null; //Connection Close.
echo json_encode($response);
?>
