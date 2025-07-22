<?php
require '../../ajaxconfig.php';

$aadhar_number = $_POST['aadhar_number'];
$result = array();

$qry = $pdo->query("SELECT cc.*, cs.cus_id AS existing_cus_id 
    FROM customer_creation cc 
    LEFT JOIN customer_status cs ON cc.cus_id = cs.cus_id AND cs.status >= 7 
    WHERE cc.aadhar_number = '$aadhar_number' 
    ORDER BY cc.id DESC 
    LIMIT 1");

if ($qry->rowCount() > 0) {
    $row = $qry->fetch(PDO::FETCH_ASSOC); // Fetch single row

    $row['cus_data'] = $row['existing_cus_id'] ? 'Existing' : 'New'; // Safe check
    $result[] = $row; // Add to result array for JSON encoding
}

$pdo = null; // Close connection

echo json_encode($result);
?>