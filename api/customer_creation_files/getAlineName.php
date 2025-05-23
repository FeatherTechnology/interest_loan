<?php
require '../../ajaxconfig.php'; // Adjust the path based on your actual configuration file

$response = array();
$areaId = $_POST['aline_id'];

$qry = $pdo->query("SELECT ac.line_id, lnc.linename FROM `area_creation` ac 
    LEFT JOIN line_name_creation lnc ON ac.line_id = lnc.id LEFT JOIN area_creation_area_name acan ON ac.id = acan.area_creation_id WHERE area_id = $areaId");

if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; // Close the connection
echo json_encode($response);
