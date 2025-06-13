<?php
require "../../ajaxconfig.php";

$le_id = $_POST['le_id'];
$response = []; // Define response array

if (isset($_POST['sub_status']) && (!empty($_POST['sub_status']))) {
    $sub_status = $_POST['sub_status'];
    // Run the update query
    $qry = $pdo->query("UPDATE `customer_status` SET `collection_status` = '$sub_status', `updated_on` = CURRENT_TIMESTAMP WHERE loan_entry_id = '$le_id' ");
}

// Check if the update was successful
if ($qry) {
    $response['status'] = 'success';
    $response['message'] = 'Customer status updated successfully';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to update customer status';
}

$pdo = null; // Close Connection
echo json_encode($response);
