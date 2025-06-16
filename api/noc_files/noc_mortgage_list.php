<?php
require "../../ajaxconfig.php";

$cheque_info_arr = array();
$le_id = $_POST['le_id'];

$qry = $pdo->query("SELECT mi.`id`, CASE WHEN mi.property_holder_name = 0 THEN CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, ''))  
            ELSE fi.fam_name 
            END as holder_name, mi.`relationship`,mi.`property_details`,mi.`mortgage_name`,mi.`designation`,mi.`reg_office`,mi.`date_of_noc`, mi.`noc_member`, mi.`noc_relationship`,  mi.`noc_status` FROM `mortgage_info` mi 
            LEFT JOIN `family_info` fi ON mi.property_holder_name = fi.id 
            LEFT JOIN customer_creation cc ON mi.cus_id= cc.cus_id WHERE mi.`cus_profile_id` = '$le_id'");

if ($qry->rowCount() > 0) {
    while ($result = $qry->fetch()) {
        $result['action'] = "<input type='checkbox' class='noc_mortgage_chkbx' name='noc_mortgage_chkbx' value='" . $result['id'] . "' data-id='" . $result['noc_status'] . "' >";
        $cheque_info_arr[] = $result;
    }
}

$pdo = null; //Close Connection.
echo json_encode($cheque_info_arr);
