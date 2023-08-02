<?php
require_once('Connections/eam.php');

function GetSQLValueString($mysqli, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    $theValue = trim($theValue);

    if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = $mysqli->real_escape_string($theValue);

    switch ($theType) {
        case "text":
        case "date":
            $theValue = ($theValue !== "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue !== "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue !== "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue !== "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

if (isset($_GET['recordID']) && $_GET['recordID'] !== "") {
    $recordID = $_GET['recordID'];

    $deleteSQL = "DELETE FROM assets_software WHERE asset_software_id = ?";
    $stmt = $mysqli->prepare($deleteSQL);

    if (!$stmt) {
        die("Error preparing statement: " . $mysqli->error);
    }

    $stmt->bind_param("i", $recordID);

    if ($stmt->execute()) {
        $stmt->close();
        $deleteGoTo = "SoftwareList.php";
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

$pageTitle = "Delete Software";
include('includes/header.php');
?>
<h3>Software Delete </h3>
<form id="delRecord" name="delRecord" method="post" action="">
    <input type="submit" name="Submit" value="Delete Record" />
</form>
<?php include('includes/footer.php'); ?>
