<?php
require "../../ajaxconfig.php";

$le_id = $_POST['le_id'];

$result = array();

$qry = $pdo->query("SELECT * FROM fine_charges WHERE loan_entry_id = '$le_id' AND fine_date != '' ");

if ($qry->rowCount() > 0) {
    while($fineInfo =  $qry->fetch(PDO::FETCH_ASSOC)){
        $fineInfo['fine_date'] = date('d-m-Y', strtotime($fineInfo['fine_date']));
        $result[] = $fineInfo;
    }
}

$pdo = null; //Close Connection.
echo json_encode($result);
