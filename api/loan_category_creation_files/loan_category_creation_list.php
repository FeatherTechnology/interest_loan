<?php
require '../../ajaxconfig.php';
require_once '../../include/views/money_format_india.php';

$loanCatCreation_list_arr = array();
$status_arr = [0 => 'Disable', 1 => 'Enable'];

$filterEnabled = isset($_POST['enable']) && $_POST['enable'];

$sql = "SELECT lcc.id, lc.loan_category, lcc.loan_limit, lcc.status 
        FROM loan_category_creation lcc 
        LEFT JOIN loan_category lc ON lcc.loan_category = lc.id";
        
if ($filterEnabled) {
    $sql .= " WHERE lcc.status = 1";  
}

$qry = $pdo->query($sql);

if ($qry->rowCount() > 0) {
    while ($loanCatCreationInfo = $qry->fetch(PDO::FETCH_ASSOC)) {
        $loanCatCreationInfo['status'] = $status_arr[$loanCatCreationInfo['status']];
        $loanCatCreationInfo['loan_limit'] = moneyFormatIndia($loanCatCreationInfo['loan_limit']);
        $loanCatCreationInfo['action'] = "<span class='icon-border_color loanCatCreationActionBtn' value='" . $loanCatCreationInfo['id'] . "'></span> <span class='icon-trash-2 loanCatCreationDeleteBtn' value='" . $loanCatCreationInfo['id'] . "'></span>";
        $loanCatCreation_list_arr[] = $loanCatCreationInfo;
    }
}

$pdo = null;
echo json_encode($loanCatCreation_list_arr);

