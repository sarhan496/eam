<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    $mysqli = new mysqli("localhost", "YourDBUsername", "YourDBPassword", "eam"); // Replace the credentials with your database details

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $theValue = $mysqli->real_escape_string($theValue);

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
    }

    $mysqli->close();
    return $theValue;
}

if ((isset($_GET['recordID'])) && ($_GET['recordID'] != "")) {
    $mysqli = new mysqli("localhost", "YourDBUsername", "YourDBPassword", "eam"); // Replace the credentials with your database details

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $deleteSQL = sprintf("DELETE FROM division WHERE id=%s", GetSQLValueString($_GET['recordID'], "int"));

    if ($mysqli->query($deleteSQL) === TRUE) {
        $mysqli->close();
        $deleteGoTo = "DivisionList.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
            $deleteGoTo .= $_SERVER['QUERY_STRING'];
        }
        header("Location: " . $deleteGoTo);
        exit;
    } else {
        die("Error deleting record: " . $mysqli->error);
    }
}

$pageTitle = "Delete Division";
?>
<?php include('includes/header.php'); ?>
    <h3>Division Delete</h3>
    <form id="delRecord" name="delRecord" method="post" action="">
        <input type="submit" name="Submit" value="Delete Record" />
    </form>
<?php include('includes/footer.php'); ?>
