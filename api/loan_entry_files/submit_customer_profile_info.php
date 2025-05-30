<?php
require '../../ajaxconfig.php';
@session_start();

$cus_id = $_POST['cus_id'];
$aadhar_number = $_POST['aadhar_number'];
$user_id = $_SESSION['user_id'];
$loan_entry_id = $_POST['customer_profile_id'];

// Query to get customer profile along with customer status
$qry = $pdo->query("SELECT cs.status 
                    FROM loan_entry le
                    JOIN customer_status cs ON le.cus_id = cs.cus_id
                    WHERE le.aadhar_number = '$aadhar_number' 
                    AND le.id != '$loan_entry_id'");

if ($qry->rowCount() > 0) {
    $result = $qry->fetch();
    $status = $result['status'];  // Customer status from the customer_status table

    if ($status >= 1 && $status <= 6) {
        $cus_data = 'New';  // Since we found a matching record, it's considered 'Existing'
    } else {
        $cus_data = 'Existing';  // Since we found a matching record, it's considered 'Existing'
    }
} else {
    $cus_data = 'New';        // No matching record found, it's considered 'New'
}

$result = 0;

if ($loan_entry_id != '') {
    $qry = $pdo->query("UPDATE `loan_entry` SET  `aadhar_number`='$aadhar_number',`cus_id`='$cus_id', `cus_data`='$cus_data', `update_login_id`='$user_id',updated_on = now() WHERE `id`='$loan_entry_id'");

    if ($qry) {
        $result = 1; // Update successfull
    }
} else {
    $qry = $pdo->query("INSERT INTO `loan_entry` (`aadhar_number`, `cus_id`, `cus_data`, `insert_login_id`, `created_on` ) VALUES ('$aadhar_number', '$cus_id', '$cus_data','$user_id',CURRENT_TIMESTAMP())");

    if ($qry) {
        $result = 2; // Insert successfull
    }

    $loan_entry_id = $pdo->lastInsertId();

    $status = 0;
    $last_id = $pdo->lastInsertId();
    $qry = $pdo->query("INSERT INTO `customer_status`( `cus_id`, `loan_entry_id`, `status`, `insert_login_id`, `created_on`) VALUES ('$cus_id', '$last_id', '1', '$user_id',CURRENT_TIMESTAMP() )");
}

if (isset($_POST['guarantorMappingData'])) {
    $guarantorMappingData = json_decode($_POST['guarantorMappingData'], true);

    foreach ($guarantorMappingData as $index => $guarantor) {
        $family_info_id = $guarantor['guar_id'] ?? '';
        $existing_pic = $guarantor['gur_pic'] ?? '';
        $fileKey = 'gu_pic_' . $index;

        // Check if the guarantor already exists for this loan
        $checkSql = "SELECT id FROM guarantor_info WHERE loan_entry_id = '$loan_entry_id' AND family_info_id = '$family_info_id'";
        $checkResult = $pdo->query($checkSql);

        if ($checkResult->rowCount() > 0) {
            // Skip duplicate
            continue;
        }

        // Process uploaded file
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === 0) {
            $originalName = $_FILES[$fileKey]['name'];
            $tmpName = $_FILES[$fileKey]['tmp_name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $gpicture = uniqid() . '.' . $extension;
            move_uploaded_file($tmpName, "../../uploads/loan_entry/gu_pic/" . $gpicture);
        } else {
            $gpicture = $existing_pic;
        }

        // Insert new guarantor mapping
        $insertSql = "INSERT INTO guarantor_info (loan_entry_id, family_info_id, gu_pic) VALUES ('$loan_entry_id', '$family_info_id', '$gpicture')";
        $pdo->query($insertSql);
    }
}

$pdo = null; //Connection Close.
echo json_encode(['result' => $result, 'loan_entry_id' => $loan_entry_id]);
