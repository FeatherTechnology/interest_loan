<?php
require "../../../ajaxconfig.php";

$qry = $pdo->query("SELECT MAX(loan_id) AS loan_id FROM loan_entry WHERE loan_id !=''");
$qry_info = $qry->fetch(PDO::FETCH_ASSOC); 

if (!empty($qry_info['loan_id'])) {
    // Extract the numeric part after "LID-"
    $l_no = ltrim(strstr($qry_info['loan_id'], '-'), '-');
    $l_no = (int)$l_no + 1;
    $loan_ID_final = "LID-" . $l_no;
} else {
    $loan_ID_final = "LID-101";
}

$pdo = null; //Connection Close.
echo json_encode($loan_ID_final);
