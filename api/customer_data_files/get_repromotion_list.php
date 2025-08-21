<?php
require '../../ajaxconfig.php';

$status = [
    5 => 'Cancel',
    6 => 'Revoke',
    8 => 'Cancel',
    9 => 'Revoke'
];

$re_promotion_arr = array();
$i = 0;
$whereCondition = "";

// Build where condition for repromotion_details
if (isset($_POST['repromotion_details']) && !empty($_POST['repromotion_details'])) {
    $details = $_POST['repromotion_details'];

    $conditions = [];

    // If "Needed" or "Later" selected
    $normalSelections = array_intersect($details, ['needed', 'later']);  // array_intersect cleanly extracts only the "needed"/"later" subset from whatever the user picked.
    if (!empty($normalSelections)) {
        // Make first letter uppercase to match DB values (Needed, Later)
        $normalSelectionsStr = implode("','", array_map('ucfirst', $normalSelections));
        $conditions[] = "replace(rc.repromotion_detail,' ','') IN ('$normalSelectionsStr')";
    }

    // If "To Follow" selected
    if (in_array('tofollow', $details)) {
        $conditions[] = "(rc.repromotion_detail IS NULL OR rc.repromotion_detail NOT IN ('Needed','Later'))";
    }

    // Combine conditions with OR
    if (!empty($conditions)) {
        $whereCondition = "AND (" . implode(" OR ", $conditions) . ")";  
    }
}

$customerQry = $pdo->query("SELECT 
    le.id as loan_entry_id,
    cc.id,
    cc.cus_id,
    cc.aadhar_number,
    CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name,
    anc.areaname AS area,
    lnc.linename,
    bc.branch_name,
    cc.mobile1,
    cs.status as c_sts,
    cs.sub_status as c_substs,
    rc.repromotion_detail,
    rc.created_on as created,
    cc.created_on as cus_created

FROM customer_creation cc

LEFT JOIN (
    SELECT MAX(id) AS latest_loan_id, cus_id
    FROM loan_entry
    GROUP BY cus_id
) latest_loan ON cc.cus_id = latest_loan.cus_id

LEFT JOIN loan_entry le ON le.id = latest_loan.latest_loan_id
LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id
LEFT JOIN repromotion_customer rc ON le.id = rc.loan_entry_id
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
LEFT JOIN area_name_creation anc ON cc.area = anc.id
LEFT JOIN area_creation ac ON cc.line = ac.line_id
LEFT JOIN branch_creation bc ON ac.branch_id = bc.id

WHERE cs.status IN (5, 6, 8, 9) $whereCondition

ORDER BY cc.id DESC");

if ($customerQry->rowCount() > 0) {
    while ($customerRow = $customerQry->fetch(PDO::FETCH_ASSOC)) {
        $customerRow['c_sts'] = isset($status[$customerRow['c_sts']]) ? $status[$customerRow['c_sts']] : '';
        if ($customerRow['repromotion_detail'] != '') {
            $customerRow['action'] = $customerRow['repromotion_detail'];
        } else {
            $customerRow['action'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'><a href='#' class='needed' value='" . $customerRow['loan_entry_id'] . "' data='Needed'>Needed</a><a href='#' class='later' value='" . $customerRow['loan_entry_id'] . "' data='Later'>Later</a></div></div>";
        }
        $re_promotion_arr[$i] = $customerRow;
        $i++;
    }
}

echo json_encode($re_promotion_arr);
$pdo = null; // Close Connection