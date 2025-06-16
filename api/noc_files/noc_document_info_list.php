<?php
require "../../ajaxconfig.php";

$endorsement_info_arr = array();
$le_id = $_POST['le_id'];

$qry = $pdo->query("SELECT di.`id`,di.`doc_name`,di.`doc_type`,CASE 
            WHEN di.holder_name = 0 THEN CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) 
            ELSE fi.fam_name 
        END as holder_name,di.`upload`,di.`date_of_noc`, di.`noc_member`, di.`noc_relationship`, di.`noc_status` FROM `document_info` di 
        LEFT JOIN `family_info` fi ON di.holder_name = fi.id 
        LEFT JOIN customer_creation cc ON di.cus_id = cc.cus_id WHERE di.`cus_profile_id` = '$le_id'");

if ($qry->rowCount() > 0) {
    while ($result = $qry->fetch()) {
        $result['d_noc'] = '';
        $result['h_person'] = '';
        $result['relation'] = '';
        $result['upload'] = "<a href='uploads/loan_issue/doc_info/" . $result['upload'] . "' target='_blank'>" . $result['upload'] . "</a>";
        $result['action'] = "<input type='checkbox' class='noc_doc_info_chkbx' name='noc_doc_info_chkbx' value='" . $result['id'] . "' data-id='" . $result['noc_status'] . "'>";
        $endorsement_info_arr[] = $result;
    }
}

$pdo = null; //Close Connection.
echo json_encode($endorsement_info_arr);
