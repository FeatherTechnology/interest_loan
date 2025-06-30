<?php
require "../../ajaxconfig.php";
require_once '../../include/views/money_format_india.php';

@session_start();
$user_id = $_SESSION['user_id'];

$from_date = $_POST['params']['from_date'];
$to_date = $_POST['params']['to_date'];

$column = array(
    'li.id',
    'le.loan_id',
    'cc.cus_id',
    'cc.aadhar_number',
    'cc.first_name',
    'gaurantor',
    'anc.areaname',
    'lnc.linename',
    'bc.branch_name',
    'cc.mobile1',
    'lc.loan_category',
    'agc.agent_name',
    'li.issue_date',
    'le.loan_amount',
    'le.loan_amount',
    'le.interest_amnt_calc',
    'le.doc_charge_calculate',
    'le.processing_fees_calculate',
    'li.net_cash',
    'li.issue_person',
    'li.relationship'
);

$query = "SELECT le.loan_id, cc.cus_id, cc.aadhar_number, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name,  GROUP_CONCAT(DISTINCT fi.fam_name SEPARATOR ' , ') AS gaurantor, anc.areaname, lnc.linename, bc.branch_name , cc.mobile1, lc.loan_category, agc.agent_name, li.issue_date, le.loan_amount, le.interest_amnt_calc, le.doc_charge_calculate, le.processing_fees_calculate, li.net_cash, li.issue_person, li.relationship 
FROM loan_issue li 
JOIN customer_creation cc ON li.cus_id = cc.cus_id
JOIN loan_entry le ON li.loan_entry_id = le.id
LEFT JOIN guarantor_info gi ON gi.loan_entry_id = le.id
LEFT JOIN family_info fi ON fi.id = gi.family_info_id   
JOIN line_name_creation lnc ON cc.line = lnc.id
JOIN area_name_creation anc ON cc.area = anc.id
JOIN area_creation ac ON cc.line = ac.line_id
JOIN branch_creation bc ON ac.branch_id = bc.id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
LEFT JOIN agent_creation agc ON le.agent_id_calc = agc.id
JOIN users u ON FIND_IN_SET(cc.line, u.line)
JOIN users us ON FIND_IN_SET(le.loan_category, us.loan_category)
WHERE u.id ='$user_id' AND us.id ='$user_id' AND li.issue_date BETWEEN '$from_date' AND '$to_date' ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .=     " AND (le.loan_id LIKE '%" . $search . "%'
        OR cc.cus_id LIKE '%" . $search . "%'
        OR cc.aadhar_number LIKE '%" . $search . "%'
        OR cc.first_name LIKE '%" . $search . "%'
        OR fi.fam_name LIKE '%" . $search . "%'
        OR anc.areaname LIKE '%" . $search . "%'
        OR lnc.linename LIKE '%" . $search . "%'
        OR bc.branch_name LIKE '%" . $search . "%'
        OR cc.mobile1 LIKE '%" . $search . "%' 
        OR lc.loan_category LIKE '%" . $search . "%' 
        OR agc.agent_name LIKE '%" . $search . "%' 
        OR li.issue_date LIKE '%" . $search . "%' 
        OR le.loan_amount LIKE '%" . $search . "%' 
        OR le.loan_amount LIKE '%" . $search . "%' 
        OR le.interest_amnt_calc LIKE '%" . $search . "%' 
        OR le.doc_charge_calculate LIKE '%" . $search . "%' 
        OR le.processing_fees_calculate LIKE '%" . $search . "%' 
        OR li.net_cash LIKE '%" . $search . "%' 
        OR li.issue_person LIKE '%" . $search . "%' 
        OR li.relationship LIKE '%" . $search . "%' )";
    }
}

$query .= 'GROUP BY li.loan_entry_id';

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
    $sub_array[] = isset($row['loan_id']) ? $row['loan_id'] : '';
    $sub_array[] = isset($row['cus_id']) ? $row['cus_id'] : '';
    $sub_array[] = isset($row['aadhar_number']) ? $row['aadhar_number'] : ''; 
    $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    $sub_array[] = isset($row['gaurantor']) ? $row['gaurantor'] : '';
    $sub_array[] = isset($row['areaname']) ? $row['areaname'] : '';
    $sub_array[] = isset($row['linename']) ? $row['linename'] : '';
    $sub_array[] = isset($row['branch_name']) ? $row['branch_name'] : '';
    $sub_array[] = isset($row['mobile1']) ? $row['mobile1'] : '';
    $sub_array[] = isset($row['loan_category']) ? $row['loan_category'] : '';
    $sub_array[] = isset($row['agent_name']) ? $row['agent_name'] : '';
    $sub_array[] = isset($row['issue_date']) ? date('d-m-Y', strtotime($row['issue_date'])) : '';
    $sub_array[] = isset($row['loan_amount']) ? moneyFormatIndia($row['loan_amount']) : '';
    $sub_array[] = isset($row['loan_amount']) ? moneyFormatIndia($row['loan_amount']) : '';
    $sub_array[] = isset($row['interest_amnt_calc']) ? moneyFormatIndia($row['interest_amnt_calc']) : '';
    $sub_array[] = isset($row['doc_charge_calculate']) ? moneyFormatIndia($row['doc_charge_calculate']) : '';
    $sub_array[] = isset($row['processing_fees_calculate']) ? moneyFormatIndia($row['processing_fees_calculate']) : '';
    $sub_array[] = isset($row['net_cash']) ? moneyFormatIndia($row['net_cash']) : '';
    $sub_array[] = isset($row['issue_person']) ? $row['issue_person'] : '';
    $sub_array[] = isset($row['relationship']) ? $row['relationship'] : '';

    $data[] = $sub_array;
}

function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM loan_issue";
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

