<?php
require '../../../ajaxconfig.php';

$id = $_POST['id'];
$response = ['result' => 0];

if (!empty($id)) {
    $qry = $pdo->prepare("SELECT * FROM loan_category_creation WHERE id = ? AND status = 1");
    $qry->execute([$id]);

    if ($qry->rowCount() > 0) {
        $row = $qry->fetch(PDO::FETCH_ASSOC);
        $response['result'] = 1;
        $response['data'] = $row;
    }
}

$pdo = null; //Connection Close.
echo json_encode($response);
