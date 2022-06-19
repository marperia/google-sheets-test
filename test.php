<?php
require_once 'connect_gsheets.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

$rename_cols = [
    13 => 'Electronic Total',
    21 => 'Print Total',
    32 => 'Direct Mail Total',
    52 => 'Fixed Internet Total',
    70 => 'New Vehicles Total',
    82 => 'Used Vehicles Total',
    90 => 'Used Vehicles - non Internet Total',
    107 => 'Store Specific Other Total',
];


const SQL = 'INSERT INTO `test`.`sheets` (`row`, `name`, `january`, `february`, `march`, `april`, `may`, `june`, `july`, `august`, `september`, `october`, `november`, `december`, `total`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)                                                                                           ON DUPLICATE KEY UPDATE `name` = ?, `january` = ?, `february` = ?, `march` = ?, `april` = ?, `may` = ?, `june` = ?, `july` = ?, `august` = ?, `september` = ?, `october` = ?, `november` = ?, `december` = ?, `total` = ?';

// Get the API client and construct the service object.
$client = getClient();
$service = new Google\Service\Sheets($client);

// Get DB creds from ENV
$host = getenv("DBHOST");
$user = getenv("DBUSER");
$pass = getenv("DBPASS");

try{
    $db = new mysqli($host, $user, $pass, 'test');
    $stmt = $db->prepare(SQL);
    $spreadsheetId = '10En6qNTpYNeY_YFTWJ_3txXzvmOA7UxSCrKfKCFfaRw';
    $range = 'HVW!A5:N107';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    // Start parsing from 5th (4 + 1) row
    $table_offset = 4;

    if (empty($values)) {
        print "No data found.\n";
    } else {
        $num_row = $table_offset;
        foreach ($values as $row) {
            $num_row++;
            // skip null-based table rows
            if (empty($row[0])) {
                continue;
            }

            // skip empty table rows
            if (empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4]) &&
                empty($row[5]) && empty($row[6]) && empty($row[7]) && empty($row[8]) &&
                empty($row[9]) && empty($row[10]) && empty($row[11]) && empty($row[12]) &&
                empty($row[13])) {
                continue;
            }

            // rename 'Total' columns
            if (array_key_exists($num_row, $rename_cols)) {
                $row[0] = $rename_cols[$num_row];
            }

            // remove '$' sign from a string
            for ($i = 1; $i <= 13; $i++) {
                $row[$i] = substr($row[$i], 1);
            }

            // bind row id, name and other data
            $stmt->bind_param('isdddddddddddddsddddddddddddd',
                $num_row, $row[0], $row[1], $row[2],
                $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10],
                $row[11], $row[12], $row[13], $row[0], $row[1], $row[2],
                $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10],
                $row[11], $row[12], $row[13]);
            $stmt->execute();
        }
    }
}
catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
}