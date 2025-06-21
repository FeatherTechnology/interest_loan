<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$loan_entry_id = isset($_POST['loan_entry_id']) ? $_POST['loan_entry_id'] : '';
$repromotion_detail = isset($_POST['repro_data']) ? $_POST['repro_data'] : '';

$result = 0;

$qry = $pdo->query("SELECT le.id , cc.cus_id, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name, anc.areaname AS area, lnc.linename, bc.branch_name, cc.mobile1, cs.status as c_sts FROM customer_creation cc 
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id 
LEFT JOIN area_name_creation anc ON cc.area = anc.id 
LEFT JOIN area_creation ac ON cc.line = ac.line_id 
LEFT JOIN branch_creation bc ON ac.branch_id = bc.id 
LEFT JOIN loan_entry le ON le.cus_id = cc.cus_id 
LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id  
WHERE cs.loan_entry_id='$loan_entry_id' ORDER BY cc.id DESC LIMIT 1");

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $cus_id = $row['cus_id'];
        $cus_name = $row['cus_name'];
        $loan_entry_id = $row['id'];
        $area = $row['area'];
        $mobile1 = $row['mobile1'];
        $linename = $row['linename'];
        $branch_name = $row['branch_name'];
        $c_sts = $row['c_sts'];

        $qry1 = $pdo->query("INSERT INTO `repromotion_customer`(`cus_id`, `loan_entry_id`, `cus_name`, `mobile1`, `area`, `linename`,`branch_name`, `c_sts`,`repromotion_detail`, `insert_login_id`, `created_on` ) VALUES ('$cus_id', '$loan_entry_id', '$cus_name','$mobile1','$area','linename','$branch_name','$c_sts','$repromotion_detail','$user_id',now())");

        $result = 1; // Insert successful
    }
}

$pdo = null; // Close Connection
echo json_encode($result);
