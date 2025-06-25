<?php
include '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';

@session_start();
$user_id = $_SESSION['user_id'];

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$sub_status = ['' => '', 1 => 'Consider', 2 => 'Reject'];

$column = array(
    'cs.id',
    'lnc.linename',
    'le.loan_id',
    'li.issue_date',
    'cc.cus_id',
    'cc.aadhar_number',
    'cc.first_name',
    'anc.areaname',
    'bc.branch_name',
    'cc.mobile1',
    'lc.loan_category',
    'le.loan_amount',
    'le.maturity_date_calc', 
    'cs.closed_date', 
    'cs.status',
    'cs.sub_status'
);


$query = "SELECT cs.id, lnc.linename, le.loan_id, li.issue_date, cc.cus_id, cc.aadhar_number, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname, bc.branch_name , cc.mobile1, lc.loan_category, le.loan_amount, le.maturity_date_calc, cs.closed_date, cs.status, cs.sub_status 
FROM customer_status cs
JOIN loan_issue li ON cs.loan_entry_id = li.loan_entry_id 
JOIN loan_entry le ON cs.loan_entry_id = le.id
JOIN customer_creation cc ON le.cus_id = cc.cus_id
JOIN line_name_creation lnc ON cc.line = lnc.id
JOIN area_name_creation anc ON cc.area = anc.id
JOIN area_creation ac ON cc.line = ac.line_id
JOIN branch_creation bc ON ac.branch_id = bc.id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
JOIN users u ON FIND_IN_SET(cc.line, u.line)
JOIN users us ON FIND_IN_SET(le.loan_category, us.loan_category)
WHERE cs.status > 10 AND u.id ='$user_id' AND us.id ='$user_id' AND li.balance_amount = 0 AND cs.closed_date BETWEEN '$from_date' AND '$to_date'";


if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (anc.areaname LIKE '%" . $_POST['search'] . "%' OR
            le.loan_id LIKE '%" . $_POST['search'] . "%' OR
            li.issue_date LIKE '%" . $_POST['search'] . "%' OR
            cc.cus_id LIKE '%" . $_POST['search'] . "%' OR
            cc.aadhar_number LIKE '%" . $_POST['search']. "%' OR
            cc.first_name LIKE '%" . $_POST['search'] . "%' OR
            lnc.linename LIKE '%" . $_POST['search'] . "%' OR
            lc.loan_category LIKE '%" . $_POST['search'] . "%' OR
            le.maturity_date_calc LIKE '%" . $_POST['search'] . "%' OR
            cs.closed_date LIKE '%" . $_POST['search'] . "%' OR
            bc.branch_name LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $pdo->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $pdo->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['linename'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['issue_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['aadhar_number'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['areaname'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $row['loan_category'];
    $sub_array[] = moneyFormatIndia($row['loan_amount']);
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_date_calc']));
    $sub_array[] = date('d-m-Y', strtotime($row['closed_date']));
    $sub_array[] = $sub_status[$row['sub_status']];

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($pdo)
{
    $query = "SELECT id FROM customer_status where status > 8 ";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
$pdo = null; // Close Connection