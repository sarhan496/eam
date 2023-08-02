<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 

function GetSQLValueString($mysqli, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    $theValue = (!$mysqli->real_escape_string) ? addslashes($theValue) : $mysqli->real_escape_string($theValue);

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
    return $theValue;
}

if (isset($_GET['recordID']) && $_GET['recordID'] != "") {
    $recordID = $_GET['recordID'];
    $mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $deleteSQL = "DELETE FROM assets_hardware WHERE asset_hardware_id = ?";
    $stmt = $mysqli->prepare($deleteSQL);
    $stmt->bind_param("i", $recordID);

    if ($stmt->execute()) {
        $stmt->close();
        $mysqli->close();
        $deleteGoTo = "HardwareList.php";
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
?>

<?php
$maxRows_DetailRS1 = 50;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
    $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT * FROM assets_hardware WHERE asset_hardware_id = ?";
$stmt = $mysqli->prepare($query_DetailRS1);
$stmt->bind_param("i", $recordID);
$stmt->execute();
$DetailRS1 = $stmt->get_result();
$row_DetailRS1 = $DetailRS1->fetch_assoc();

if (isset($_GET['totalRows_DetailRS1'])) {
    $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
    $queryAll_DetailRS1 = "SELECT * FROM assets_hardware";
    $all_DetailRS1 = $mysqli->query($queryAll_DetailRS1);
    $totalRows_DetailRS1 = $all_DetailRS1->num_rows;
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1 / $maxRows_DetailRS1) - 1;
$stmt->close();
$mysqli->close();
?>

<?php $pageTitle = "Delete Hardware"; ?>
<?php include('includes/header.php'); ?>
<h3>Hardware Delete </h3>
<form id="delRecord" name="delRecord" method="post" action="">
    <label>
        <input type="submit" name="Submit" value="Delete Record" />
    </label>
</form>
<?php include('includes/footer.php'); ?>
