<?php
require '../../ajaxconfig.php';

$search_list_arr = array();

$cus_id = isset($_POST['cus_id']) ? $_POST['cus_id'] : '';
$aadhar_number = isset($_POST['aadhar_number']) ? $_POST['aadhar_number'] : '';
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$mobile1 = isset($_POST['mobile']) ? $_POST['mobile'] : '';

// Initialize the query with the common part
$sql = "SELECT cc.cus_id, cc.aadhar_number, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname AS area, lnc.linename, bc.branch_name, cc.mobile1
        FROM customer_creation cc 
        LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
        LEFT JOIN area_name_creation anc ON cc.area = anc.id
        LEFT JOIN area_creation ac ON cc.line = ac.line_id
        LEFT JOIN branch_creation bc ON ac.branch_id = bc.id
        WHERE 1 = 1 ";

// Create an array to hold the conditions
$conditions = [];
$parameters = [];

// Add conditions based on priority
if (!empty($cus_id)) {
    $conditions[] = "cc.cus_id LIKE :cus_id";
    $parameters[':cus_id'] = '%' . $cus_id . '%';
}

if (!empty($aadhar_number)) {
    $conditions[] = "cc.aadhar_number LIKE :aadhar_number";
    $parameters[':aadhar_number'] = '%' . $aadhar_number . '%';
}

if (!empty($first_name)) {
    $conditions[] = "cc.first_name LIKE :first_name";
    $parameters[':first_name'] = '%' . $first_name . '%';
}

if (!empty($mobile1)) {
    $conditions[] = "cc.mobile1 LIKE :mobile1";
    $parameters[':mobile1'] = '%' . $mobile1 . '%';
}

if (!empty($area)) {
    $conditions[] = "anc.areaname LIKE :cus_area";
    $parameters[':cus_area'] = '%' . $area . '%';
}

// Apply the conditions based on priority
if (count($conditions) > 0) {
    $sql .= " AND (" . implode(" OR ", $conditions) . ")";
}

$sql .= " ORDER BY cc.id DESC";

$stmt = $pdo->prepare($sql);


foreach ($parameters as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}

$stmt->execute();

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['action'] = "<div class='dropdown'>
            <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
            <div class='dropdown-content'>
                <a href='#' class='view_customer' value='" . $row['cus_id'] . "'>View</a>
            </div>
        </div>";
        $search_list_arr[] = $row;
    }
}

$pdo = null; // Close Connection

echo json_encode($search_list_arr);
