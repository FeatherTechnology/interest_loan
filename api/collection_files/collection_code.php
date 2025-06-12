<?php
require '../../ajaxconfig.php';

$myStr = 'COL';
$selectIC = $pdo->query("SELECT collection_id FROM `collection` WHERE collection_id != '' ");

if ($selectIC->rowCount() > 0) {
    $codeAvailable = $pdo->query("SELECT collection_id FROM collection WHERE collection_id != '' ORDER BY id DESC LIMIT 1");
    while ($row = $codeAvailable->fetch()) {
        $ac2 = $row["collection_id"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-');
    $appno2 = $appno2 + 1;
    $collection_id = $myStr . "-" . "$appno2";
} else {
    $initialapp = $myStr . "-101";
    $collection_id = $initialapp;
}

$pdo = null; //Close Connection
echo json_encode($collection_id);
