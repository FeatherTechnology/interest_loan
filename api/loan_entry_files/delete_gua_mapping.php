<?php
require '../../ajaxconfig.php'; // DB connection

if (isset($_POST['loan_entry_id']) && isset($_POST['family_info_id'])) {
    $loan_entry_id = $_POST['loan_entry_id'];
    $family_info_id = $_POST['family_info_id'];

    // Delete based on both keys
    $deleteQry = $pdo->prepare("DELETE FROM guarantor_info WHERE loan_entry_id = ? AND family_info_id = ?");
    $deleteQry->execute([$loan_entry_id, $family_info_id]);

    if ($deleteQry->rowCount() > 0) {
        echo json_encode(1); // Success
    } else {
        echo json_encode(0); // Not found / not deleted
    }
} else {
    echo json_encode(0); // Invalid input
}

$pdo = null; // Close the connection
