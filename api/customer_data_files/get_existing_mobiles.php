<?php
require '../../ajaxconfig.php';

$mobile = $_POST['mobile'];

$stmt = $pdo->prepare('SELECT cc.mobile1, cc.mobile2, cs.status FROM customer_creation cc 
JOIN customer_status cs ON cs.cus_id = cc.cus_id WHERE cc.mobile1 = ? OR cc.mobile2 = ?');

$stmt->execute([$mobile, $mobile]);
$response = $stmt->fetch();

if ($response) {
    echo json_encode(['exists' => true, 'status' => $response['status']]);
} else {
    echo json_encode(['exists' => false]);
}

// Close the connection
$pdo = null;
?>

