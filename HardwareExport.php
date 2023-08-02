<?php

$host = 'localhost'; // MYSQL database host address
$db = 'hmh_eam'; // MYSQL database name
$user = 'hmh_eam'; // MySQL Database user
$pass = 'be4sleep'; // MySQL Database password

// Connect to the database using mysqli
$link = new mysqli($host, $user, $pass, $db);

// Check if the connection was successful
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// Now you can use the $link variable to perform database operations.

// For example, you can fetch data from the database:
$sql = "SELECT * FROM your_table_name";
$result = $link->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Access data using $row['column_name']
        // For example: echo $row['id'] or echo $row['name']
    }
    $result->free(); // Free the result set
} else {
    echo "Error executing query: " . $link->error;
}

// Remember to close the database connection when you're done.
$link->close();

?>
