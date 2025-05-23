<?php
require '../../ajaxconfig.php';

$response = [];

$qry = $pdo->prepare(" SELECT anc.id, anc.areaname FROM area_creation ac LEFT JOIN area_creation_area_name acan ON ac.id = acan.area_creation_id
LEFT JOIN area_name_creation anc ON acan.area_id = anc.id WHERE ac.status = 1");

$qry->execute();
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null;

// Output the response as JSON
echo json_encode($response);
