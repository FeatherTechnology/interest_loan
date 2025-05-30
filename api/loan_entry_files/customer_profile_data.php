<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
$response = [];

// Fetch customer profile info
$qry = $pdo->prepare("SELECT cc.*, le.id as loan_entry_id
FROM customer_creation cc
LEFT JOIN loan_entry le ON cc.cus_id = le.cus_id 
LEFT JOIN line_name_creation lnc ON cc.line = lnc.id 
LEFT JOIN area_name_creation anc ON cc.area = anc.id
WHERE le.id = ?");
$qry->execute([$id]);

if ($qry->rowCount() > 0) {
    $result = $qry->fetch(PDO::FETCH_ASSOC);
    $loan_entry_id = $result['loan_entry_id'];

    // Fetch guarantors linked to this loan_entry_id
    $guaQry = $pdo->prepare("SELECT 
        gi.family_info_id as id,
        fi.fam_name,
        fi.fam_relationship,
        fi.relation_type,
        fi.fam_aadhar,
        fi.fam_mobile
    FROM guarantor_info gi
    LEFT JOIN family_info fi ON gi.family_info_id = fi.id
    WHERE gi.loan_entry_id = ?");
    
    $guaQry->execute([$loan_entry_id]);

    $guarantor_info = $guaQry->fetchAll(PDO::FETCH_ASSOC);

    // Add it to result
    $result['guarantor_info'] = $guarantor_info;

    $response[] = $result;
}

$pdo = null;
echo json_encode(['result' => 1, 'data' => $response]);
