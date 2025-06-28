<?php
require '../../ajaxconfig.php';
include 'bulkUploadClass.php';
require_once('../../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../../vendor/csvreader/SpreadsheetReader_XLSX.php');


$obj = new bulkUploadClass();

$allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'text/csv', 'text/xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if (in_array($_FILES["excelFile"]["type"], $allowedFileType)) {

    $excelfolder = $obj->uploadFiletoFolder();

    $Reader = new SpreadsheetReader_XLSX($excelfolder);
    $sheetCount = count($Reader->sheets());

    for ($i = 0; $i < $sheetCount; $i++) {

        $Reader->ChangeSheet($i);
        $rowChange = 0;
        foreach ($Reader as $Row) {
            if ($rowChange != 0) { // omitted 0,1 to avoid headers

                $data = $obj->fetchAllRowData($Row);

                $data['loan_id'] = $obj->getLoanCode($pdo);

                $data['cus_id'] = $obj->getCustomerCode($pdo, $data['aadhar_number']);

                $data['loan_category_id'] = $obj->getLoanCategoryId($pdo, $data['loan_category']);

                $data['agent_id'] = $obj->checkAgent($pdo, $data['agent_id_calc']);

                $data['area_id'] = $obj->getAreaId($pdo, $data['area']);

                $data['line_id'] = $obj->getAreaLine($pdo, $data['area_id']);

                $data['bank_id'] = $obj->getBankId($pdo, $data['bank_name']);

                $err_columns = $obj->handleError($data);
                if (empty($err_columns)) {
                    // Call LoanEntryTables function
                    $obj->FamilyTable($pdo, $data);
                    $data['family_info_id'] = $obj->guarantorName($pdo, $data['guarantor_aadhar']);
                    $loan_entry_id = $obj->LoanEntryTables($pdo, $data);
                } else {
                    $errtxt = "Please Check the input given in Serial No: " . ($rowChange) . " on below. <br><br>";
                    $errtxt .= "<ul>";
                    foreach ($err_columns as $columns) {
                        $errtxt .= "<li>$columns</li>";
                    }
                    $errtxt .= "</ul><br>";
                    $errtxt .= "Insertion completed till Serial No: " . ($rowChange - 1);
                    echo $errtxt;
                    exit();
                }
            }

            $rowChange++;
        }
    }
    $message = 'Bulk Upload Completed.';
} else {
    $message = 'File is not in Excel Format.';
}

echo $message;
