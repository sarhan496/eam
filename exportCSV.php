<?php
$host = 'localhost';
$user = 'hmh_eam';
$pass = 'be4sleep';
$db = 'hmh_eam';
$table = 'assets_hardware';
$file = 'assets';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die("Can not connect: " . $mysqli->connect_error);
}

$result = $mysqli->query("SHOW COLUMNS FROM " . $table);
$i = 0;
$csv_output = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $csv_output .= $row['Field'] . "; ";
        $i++;
    }
}

$csv_output .= "\n";

$values = $mysqli->query("SELECT * FROM " . $table);
while ($rowr = $values->fetch_row()) {
    for ($j = 0; $j < $i; $j++) {
        $csv_output .= $rowr[$j] . "; ";
    }
    $csv_output .= "\n";
}

$filename = $file . "_" . date("Y-m-d_H-i", time());
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=" . $filename . ".csv");
print $csv_output;
exit;
?>
