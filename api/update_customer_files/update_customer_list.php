<?php
require '../../ajaxconfig.php';

$update_cus_list_arr = array();
$column = array(
    'cc.id',
    'cc.cus_id',
    'cc.aadhar_number',
    'cc.first_name',
    'anc.areaname',
    'lnc.linename',
    'bc.branch_name',
    'cc.mobile1',
    'cc.id'
);

$query = "SELECT cc.id, cc.aadhar_number , cc.cus_id, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname , lnc.linename, bc.branch_name , cc.mobile1
FROM customer_creation cc 
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
LEFT JOIN area_name_creation anc ON cc.area = anc.id
LEFT JOIN area_creation ac ON cc.line = ac.line_id
LEFT JOIN branch_creation bc ON ac.branch_id = bc.id ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " AND (cc.cus_id LIKE '" . $search . "%'
                    OR cc.aadhar_number LIKE '%" . $search . "%'
                    OR CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) LIKE '%" . $search . "%'
                    OR anc.areaname LIKE '%" . $search . "%'
                    OR lnc.linename LIKE '%" . $search . "%'
                    OR bc.branch_name LIKE '%" . $search . "%'
                    OR cc.mobile1 LIKE '%" . $search . "%')";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

$statement = $pdo->prepare($query);

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
    $sub_array[] = isset($row['aadhar_number']) ? $row['aadhar_number'] : '';
    $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    $sub_array[] = isset($row['areaname']) ? $row['areaname'] : '';
    $sub_array[] = isset($row['linename']) ? $row['linename'] : '';
    $sub_array[] = isset($row['branch_name']) ? $row['branch_name'] : '';
    $sub_array[] = isset($row['mobile1']) ? $row['mobile1'] : '';

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";
    $action .= "<a href='#' class='edit-cus-update' value='" . $row['id'] . "' title='Edit details'>Edit</a>";
    $action .= "</div></div>";
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

$pdo = null; //Connection Close.
echo json_encode($output);
