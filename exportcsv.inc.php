<?php
require 'includes/dbConnecExportCSV.php'; 

$table = "assets_hardware"; // this is the tablename that you want to export to csv from mysql.

exportMysqlToCsv($table);

function exportMysqlToCsv($table, $file = 'export')
{
    $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";

    // Gets the data from the database
    $mysqli = new mysqli($hostname, $username, $password, $database);
    if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    $sql_query = "SELECT * FROM $table WHERE asset_type = 'laptop'";
    $result = $mysqli->query($sql_query);

    if (!$result) {
        die("Error executing query: " . $mysqli->error);
    }

    $fields_cnt = $result->field_count;
    $schema_insert = '';
    for ($i = 0; $i < $fields_cnt; $i++) {
        $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $result->fetch_field_direct($i)->name) . $csv_enclosed;
        $schema_insert .= $l;
        $schema_insert .= $csv_separator;
    } // end for
    $out = trim(substr($schema_insert, 0, -1));
    $out .= $csv_terminated;

    // Format the data
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $schema_insert = '';
        foreach ($row as $value) {
            if ($value == '0' || $value != '') {
                if ($csv_enclosed == '') {
                    $schema_insert .= $value;
                } else {
                    $schema_insert .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $value) . $csv_enclosed;
                }
            } else {
                $schema_insert .= '';
            }
            $schema_insert .= $csv_separator;
        } // end for
        $out .= $schema_insert;
        $out .= $csv_terminated;
    } // end while

    $result->free_result();
    $mysqli->close();

    $filename = $file . "_" . date("Y-m-d_H-i", time());
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
    header("Content-type: text/x-csv");
    header("Content-disposition: attachment; filename=" . $filename . ".csv");

    echo $out;
    exit;
}
?>
