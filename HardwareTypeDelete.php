<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan // 

// Create the database connection
$database_eam = new mysqli('localhost', 'root', '', 'eam');
if ($database_eam->connect_error) {
    die("Connection failed: " . $database_eam->connect_error);
}

function getSQLValueString($theValue, $theType, $database_eam)
{
    $theValue = stripslashes($theValue);

    $theValue = $database_eam->real_escape_string($theValue);

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
        default:
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
    }
    return $theValue;
}

if ((isset($_GET['recordID'])) && ($_GET['recordID'] != "")) {
    $deleteSQL = "DELETE FROM assets_hardware_type WHERE id=" . getSQLValueString($_GET['recordID'], "int", $database_eam);

    $result = $database_eam->query($deleteSQL);
    if (!$result) {
        die("Error: " . $database_eam->error);
    }

    $deleteGoTo = "HardwareTypeList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
        $deleteGoTo .= $_SERVER['QUERY_STRING'];
    }
    header("Location: $deleteGoTo");
    exit;
}
?>
<?php $pageTitle = "Delete Platform"; ?>
<?php include('includes/header.php'); ?>

<h3>Platform Delete</h3>
<form id="delRecord" name="delRecord" method="post" action="">
    <label>
        <input type="submit" name="Submit" value="Delete Record" />
    </label>
</form>
<?php include('includes/footer.php'); ?>
