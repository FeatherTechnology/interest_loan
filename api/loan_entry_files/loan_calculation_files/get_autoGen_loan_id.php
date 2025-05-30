<?php
require "../../../ajaxconfig.php";

$qry = $pdo->query("SELECT loan_id FROM loan_entry WHERE loan_id !='' ORDER BY id DESC ");
if ($qry->rowCount() > 0) {
    $qry_info = $qry->fetch(); //LID-101
    $l_no = ltrim(strstr($qry_info['loan_id'], '-'), '-');
    $l_no = $l_no + 1;
    $loan_ID_final = "LID-" . "$l_no";
} else {
    $loan_ID_final = "LID-" . "101";
}

$pdo = null; //Connection Close.
echo json_encode($loan_ID_final);
