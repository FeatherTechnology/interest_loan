<?php
require "../../../ajaxconfig.php";

$id = $_POST['id'];
$response = array();

$qry = $pdo->query("SELECT * FROM loan_entry WHERE id ='$id' ");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; //Connection Close.
echo json_encode($response);
