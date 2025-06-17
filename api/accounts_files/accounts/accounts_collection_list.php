<?php
require "../../../ajaxconfig.php";
require_once '../../../include/views/money_format_india.php';

$collection_list_arr = array();
$cash_type = $_POST['cash_type'];
$bank_id = $_POST['bank_id'];

if ($cash_type == '1') {
    $cndtn = "collection_mode = '1' ";
} elseif ($cash_type == '2') {
    $cndtn = "collection_mode != '1' AND bank_id = '$bank_id' ";
}

//collection_mode = 1 - cash; 2 to 5 - bank;
$qry = $pdo->query("WITH first_query AS (
    SELECT 
        u.id AS userid, 
        u.name, 
        lnc.linename, 
        bc.branch_name, 
        (
            SELECT COUNT(*) 
            FROM collection c 
            WHERE $cndtn 
            AND c.insert_login_id = u.id 
            AND c.collection_date > COALESCE(
                (SELECT created_on FROM accounts_collect_entry WHERE user_id = u.id AND $cndtn ORDER BY id DESC LIMIT 1), 
                '1970-01-01 00:00:00'
            ) 
            AND c.collection_date <= NOW()
        ) as no_of_bills,
        SUM(c.total_paid_track) AS total_amount, 
        '1' AS type
    FROM `collection` c 
    LEFT JOIN customer_creation cc ON cc.cus_id = c.cus_id
    JOIN line_name_creation lnc ON cc.line = lnc.id 
    JOIN users us ON FIND_IN_SET(c.insert_login_id, us.id)
    JOIN branch_creation bc ON us.branch = bc.id 
    JOIN users u ON FIND_IN_SET(cc.line, u.line) 
    LEFT JOIN (
        SELECT ace.user_id, ace.collection_amnt 
        FROM `accounts_collect_entry` ace 
        ORDER BY id DESC 
        LIMIT 1
    ) AS last_collection ON c.insert_login_id = last_collection.user_id 
    WHERE $cndtn 
    AND c.collection_date > COALESCE(
        (SELECT created_on FROM accounts_collect_entry WHERE user_id = u.id AND $cndtn ORDER BY id DESC LIMIT 1), 
        '1970-01-01 00:00:00'
    ) 
    AND c.collection_date <= NOW() 
    AND c.insert_login_id = u.id 
    GROUP BY u.id
),
second_query AS (
    SELECT 
        us.id as userid, 
        us.name, 
        ac.line, 
        ac.branch, 
        SUM(ac.no_of_bills) AS no_of_bills,  
        SUM(ac.collection_amnt) AS total_amount,
        '2' as type
    FROM `accounts_collect_entry` ac
    JOIN users us ON ac.user_id = us.id
    WHERE $cndtn 
    AND DATE(ac.created_on) = CURDATE() 
    AND ac.user_id NOT IN (
        SELECT userid 
        FROM first_query
    )
    GROUP BY us.id
)
SELECT userid, name, linename, branch_name, no_of_bills, total_amount, type 
FROM (
    SELECT * FROM first_query
    UNION ALL
    SELECT * FROM second_query
) AS subqry 
ORDER BY userid ASC; ");

if ($qry->rowCount() > 0) {
    while ($data = $qry->fetch(PDO::FETCH_ASSOC)) {
        $disabled = ($data['type'] == 2) ? 'disabled' : ''; // 1 - disabled; 2 - enabled;
        $data['total_amount'] = moneyFormatIndia($data['total_amount']);
        $data['action'] = "<a href='#' class='collect-money' value='" . $data['userid'] . "'><button class='btn btn-primary' " . $disabled . ">Collect</button></a> ";
        $collection_list_arr[] = $data;
    }
}

$pdo = null; // Close connection.
echo json_encode($collection_list_arr);
