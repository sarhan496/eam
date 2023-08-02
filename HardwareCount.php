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

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
    $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
    $colname_DetailRS1 = $_GET['recordID'];
}

$mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query_DetailRS1 = "SELECT assets_hardware.asset_type, COUNT(*) AS total_count FROM assets_hardware WHERE asset_type = ?";
$stmt = $mysqli->prepare($query_DetailRS1);
$stmt->bind_param("s", $colname_DetailRS1);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error executing query: " . $mysqli->error);
}

$row_DetailRS1 = $result->fetch_assoc();

$totalRows_DetailRS1 = $result->num_rows;
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1 / $maxRows_DetailRS1) - 1;

?>

<table border="1" align="center">
    <tr>
        <td>asset_type</td>
        <td><?php echo $row_DetailRS1['asset_type']; ?> </td>
    </tr>
    <tr>
        <td>COUNT(*)</td>
        <td><?php echo $row_DetailRS1['total_count']; ?> </td>
    </tr>
</table>

<?php
$stmt->close();
$mysqli->close();
?>
