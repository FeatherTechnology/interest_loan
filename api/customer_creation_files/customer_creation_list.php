<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$customer_data_arr = [1 => 'New', 2 => 'Existing'];
$column = array(
    'cc.id',
    'cc.cus_id',
    'cus_name',
    'cc.aadhar_number',
    'cc.mobile1',
    'anc.areaname',
    'lnc.linename',
    'cc.id'
);

$query = "SELECT cc.id, cc.cus_id, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, cc.aadhar_number, cc.mobile1, anc.areaname , lnc.linename  FROM customer_creation cc  LEFT JOIN line_name_creation lnc ON cc.line = lnc.id LEFT JOIN area_name_creation anc ON cc.area = anc.id 
LEFT JOIN area_creation ac ON cc.line = ac.line_id  WHERE 1";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " AND (cc.cus_id LIKE '" . $search . "%'
                    OR CONCAT(cc.first_name, ' ', cc.last_name)LIKE '%" . $search . "%'
                    OR cc.aadhar_number LIKE '%" . $search . "%'
                    OR cc.mobile1 LIKE '%" . $search . "%'
                    OR anc.areaname LIKE '%" . $search . "%')";
    }
}

// Ordering functionality
if (isset($_POST['order'])) {
    $columnIndex = $_POST['order'][0]['column'];  // Index of the column to be sorted
    $sortDirection = $_POST['order'][0]['dir'];  // Sort direction (asc/desc)

    if (isset($column[$columnIndex])) {
        // Apply sorting using the column and direction provided
        $query .= " ORDER BY " . $column[$columnIndex] . " " . $sortDirection;
    }
} else {
    // Default sorting (if no sorting is applied from frontend)
    $query .= ' ORDER BY cc.id DESC';
}

// Pagination (length and start from DataTables)
$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

$statement = $pdo->prepare($query);

// echo $query; die;
$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $pdo->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();
$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];
foreach ($result as $row) {
    $sub_array = array();

    $sub_array[] = $sno++;
    $sub_array[] = isset($row['cus_id']) ? $row['cus_id'] : '';
    $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    $sub_array[] = isset($row['aadhar_number']) ? $row['aadhar_number'] : '';
    $sub_array[] = isset($row['mobile1']) ? $row['mobile1'] : '';
    $sub_array[] = isset($row['areaname']) ? $row['areaname'] : '';
    $sub_array[] = isset($row['linename']) ? $row['linename'] : '';
    $action = "<span class='icon-border_color customerActionBtn' value='" . $row['id'] . "' cus_id='" . $row['cus_id'] . "'></span>&nbsp;&nbsp;&nbsp;";
    $action .= "<span class='icon-delete customerDeleteBtn' value='" . $row['id'] . "' cus_id='" . $row['cus_id'] . "'></span>";
    $sub_array[] = $action;
    $data[] = $sub_array;
}

function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM customer_creation";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchColumn();
}

$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

$pdo = null; // Close Connection

echo json_encode($output);
