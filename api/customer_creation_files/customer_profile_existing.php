<?php
require '../../ajaxconfig.php';

$aadhar_number = $_POST['aadhar_number'];
$response = [];

$qry = $pdo->query("SELECT cc.*, le.id as loan_entry_id , cs.cus_id AS existing_cus_id 
    FROM customer_creation cc 
    LEFT JOIN loan_entry le ON cc.cus_id = le.cus_id 
    LEFT JOIN customer_status cs ON cc.cus_id = cs.cus_id AND cs.status >= 7 
    WHERE cc.aadhar_number = '$aadhar_number' 
    ORDER BY cc.id DESC 
    LIMIT 1");

if ($qry->rowCount() > 0) {
    $result = $qry->fetch(PDO::FETCH_ASSOC); // Fetch single customer row
    $result['cus_data'] = $result['existing_cus_id'] ? 'Existing' : 'New';

    $loan_entry_id = $result['loan_entry_id'] ?? null;

    if (!empty($loan_entry_id)) {
        // Fetch guarantors linked to this loan_entry_id
        $guaQry = $pdo->prepare("SELECT 
            gi.family_info_id as id,
            fi.fam_name,
            fi.fam_relationship,
            fi.relation_type,
            fi.fam_aadhar,
            fi.fam_mobile,
            gi.gu_pic
        FROM guarantor_info gi
        LEFT JOIN family_info fi ON gi.family_info_id = fi.id
        WHERE gi.loan_entry_id = ?");

        $guaQry->execute([$loan_entry_id]);
        $guarantor_info = $guaQry->fetchAll(PDO::FETCH_ASSOC);

        // Add it to result
        $result['guarantor_info'] = $guarantor_info;
    }

    $response[] = $result;
}

$pdo = null;

echo json_encode(['result' => 1, 'data' => $response]);
