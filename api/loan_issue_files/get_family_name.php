<?php
//Also used in property holder name, KYC Family Member, Loan Issue, NOC.
require '../../ajaxconfig.php';

$cus_id = $_POST['cus_id'];

$qry = $pdo->query(" SELECT fi.id, fi.fam_name, CONCAT(cc.first_name, ' ', COALESCE(cc.last_name, '')) AS cus_name
    FROM 
        customer_creation cc 
    LEFT JOIN 
        family_info fi ON cc.cus_id = fi.cus_id
    WHERE 
        cc.cus_id = '$cus_id'
    GROUP BY 
        cc.cus_id, fi.id ");

$result = [];
$customerAdded = false;  // Flag to track if the customer name is added

if ($qry->rowCount() > 0) {
    $guarantorResults = $qry->fetchAll(PDO::FETCH_ASSOC);  // Fetch the results
    
    foreach ($guarantorResults as $row) {
        // Push the customer name only once
        if (!$customerAdded) {
            $result[] = [
                'id' => 0,  // Assuming customer doesn't have an id in this context
                'name' => $row['cus_name'],
                'type' => 'Customer',  // Adding type to differentiate
            ];
            $customerAdded = true;  // Set flag to true after adding the customer
        }

        // Push the family member name (if available)
        if (!empty($row['fam_name'])) {
            $result[] = [
                'id' => $row['id'],
                'name' => $row['fam_name'],
                'type' => 'Family',  // Adding type to differentiate
            ];
        }
    }
}

$pdo = null;  // Close connection
echo json_encode($result);  // Return the result as JSON
?>
