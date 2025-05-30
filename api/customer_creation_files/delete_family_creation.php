<?php
require "../../ajaxconfig.php";

$id = $_POST['id'];
try {
    // First, check if this family_info.id is used in guarantor_info
    $checkQry = $pdo->prepare("SELECT family_info_id FROM `guarantor_info` WHERE `family_info_id` = :id");
    $checkQry->bindParam(':id', $id, PDO::PARAM_INT);
    $checkQry->execute();
    $count = $checkQry->fetchColumn();

    if ($count > 0) {
        $result = 3; // In use, do not delete
    } else {
        // Proceed with deletion
        $qry = $pdo->prepare("DELETE FROM `family_info` WHERE `id` = :id");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        if ($qry->execute()) {
            $result = 1; // Deleted
        } else {
            throw new Exception();
        }
    }
} catch (Exception $e) {
    $result = 2; // General error
}


$pdo = null; // Close Connection
echo json_encode($result);
