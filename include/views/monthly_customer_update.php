<?php
//  1. Set the log file path
$logFile = __DIR__ . '/monthly_update_log.txt';

// 2. Define the logMessage() function
function logMessage($message)
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// 3. Prevent script from running on non-1st days
if (date('d') != '1') {
    logMessage("Not the 1st of the month. Script exited.");
    exit;
}
logMessage(" Script started at " . date('h:i:s A'));

include_once(__DIR__ . '/../../ajaxconfig.php');

logMessage("Database connection loaded. Fetching customer profile IDs...");

try {
    $qry = $pdo->query("SELECT cs.loan_entry_id as le_id FROM customer_status cs WHERE cs.status = 7 ORDER BY cs.id ASC");
    $loan_entry_id = array_column($qry->fetchAll(PDO::FETCH_ASSOC), 'le_id');
} catch (Exception $e) {
    logMessage(" Database error: " . $e->getMessage());
    exit;
}

logMessage("Total le_ids fetched: " . count($loan_entry_id));

$chunks = array_chunk($loan_entry_id, 2);

foreach ($chunks as $chunk) {
    foreach ($chunk as $le_id) {
        logMessage("Processing le_id: $le_id");

        $postData = ['le_id' => $le_id];

        $ch = curl_init('http://spfeather-002-site12.ktempurl.com/api/collection_files/resetCustomerStatus.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $responseJSON = curl_exec($ch);

        if (curl_errno($ch)) {
            logMessage(" CURL error for resetCustomerStatus: " . curl_error($ch));
            curl_close($ch);
            continue;
        }

        curl_close($ch);
        $response = json_decode(trim($responseJSON), true);

        if (empty($response) || !isset($response['le_id'])) {
            logMessage(" No response for le_id $le_id.");
            continue;
        }

        $sub_status_customer = $response['sub_status_customer'][0] ?? null;

        $ch2 = curl_init('http://spfeather-002-site12.ktempurl.com/api/collection_files/updateCustomerStatus.php');
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, [
            'le_id' => $le_id,
            'sub_status_customer' => $sub_status_customer,
            'userid' => '1'
        ]);
        $updateResponse = curl_exec($ch2);

        if (curl_errno($ch2)) {
            logMessage(" CURL error for updateCustomerStatus: " . curl_error($ch2));
            curl_close($ch2);
            continue;
        }

        curl_close($ch2);
        logMessage(" Updated le_id $le_id: $updateResponse");
    }
}

logMessage("Script finished.");
