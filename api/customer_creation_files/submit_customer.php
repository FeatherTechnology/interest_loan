<?php
require '../../ajaxconfig.php';
@session_start();

if (!empty($_FILES['pic']['name'])) {
    $path = "../../uploads/customer_creation/cus_pic/";
    $picture = $_FILES['pic']['name'];
    $pic_temp = $_FILES['pic']['tmp_name'];
    $picfolder = $path . $picture;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION); //get the file extention
    $picture = uniqid() . '.' . $fileExtension;
    while (file_exists($path . $picture)) {
        //this loop will continue until it generates a unique file name
        $picture = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($pic_temp, $path . $picture);
} else {
    $picture = $_POST['per_pic'];
}

$cus_id = $_POST['cus_id'];
$aadhar_number = $_POST['aadhar_number'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$dob = $_POST['dob'];
$age = $_POST['age'];
$area = $_POST['area'];
$line = $_POST['line'];
$customer_data = 1;
$mobile1 = $_POST['mobile1'];
$mobile2 = $_POST['mobile2'];
$whatsapp = $_POST['whatsapp'];
$occupation = $_POST['occupation'];
$occ_detail = $_POST['occ_detail'];
$address = $_POST['address'];
$native_address = $_POST['native_address'];
$cus_limit = $_POST['cus_limit'];
$about_cus = $_POST['about_cus'];
$customer_profile_id = $_POST['customer_profile_id'];
$user_id = $_SESSION['user_id'];

$result = 0;

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Check if customer exists based on Aadhar number
    $check_query = $pdo->query("SELECT * FROM customer_creation WHERE aadhar_number = '$aadhar_number'");

    // Get the latest customer ID
    $selectIC = $pdo->query("SELECT cus_id FROM customer_creation WHERE cus_id != '' ORDER BY id DESC LIMIT 1 FOR UPDATE");

    if ($check_query->rowCount() > 0) {
        $row = $check_query->fetch();
        $customer_profile_id = $row['id']; // Get the existing customer's ID
        $qry = $pdo->query("UPDATE `customer_creation` SET `cus_id`='$cus_id', `aadhar_number`='$aadhar_number', `first_name`='$first_name', `last_name`='$last_name', `dob`='$dob',`age`='$age',`area`='$area', `line`='$line', `customer_data`='$customer_data', `mobile1`='$mobile1', `mobile2`='$mobile2', `whatsapp`='$whatsapp', `occupation`='$occupation',`occ_detail`='$occ_detail',`address`='$address', `native_address`='$native_address', `cus_limit`='$cus_limit', `about_cus`='$about_cus', `pic`='$picture', `update_login_id`='$user_id', updated_on = now() WHERE `id`='$customer_profile_id'");

        if ($qry) {
            $result = 1; // Update successfull
        }
    } else {
        if ($selectIC->rowCount() > 0) {
            $row = $selectIC->fetch();
            $ac2 = $row["cus_id"];
            $appno2 = ltrim(strstr($ac2, '-'), '-'); // Extract numeric part after the hyphen
            $appno2 = $appno2 + 1;
            $cus_id = "C-" . $appno2;
        } else {
            $cus_id = "C-101"; // If no previous customer ID exists, start with C-1
        }

        $qry = $pdo->query("INSERT INTO `customer_creation`(`cus_id`,`aadhar_number`, `first_name`, `last_name`,`dob`,`age`,`area`, `line`, `customer_data`, `mobile1`, `mobile2`, `whatsapp`,`occupation`,`occ_detail`, `address`, `native_address`, `cus_limit`, `about_cus`, `pic`, `insert_login_id`, `created_on`) VALUES ('$cus_id','$aadhar_number','$first_name', '$last_name','$dob','$age','$area', '$line', '$customer_data', '$mobile1', '$mobile2', '$whatsapp','$occupation','$occ_detail', '$address', '$native_address', '$cus_limit', '$about_cus', '$picture','$user_id', now())");

        if ($qry) {
            $result = 2; // Insert successfull
        }
    }
    // Commit transaction
    $pdo->commit();
} catch (Exception $e) {
    // Rollback the transaction on error
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
    exit;
}

$pdo = null; // Close Connection

echo json_encode($result);
