<?php
require '../../ajaxconfig.php';
@session_start();

class bulkUploadClass
{
    public function uploadFiletoFolder()
    {
        $excel = $_FILES['excelFile']['name'];
        $excel_temp = $_FILES['excelFile']['tmp_name'];
        $excelfolder = "../../uploads/bulk_upload/excelFile/" . $excel;

        $fileExtension = pathinfo($excelfolder, PATHINFO_EXTENSION); //get the file extension

        $excel = uniqid() . '.' . $fileExtension;
        while (file_exists("../../uploads/bulk_upload/excelFile/" . $excel)) {
            // this loop will continue until it generates a unique file name
            $excel = uniqid() . '.' . $fileExtension;
        }
        $excelfolder = "../../uploads/bulk_upload/excelFile/" . $excel;
        move_uploaded_file($excel_temp, $excelfolder);
        return $excelfolder;
    }

    public function fetchAllRowData($Row)
    {
        $dataArray = array(
            'aadhar_number' => isset($Row[1]) ? $Row[1] : "",
            'first_name' => isset($Row[2]) ? $Row[2] : "",
            'last_name' => isset($Row[3]) ? $Row[3] : "",
            'area' => isset($Row[4]) ? $Row[4] : "",
            'line' => isset($Row[5]) ? $Row[5] : "",
            'mobile1' => isset($Row[6]) ? $Row[6] : "",
            'fam_name' => isset($Row[7]) ? $Row[7] : "",
            'fam_relationship' => isset($Row[8]) ? $Row[8] : "",
            'relation_type' => isset($Row[9]) ? $Row[9] : "",
            'fam_age' => isset($Row[10]) ? $Row[10] : "",
            'fam_occupation' => isset($Row[11]) ? $Row[11] : "",
            'fam_aadhar' => isset($Row[12]) ? $Row[12] : "",
            'fam_mobile' => isset($Row[13]) ? $Row[13] : "",
            'cus_limit' => isset($Row[14]) ? $Row[14] : "",
            'about_cus' => isset($Row[15]) ? $Row[15] : "",
            'cus_data' => isset($Row[16]) ? $Row[16] : "",
            'guarantor_aadhar' => isset($Row[17]) ? $Row[17] : "",
            'loan_category' => isset($Row[18]) ? $Row[18] : "",
            'loan_amount' => isset($Row[19]) ? $Row[19] : "",
            'benefit_method' => isset($Row[20]) ? $Row[20] : "",
            'due_method' => isset($Row[21]) ? $Row[21] : "",
            'due_period' => isset($Row[22]) ? $Row[22] : "",
            'interest_calculate' => isset($Row[23]) ? $Row[23] : "",
            'due_calculate' => isset($Row[24]) ? $Row[24] : "",
            'interest_rate_calc' => isset($Row[25]) ? $Row[25] : "",
            'due_period_calc' => isset($Row[26]) ? $Row[26] : "",
            'doc_charge_calc' => isset($Row[27]) ? $Row[27] : "",
            'processing_fees_calc' => isset($Row[28]) ? $Row[28] : "",
            'loan_amnt_calc' => isset($Row[29]) ? $Row[29] : "",
            'doc_charge_calculate' => isset($Row[30]) ? $Row[30] : "",
            'processing_fees_calculate' => isset($Row[31]) ? $Row[31] : "",
            'net_cash_calc' => isset($Row[32]) ? $Row[32] : "",
            'interest_amnt_calc' => isset($Row[33]) ? $Row[33] : "",
            'loan_date' => isset($Row[34]) ? $Row[34] : "",
            'due_startdate_calc' => isset($Row[35]) ? $Row[35] : "",
            'maturity_date_calc' => isset($Row[36]) ? $Row[36] : "",
            'referred_calc' => isset($Row[37]) ? $Row[37] : "",
            'agent_id_calc' => isset($Row[38]) ? $Row[38] : "",
            'agent_name_calc' => isset($Row[39]) ? $Row[39] : "",
            'net_bal_cash' => isset($Row[40]) ? $Row[40] : "",
            'payment_type' => isset($Row[41]) ? $Row[41] : "",
            'payment_mode' => isset($Row[42]) ? $Row[42] : "",
            'bank_name' => isset($Row[43]) ? $Row[43] : "",
            'cash' => isset($Row[44]) ? $Row[44] : "",
            'cheque_val' => isset($Row[45]) ? $Row[45] : "",
            'transaction_val' => isset($Row[46]) ? $Row[46] : "",
            'transaction_id' => isset($Row[47]) ? $Row[47] : "",
            'cheque_no' => isset($Row[48]) ? $Row[48] : "",
            'cheque_remark' => isset($Row[49]) ? $Row[49] : "",
            'tran_remark' => isset($Row[50]) ? $Row[50] : "",
            'balance_amount' => isset($Row[51]) ? $Row[51] : "",
            'issue_date' => isset($Row[52]) ? $Row[52] : "",
            'issue_person' => isset($Row[53]) ? $Row[53] : "",
            'relationship' => isset($Row[54]) ? $Row[54] : "",
        );

        $dataArray['aadhar_number'] = strlen($dataArray['aadhar_number']) == 12 ? $dataArray['aadhar_number'] : 'Invalid';

        $cus_dataArray = ['New' => 'New', 'Existing' => 'Existing'];

        $dataArray['cus_data'] = $this->arrayItemChecker($cus_dataArray, $dataArray['cus_data']);

        $dataArray['fam_aadhar'] = strlen($dataArray['fam_aadhar']) == 12 ? $dataArray['fam_aadhar'] : 'Invalid';

        $guarantor_relationshipArray = ['Father' => 'Father', 'Mother' => 'Mother', 'Spouse' => 'Spouse', 'Sister' => 'Sister', 'Brother' => 'Brother', 'Son' => 'Son', 'Daughter' => 'Daughter', 'Other' => 'Other'];
        $dataArray['fam_relationship'] = $this->arrayItemChecker($guarantor_relationshipArray, $dataArray['fam_relationship']);

        $dataArray['fam_mobile'] = strlen($dataArray['fam_mobile']) == 10 ? $dataArray['fam_mobile'] : 'Invalid';

        $interest_calculateArray = ['Month' => 'Month', 'Days' => 'Days'];
        $dataArray['interest_calculate'] = $this->arrayItemChecker($interest_calculateArray, $dataArray['interest_calculate']);

        $due_method_calcArray = ['Monthly' => 'Monthly'];
        $dataArray['due_method'] = $this->arrayItemChecker($due_method_calcArray, $dataArray['due_method']);

        $dataArray['due_startdate_calc'] = $this->dateFormatChecker($dataArray['due_startdate_calc']);

        $dataArray['maturity_date_calc'] = $this->dateFormatChecker($dataArray['maturity_date_calc']);
        $dataArray['issue_date'] = $this->dateFormatChecker($dataArray['issue_date']);

        $referred_typeArray = ['Yes' => '0', 'No' => '1'];
        $dataArray['referred_calc'] = $this->arrayItemChecker($referred_typeArray, $dataArray['referred_calc']);

        // Check only if 'payment_type' field is not empty
        if (!empty($dataArray['payment_type'])) {
            $refer_typeArray = ['Split' => '1', 'Single' => '2'];
            $dataArray['payment_type'] = $this->arrayItemChecker($refer_typeArray, $dataArray['payment_type']);
        }
        $payment_typeArray = ['Cash' => '1', 'Bank Transfer' => '2', 'Cheque' => '3'];
        $dataArray['payment_mode'] = $this->arrayItemChecker($payment_typeArray, $dataArray['payment_mode']);

        return $dataArray;
    }

    function dateFormatChecker($checkdate)
    {
        // Attempt to create a DateTime object from the provided date
        $dateTime = DateTime::createFromFormat('Y-m-d', $checkdate);

        // Check if the date is in the correct format
        if ($dateTime && $dateTime->format('Y-m-d') === $checkdate) {
            // Date is in the correct format, no need to change anything
            return $checkdate;
        }
        return 'Invalid Date';
    }

    function arrayItemChecker($arrayList, $arrayItem)
    {
        if (array_key_exists($arrayItem, $arrayList)) {
            $arrayItem = $arrayList[$arrayItem];
        } else {
            $arrayItem = 'Not Found';
        }
        return $arrayItem;
    }

    // <------------------------------------------------------------------ Agent Function ----------------------------------------------------------------->

    function checkAgent($pdo, $agent_name)
    {
        if ($agent_name != '') { // because it's not mandatory
            $stmt = $pdo->query("SELECT id FROM `agent_creation` WHERE LOWER(REPLACE(TRIM(agent_name),' ' ,'')) = LOWER(REPLACE(TRIM('$agent_name'),' ' ,'')) ");
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $agentCheck = $row["id"];
            } else {
                $agentCheck = 'Not Found';
            }
        } else {
            $agentCheck = '';
        }
        return $agentCheck;
    }

    // <------------------------------------------------------------------ Loan ID Function ---------------------------------------------------------------->

    function getLoanCode($pdo)
    {
        $qry = $pdo->query("SELECT loan_id FROM loan_entry WHERE loan_id != '' ORDER BY id DESC LIMIT 1");

        if ($qry->rowCount() > 0) {
            $qry_info = $qry->fetch();
            $l_no = ltrim(strstr($qry_info['loan_id'], '-'), '-');
            $l_no = $l_no + 1;
            $loan_ID_final = "LID-" . "$l_no";
        } else {
            $loan_ID_final = "LID-101";
        }

        return $loan_ID_final;
    }

    // <------------------------------------------------------------------ Cus ID Function ---------------------------------------------------------------->

    function getCustomerCode($pdo, $aadhar_number)
    {
        // Check if customer ID already exists
        $stmt = $pdo->prepare("SELECT cus_id FROM customer_creation WHERE aadhar_number = :aadhar_number");
        $stmt->execute(['aadhar_number' => $aadhar_number]);

        if ($stmt->rowCount() > 0) {
            // If ID exists, return it as-is
            $qry_info = $stmt->fetch();
            return $qry_info['cus_id'];
        } else {
            // Get the last inserted customer ID to generate a new one
            $selectIC = $pdo->query("SELECT cus_id FROM customer_creation ORDER BY id DESC LIMIT 1");

            if ($selectIC->rowCount() > 0) {
                $row = $selectIC->fetch();
                $lastCusId = $row["cus_id"]; // Example: C-120
                $lastNumber = (int) ltrim(strstr($lastCusId, '-'), '-'); // Extract number after '-'
                $newNumber = $lastNumber + 1;
                return "C-" . $newNumber;
            } else {
                // If no customer found at all, start fresh
                return "C-101";
            }
        }
    }

    // <------------------------------------------------------------------ Guarantor Function ------------------------------------------------------------->

    function guarantorName($pdo, $guarantor_aadhar)
    {
        $stmt = $pdo->prepare("SELECT id FROM family_info WHERE fam_aadhar = :guarantor_aadhar");
        $stmt->execute([':guarantor_aadhar' => $guarantor_aadhar]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $family_info_id =  $row["id"];
        } else {
            $family_info_id = 'Not Found';
        }

        return $family_info_id;
    }

    // <---------------------------------------------------------------- Area Function -------------------------------------------------------------------->

    function getAreaId($pdo, $areaname)
    {
        $stmt = $pdo->query("SELECT anc.id, anc.areaname FROM area_creation ac 
        LEFT JOIN area_creation_area_name acan ON ac.id = acan.area_creation_id
        LEFT JOIN area_name_creation anc ON acan.area_id = anc.id
        WHERE LOWER(REPLACE(TRIM(anc.areaname),' ','')) = LOWER(REPLACE(TRIM('$areaname'),' ',''))");

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $area_id = $row["id"];
        } else {
            $area_id = 'Not Found';
        }

        return $area_id;
    }

    // <----------------------------------------------------------------- Loan Category Function ------------------------------------------------------------->

    function getLoanCategoryId($pdo, $loan_category)
    {
        $query = "SELECT lcc.id FROM loan_category_creation lcc 
        LEFT JOIN loan_category lc ON lcc.loan_category = lc.id 
        WHERE LOWER(REPLACE(TRIM(lc.loan_category), ' ', '')) = LOWER(REPLACE(TRIM(:loan_category), ' ', ''))
        LIMIT 1 ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':loan_category' => $loan_category]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        } else {
            return 'Not Found';
        }
    }

    // <----------------------------------------------------------------------- Line Function --------------------------------------------------------------->

    function getAreaLine($pdo, $areaId)
    {
        $defaultLineId = null;
        $query = ("SELECT ac.line_id, lnc.linename FROM `area_creation` ac 
        LEFT JOIN line_name_creation lnc ON ac.line_id = lnc.id 
        LEFT JOIN area_creation_area_name acan ON ac.id = acan.area_creation_id WHERE acan.area_id = :areaId");

        $stmt = $pdo->prepare($query);
        $stmt->execute([':areaId' => $areaId]);

        if ($stmt) {
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $lineId = $result['line_id'];
            } else {
                $lineId = $defaultLineId; // If no matching line_id found, set to default
            }
        } else {
            $lineId = $defaultLineId;
        }

        return $lineId;
    }

    // <----------------------------------------------------------------- Bank Function ------------------------------------------------------------------>

    function getBankId($pdo, $bank_name)
    {
        $stmt = $pdo->query("SELECT b.id
    FROM `loan_issue` si 
    JOIN bank_creation b ON FIND_IN_SET(b.id, si.bank_name)
    WHERE LOWER(REPLACE(TRIM(b.bank_name), ' ', '')) = LOWER(REPLACE(TRIM('$bank_name'), ' ', ''))");

        if ($stmt->rowCount() > 0) {
            $bank_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            $bank_id = ''; // Return 'Not Found' if branch does not exist
        }
        return $bank_id;
    }

    // <------------------------------------------------------------------ Insert Family Info ------------------------------------------------------------>

    function FamilyTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];
        $check_query = "SELECT id FROM family_info WHERE cus_id = '" . $data['cus_id'] . "' AND fam_aadhar = '" . $data['fam_aadhar'] . "'";
        $result = $pdo->query($check_query);
        if ($result->rowCount() == 0) {
            $insert_query = "INSERT INTO family_info (cus_id, fam_name, fam_relationship, relation_type , fam_age, fam_occupation, fam_aadhar, fam_mobile, insert_login_id, created_on, updated_on) 
                VALUES (
                    '" . $data['cus_id'] . "',
                    '" . $data['fam_name'] . "',
                    '" . $data['fam_relationship'] . "',
                    '" . $data['relation_type'] . "',
                    '" . $data['fam_age'] . "',
                    '" . $data['fam_occupation'] . "',
                    '" . $data['fam_aadhar'] . "',
                    '" . $data['fam_mobile'] . "',
                    '" . $user_id . "',
                    '" . strip_tags($data['loan_date']) . "',
                    '" . strip_tags($data['loan_date']) . "'
                )
            ";

            $pdo->query($insert_query);
        }
    }

    function LoanEntryTables($pdo, $data)
    {
        // <------------------------------------------------------------------ Insert Customer Creation  ------------------------------------------------------------>

        $user_id = $_SESSION['user_id'];
        $aadhar = strip_tags($data['aadhar_number']);
        $check_stmt = $pdo->prepare("SELECT aadhar_number FROM customer_creation WHERE aadhar_number = :aadhar");
        $check_stmt->execute(['aadhar' => $aadhar]);

        if ($check_stmt->rowCount() > 0) {
            $errcolumns[] = "Duplicate Aadhar Number found: " . $aadhar;
        } else {
            $insert_cp_query = "INSERT INTO customer_creation (
            cus_id, aadhar_number , first_name, last_name, area, line, customer_data , mobile1 , cus_limit, about_cus, insert_login_id, created_on, updated_on
        ) VALUES (
            '" . strip_tags($data['cus_id']) . "','" . strip_tags($data['aadhar_number']) . "', '" . strip_tags($data['first_name']) . "', 
            '" . strip_tags($data['last_name']) . "', '" . strip_tags($data['area_id']) . "', '" . strip_tags($data['line_id']) . "',  '2', 
            '" . strip_tags($data['mobile1']) . "', '" . strip_tags($data['cus_limit']) . "', '" . strip_tags($data['about_cus']) . "', 
            '" . $user_id . "', '" . strip_tags($data['loan_date']) . "', '" . strip_tags($data['loan_date']) . "'
        )";

            $pdo->query($insert_cp_query);
        }

        // <------------------------------------------------------------ Insert Loan Entry Table --------------------------------------------------------------------->

        $cus_id = strip_tags($data['cus_id']);
        // Step 1: Check if customer already exists with active status
        $checkQry = $pdo->prepare("SELECT le.id FROM loan_entry le JOIN customer_status cs ON le.cus_id = cs.cus_id WHERE le.cus_id = :cus_id 
        AND cs.status >= 1 LIMIT 1 ");

        $checkQry->execute(['cus_id' => $cus_id]);

        // Step 2: Set cus_data value
        if ($checkQry->rowCount() > 0) {
            $data['cus_data'] = 'Existing';
        } else {
            $data['cus_data'] = 'New';
        }

        $insert_vlc = "INSERT INTO loan_entry (
            aadhar_number , cus_id , cus_data , loan_id, loan_category, loan_amount, benefit_method, due_method, due_period , interest_calculate, due_calculate, interest_rate_calc, due_period_calc, doc_charge_calc, processing_fees_calc, loan_amnt_calc, doc_charge_calculate, processing_fees_calculate, net_cash_calc, interest_amnt_calc, loan_date , due_startdate_calc, maturity_date_calc, referred_calc, agent_id_calc , agent_name_calc , insert_login_id, created_on, updated_on
        ) VALUES (
            '" . strip_tags($data['aadhar_number']) . "', '" . strip_tags($data['cus_id']) . "',  '" . strip_tags($data['cus_data']) . "', 
            '" . strip_tags($data['loan_id']) . "', '" . strip_tags($data['loan_category_id']) . "','" . strip_tags($data['loan_amount']) . "', 
            '" . strip_tags($data['benefit_method']) . "', '" . strip_tags($data['due_method']) . "',  '" . strip_tags($data['due_period']) . "',
            '" . strip_tags($data['interest_calculate']) . "', '" . strip_tags($data['due_calculate']) . "', '" . strip_tags($data['interest_rate_calc']) . "',
            '" . strip_tags($data['due_period_calc']) . "', '" . strip_tags($data['doc_charge_calc']) . "', '" . strip_tags($data['processing_fees_calc']) . "',
            '" . strip_tags($data['loan_amnt_calc']) . "', '" . strip_tags($data['doc_charge_calculate']) . "', '" . strip_tags($data['processing_fees_calculate']) . "',
            '" . strip_tags($data['net_cash_calc']) . "',  '" . strip_tags($data['interest_amnt_calc']) . "',  '" . strip_tags($data['loan_date']) . "',
            '" . strip_tags($data['due_startdate_calc']) . "',  '" . strip_tags($data['maturity_date_calc']) . "', '" . strip_tags($data['referred_calc']) . "',
            '" . strip_tags($data['agent_id']) . "', '" . strip_tags($data['agent_name_calc']) . "', '" . $user_id . "', '" . strip_tags($data['loan_date']) . "',
            '" . strip_tags($data['loan_date']) . "'
        )";

        $pdo->query($insert_vlc);

        // Get the last inserted Id
        $loan_entry_id = $pdo->lastInsertId();

        if ($data['family_info_id'] !== 'Not Found') {

            $guarantor_insert_query = "INSERT INTO guarantor_info (loan_entry_id, family_info_id) VALUES (:loan_entry_id, :family_info_id )";

            $stmt = $pdo->prepare($guarantor_insert_query);
            $stmt->execute([
                ':loan_entry_id' => $loan_entry_id,
                ':family_info_id' => $data['family_info_id'],
            ]);
        }

        $cus_sts_insert_query = "INSERT INTO `customer_status` (`cus_id`, `loan_entry_id`, `status`, `insert_login_id`, `created_on`)  VALUES (:cus_id ,:loan_entry_id, 2, :user_id, NOW())";
        $stmt = $pdo->prepare($cus_sts_insert_query);
        $stmt->execute([
            ':cus_id' => strip_tags($data['cus_id']),
            ':loan_entry_id' => $loan_entry_id,
            ':user_id' => $user_id,
        ]);

        // <------------------------------------------------------------ Insert Loan Issue Table --------------------------------------------------------------------->

        $insert_li_query = "INSERT INTO loan_issue
        (`cus_id`, `loan_entry_id`, `loan_amnt`, `net_cash`, `net_bal_cash`,`payment_type`, `payment_mode`, `bank_name`, `cash`,`cheque_val`,`transaction_val`, 
        `transaction_id`,`cheque_no`, `cheque_remark`, `tran_remark`, `balance_amount`, `issue_date`,`issue_person`, `relationship`, `insert_login_id`, `created_on`
        ) VALUES (
        '" . strip_tags($data['cus_id']) . "','" . strip_tags($loan_entry_id) . "', '" . strip_tags($data['loan_amount']) . "', 
        '" . strip_tags($data['net_cash_calc']) . "', '" . strip_tags($data['net_bal_cash']) . "', '" . strip_tags($data['payment_type']) . "',
        '" . strip_tags($data['payment_mode']) . "', '" . strip_tags($data['bank_id']) . "', '" . strip_tags($data['cash']) .  "',
        '" . strip_tags($data['cheque_val']) .  "', '" . strip_tags($data['transaction_val']) .  "','" . strip_tags($data['transaction_id']) . "',
        '" . strip_tags($data['cheque_no']) . "', '" . strip_tags($data['cheque_remark']) . "','" . strip_tags($data['tran_remark']) . "',
        '" . strip_tags($data['balance_amount']) . "', '" . strip_tags($data['issue_date']) . "', '" . strip_tags($data['issue_person']) . "',
        '" . strip_tags($data['relationship']) . "', '" .  $user_id . "', '"  . strip_tags($data['loan_date']) . "')";

        $pdo->query($insert_li_query);

        $cus_sts_update_query2 = "UPDATE `customer_status` SET `status` = 7, `update_login_id` = :user_id, `updated_on` = NOW() WHERE `loan_entry_id` = :loan_entry_id";
        $stmt = $pdo->prepare($cus_sts_update_query2);
        $stmt->execute([
            ':user_id' => $user_id,
            ':loan_entry_id' => $loan_entry_id
        ]);
        return $loan_entry_id;
    }

    function handleError($data)
    {
        $errcolumns = array();

        if ($data['cus_id'] == 'Invalid') {
            $errcolumns[] = 'Customer ID';
        }

        if ($data['cus_data'] == 'Not Found') {
            $errcolumns[] = 'Customer Data';
        }

        if ($data['first_name'] == '') {
            $errcolumns[] = 'First Name';
        }

        if ($data['last_name'] == '') {
            $errcolumns[] = 'Last Name';
        }

        if ($data['mobile1'] == 'Invalid') {
            $errcolumns[] = 'Mobile Number';
        }

        if ($data['fam_name'] == '') {
            $errcolumns[] = 'Family Name';
        }

        if ($data['fam_aadhar'] == 'Invalid') {
            $errcolumns[] = 'Family Aadhar';
        }

        if ($data['fam_mobile'] == 'Invalid') {
            $errcolumns[] = 'Family Mobile Number';
        }

        if (!preg_match('/^[A-Za-z0-9]+$/', $data['fam_occupation'])) {
            $errcolumns[] = 'Family Occupation';
        }

        if ($data['loan_category'] == 'Not Found') {
            $errcolumns[] = 'Loan Category ID';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['loan_amount'])) {
            $errcolumns[] = 'Loan Amount';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['interest_amnt_calc'])) {
            $errcolumns[] = 'Interest Amount Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['doc_charge_calculate'])) {
            $errcolumns[] = 'Document Charge Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['processing_fees_calculate'])) {
            $errcolumns[] = 'Processing Fee Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['net_cash_calc'])) {
            $errcolumns[] = 'Net Cash Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['cus_limit'])) {
            $errcolumns[] = 'Customer Limit';
        }

        if ($data['loan_date'] == 'Invalid Date') {
            $errcolumns[] = 'Loan Date';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['interest_rate_calc'])) {
            $errcolumns[] = 'Interest Rate';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['due_period_calc'])) {
            $errcolumns[] = 'Due Period';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['doc_charge_calc'])) {
            $errcolumns[] = 'Document Charge';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['processing_fees_calc'])) {
            $errcolumns[] = 'Processing Fee';
        }

        if ($data['due_startdate_calc'] == 'Invalid Date') {
            $errcolumns[] = 'Due Start From';
        }

        if ($data['maturity_date_calc'] == 'Invalid Date') {
            $errcolumns[] = 'Maturity Date';
        }

        if ($data['issue_date'] == 'Invalid Date') {
            $errcolumns[] = 'Issued Date';
        }

        if ($data['agent_id'] == 'Not Found') {
            $errcolumns[] = 'Agent ID';
        }

        if ($data['issue_person'] == 'Not Found') {
            $errcolumns[] = 'Issue Person';
        }

        if ($data['payment_type'] == 'Not Found') {
            $errcolumns[] = 'Payment Type';
        }

        if ($data['area_id'] == 'Not Found') {
            $errcolumns[] = 'Area ID';
        }

        return $errcolumns;
    }
}
