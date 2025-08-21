<?php
require "../../ajaxconfig.php";
@session_start();

$response = [];

$guarantor_name = $_POST['guarantor_name'];

if (!empty($_FILES['gu_pic']['name'])) { 
    $path = "../../uploads/loan_entry/gu_pic/";

    $picture = $_FILES['gu_pic']['name']; 
    $pic_temp = $_FILES['gu_pic']['tmp_name']; 
    $fileExtension = pathinfo($picture, PATHINFO_EXTENSION);

    // Generate unique filename
    $picture = uniqid() . '.' . $fileExtension;
    while (file_exists($path . $picture)) {
        $picture = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($pic_temp, $path . $picture);

    $response['gu_pic'] = $picture; // return file name
} else {
    $picture = $_POST['gur_pic'] ?? '';
    $response['gu_pic'] = $picture;
}

// Basic sanitization (ensure it's an integer if it's an ID)
$family_info_id = intval($guarantor_name); // assuming it's an ID

$sql = "SELECT id , fam_name, fam_relationship, relation_type, fam_aadhar, fam_mobile FROM family_info  WHERE id = $family_info_id";
$qry = $pdo->query($sql);

if ($qry->rowCount() > 0) {
    $response['result'] = 1;
    $response['customer_data'] = $qry->fetch(PDO::FETCH_ASSOC);
} else {
    $response['result'] = 0;
    $response['message'] = 'Guarantor not found';
}


$pdo = null; //Connection Close.
echo json_encode($response);
?>
