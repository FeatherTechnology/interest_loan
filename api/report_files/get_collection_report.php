<?php
include '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';

@session_start();
$user_id = $_SESSION['user_id'];

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$status = [1 => 'customer profile', 2 => 'Loan Calculation', 3 => 'In Approval', 4 => 'In Loan Issue', 5 => 'Cancel', 6 => 'Revoke', 7 => 'Current', 8 => 'Cancel', 9 => 'Revoke', 10 => 'In Closed', 11 => 'Closed', 12 => 'In NOC', 13 => 'NOC Completed', 14 => 'NOC Removed'];
$sub_status = ['' => '', 1 => 'Consider', 2 => 'Reject'];

$column = array(
    'c.id',
    'lnc.linename',
    'le.loan_id',
    'li.issue_date',
    'c.cus_id',
    'cc.aadhar_number',
    'cc.first_name',
    'anc.areaname',
    'bc.branch_name',
    'cc.mobile1',
    'lc.loan_category',
    'agc.agent_name',
    'r.role',
    'u.name',
    'c.collection_date',
    'SUM(c.principal_amount_track)',
    'SUM(c.interest_amount_track)',
    'SUM(c.penalty_track)',
    'SUM(c.fine_charge_track)',
    'SUM(c.total_paid_track)',
    'cs.status',
    'cs.sub_status'
);

$query = "SELECT c.id, lnc.linename, le.loan_id, li.issue_date, c.cus_id, cc.aadhar_number, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname, bc.branch_name , cc.mobile1, lc.loan_category, agc.agent_name, r.role, u.name,  c.collection_date, SUM(c.principal_amount_track) as principal_amount_track, SUM(c.interest_amount_track) as interest_amount_track, SUM(c.penalty_track) as penalty_track, SUM(c.fine_charge_track) as fine_charge_track, SUM(c.total_paid_track) as total_paid_track, cs.status, cs.sub_status 
FROM collection c
JOIN loan_issue li ON c.loan_entry_id = li.loan_entry_id 
JOIN loan_entry le ON c.loan_entry_id = le.id
JOIN customer_creation cc ON le.cus_id = cc.cus_id
JOIN line_name_creation lnc ON cc.line = lnc.id
JOIN area_name_creation anc ON cc.area = anc.id
JOIN area_creation ac ON cc.line = ac.line_id
JOIN branch_creation bc ON ac.branch_id = bc.id
JOIN loan_category_creation lcc ON le.loan_category = lcc.id
JOIN loan_category lc ON lcc.loan_category = lc.id
LEFT JOIN agent_creation agc ON le.agent_id_calc = agc.id
JOIN customer_status cs ON le.id = cs.loan_entry_id
JOIN users u ON FIND_IN_SET(cc.line, u.line)
LEFT JOIN role r ON u.role = r.id
JOIN users us ON FIND_IN_SET(le.loan_category, us.loan_category)
WHERE li.balance_amount = 0 AND u.id ='$user_id' AND us.id ='$user_id' AND DATE(c.collection_date) BETWEEN '$from_date' AND '$to_date' ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (le.loan_id LIKE '%" . $_POST['search'] . "%'
                    OR li.issue_date LIKE '%" . $_POST['search'] . "%'
                    OR c.cus_id LIKE '%" . $_POST['search'] . "%'
                    OR cc.aadhar_number LIKE '%" . $_POST['search'] . "%'
                    OR cc.first_name LIKE '%" . $_POST['search'] . "%'
                    OR lnc.linename LIKE '%" . $_POST['search'] . "%'
                    OR anc.areaname LIKE '%" . $_POST['search'] . "%'
                    OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
                    OR r.role LIKE '%" . $_POST['search'] . "%'
                    OR u.name LIKE '%" . $_POST['search'] . "%'
                    OR c.collection_date LIKE '%" . $_POST['search'] . "%') ";
    }
}

$query .= " GROUP BY le.loan_id ";

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
    $sub_array[] = $row['agent_name'];
    $sub_array[] = $row['role'];
    $sub_array[] = $row['name'];
    $sub_array[] = date('d-m-Y', strtotime($row['collection_date']));
    $sub_array[] = moneyFormatIndia(intval($row['principal_amount_track']));
    $sub_array[] = moneyFormatIndia(intval($row['interest_amount_track']));
    $sub_array[] = moneyFormatIndia(intval($row['penalty_track']));
    $sub_array[] = moneyFormatIndia(intval($row['fine_charge_track']));
    $sub_array[] = moneyFormatIndia(intval($row['total_paid_track']));

    if ($row['status'] >= '10') {
        $sub_array[] = 'Closed';

        if ($row['sub_status'] != '') {
            $sub_array[] = $sub_status[$row['sub_status']];
        } else {
            $sub_array[] = $status[$row['status']];
        }
    } else {
        $sub_array[] = 'Present';
        $sub_array[] = $status[$row['status']];
    }

    $data[] = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($pdo)
{
    $query = "SELECT id FROM collection c  GROUP BY c.loan_entry_id ";
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

