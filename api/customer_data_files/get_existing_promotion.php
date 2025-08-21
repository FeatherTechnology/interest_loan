<?php
require '../../ajaxconfig.php';

$status = [
    10 => 'Closed',
    11 => 'Closed',
    12 => 'NOC',
    13 => 'NOC',
    14 => 'NOC'
];

$sub_status = [
    1 => 'Consider',
    2 => 'Reject'
];

$existing_promo_arr = [];
$whereCondition = "";

// Build where condition for existing_details
if (isset($_POST['existing_details']) && !empty($_POST['existing_details'])) {
    $details = $_POST['existing_details'];

    $conditions = [];

    // If "Needed" or "Later" selected
    $normalSelections = array_intersect($details, ['needed', 'later']);
    if (!empty($normalSelections)) {
        // Make first letter uppercase to match DB values (Needed, Later)
        $normalSelectionsStr = implode("','", array_map('ucfirst', $normalSelections));
        $conditions[] = "replace(ec.existing_detail,' ','') IN ('$normalSelectionsStr')";
    }

    // If "To Follow" selected
    if (in_array('tofollow', $details)) {
        $conditions[] = "(ec.existing_detail IS NULL OR ec.existing_detail NOT IN ('Needed','Later'))";
    }

    // Combine conditions with OR
    if (!empty($conditions)) {
        $whereCondition = "AND (" . implode(" OR ", $conditions) . ")";
    }
}

// Prepare the query to fetch customers with status >= 11
$query = " SELECT 
    le.id AS loan_entry_id,
    cc.id,
    cc.cus_id,
    cc.aadhar_number,
    CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name,
    anc.areaname AS area,
    lnc.linename,
    bc.branch_name,
    cc.mobile1,
    cs.status AS c_sts,
    cs.sub_status AS c_substs,
    ec.created_on AS created,
    ec.existing_detail,
    cc.created_on AS cus_created
FROM customer_creation cc

LEFT JOIN (
    SELECT MAX(id) AS max_loan_id, cus_id
    FROM loan_entry
    GROUP BY cus_id
) latest_loan ON cc.cus_id = latest_loan.cus_id

LEFT JOIN loan_entry le ON le.id = latest_loan.max_loan_id
LEFT JOIN customer_status cs ON le.id = cs.loan_entry_id
LEFT JOIN existing_customer ec ON le.id = ec.loan_entry_id
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id
LEFT JOIN area_name_creation anc ON cc.area = anc.id
LEFT JOIN area_creation ac ON cc.line = ac.line_id
LEFT JOIN branch_creation bc ON ac.branch_id = bc.id

WHERE cs.status >= 11 $whereCondition
ORDER BY cc.id DESC";

$customerQry = $pdo->query($query);

if ($customerQry->rowCount() > 0) {
    while ($customerRow = $customerQry->fetch(PDO::FETCH_ASSOC)) {
        // Map customer status
        $customerRow['c_sts'] = isset($status[$customerRow['c_sts']]) ? $status[$customerRow['c_sts']] : '';

        // Fetch loan status
        $loanCustomerStatus = loanCustomerStatus($pdo, $customerRow['cus_id']);
        $customerRow['c_substs'] = $loanCustomerStatus;

        // Handle the 'existing_detail' field
        if ($customerRow['existing_detail'] != '') {
            $customerRow['action'] = $customerRow['existing_detail'];
        } else {
            $customerRow['action'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'><a href='#' class='exs_needed' value='" . $customerRow['loan_entry_id'] . "' data='Needed'>Needed</a><a href='#' class='exs_later' value='" . $customerRow['loan_entry_id'] . "' data='Later'>Later</a></div></div>";
        }

        // Add the customer to the array
        $existing_promo_arr[] = $customerRow;  // Cleaner way of adding to array
    }
}

echo json_encode($existing_promo_arr);
// Function to fetch loan status of a customer
function loanCustomerStatus($pdo, $cus_id)
{
    $stmt = $pdo->prepare("SELECT cs.status as cs_status, cs.sub_status as sub_sts
        FROM loan_entry le
        JOIN customer_status cs ON le.id = cs.loan_entry_id
        WHERE le.cus_id = :cus_id ORDER BY le.id DESC LIMIT 1");

    $stmt->execute(['cus_id' => $cus_id]);
    $row1 = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row1) {
        $cs_status = $row1['cs_status'];
        $sub_sts = $row1['sub_sts'];
        if ($cs_status == '11') {
            return ($sub_sts == '1') ? 'Consider' : 'Rejected';
        } elseif ($cs_status == '12') {
            return 'In NOC';
        } elseif ($cs_status == '13') {
            return 'NOC Completed';
        } elseif ($cs_status == '14') {
            return 'Removed From NOC';
        }
    }

    return ''; // Default return value if no conditions match
}

$pdo = null; // Close Connection