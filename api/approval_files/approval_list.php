<?php
require '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';
@session_start();
$user_id = $_SESSION['user_id'];

$column = array(
    'cc.id',
    'cc.cus_id',
    'cc.aadhar_number',
    'cc.first_name',
    'anc.areaname',
    'lnc.linename',
    'bc.branch_name',
    'lc.loan_category',
    'le.loan_amount',
    'cc.mobile1',
    'le.cus_data',
    'cc.id'
);

$query = "SELECT le.id AS loan_entry_id, cc.id , cc.cus_id ,  CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, cc.aadhar_number , lc.loan_category , le.loan_amount , le.cus_data , anc.areaname , lnc.linename , bc.branch_name , cc.mobile1, cs.id as cus_sts_id,  cs.status as c_sts
FROM customer_creation cc
LEFT JOIN loan_entry le ON cc.cus_id = le.cus_id
LEFT JOIN loan_category_creation lcc ON le.loan_category = lcc.id
LEFT JOIN loan_category lc ON lcc.loan_category = lc.id
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
LEFT JOIN area_name_creation anc ON cc.area = anc.id
LEFT JOIN area_creation ac ON cc.line = ac.line_id
LEFT JOIN branch_creation bc ON ac.branch_id = bc.id
LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id 
WHERE cc.insert_login_id = '$user_id' AND cs.status = 3 ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " AND (
            cc.cus_id LIKE '" . $search . "%'
            OR cc.aadhar_number LIKE '%" . $search . "%'
            OR CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) LIKE '%" . $search . "%'
            OR anc.areaname LIKE '%" . $search . "%'
            OR lnc.linename LIKE '%" . $search . "%'
            OR bc.branch_name LIKE '%" . $search . "%'
            OR lc.loan_category LIKE '%" . $search . "%'
            OR le.loan_amount LIKE '%" . $search . "%'
            OR cc.mobile1 LIKE '%" . $search . "%'
            OR le.cus_data LIKE '%" . $search . "%'
        )";
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
    $sub_array[] = isset($row['loan_category']) ? $row['loan_category'] : '';
    $sub_array[] = isset($row['loan_amount']) ? moneyFormatIndia($row['loan_amount']) : '';
    $sub_array[] = isset($row['cus_data']) ? $row['cus_data'] : '';
    $action = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                <div class='dropdown-content'>";
    if ($row['c_sts'] == '3') {

        $action .= "<a href='#' class='approval-edit' value='" . $row['loan_entry_id'] . "' title='Edit details'>Edit</a>";
        $action .= "<a href='#' class='approval-approve' value='" . $row['cus_sts_id'] . "' data-id='" . $row['loan_entry_id'] . "' title='Approve'>Approve</a>";
        $action .= "<a href='#' class='approval-cancel' value='" . $row['cus_sts_id'] . "' title='Cancel'>Cancel</a>";
        $action .= "<a href='#' class='approval-revoke' value='" . $row['cus_sts_id'] . "' title='Revoke'>Revoke</a>";
    }
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

$pdo = null; // Close Connection
echo json_encode($output);
