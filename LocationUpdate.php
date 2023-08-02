<?php
require_once('Connections/eam.php');

// Function to handle SQL value processing
function GetSQLValueString($conn, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    $theValue = (!$conn->get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE location SET location=%s, comments=%s WHERE id=%s",
        GetSQLValueString($eam, $_POST['location'], "text"),
        GetSQLValueString($eam, $_POST['comments'], "text"),
        GetSQLValueString($eam, $_POST['id'], "int")
    );

    $Result1 = $eam->query($updateSQL);
    if (!$Result1) {
        die("Error: " . $eam->error);
    }

    $updateGoTo = "LocationList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsLocationList = "-1";
if (isset($_GET['recordID'])) {
    $colname_rsLocationList = ($_GET['recordID']);
}

$query_rsLocationList = sprintf("SELECT * FROM location WHERE id = %s", GetSQLValueString($eam, $colname_rsLocationList, "int"));
$rsLocationList = $eam->query($query_rsLocationList);
if (!$rsLocationList) {
    die("Error: " . $eam->error);
}
$row_rsLocationList = $rsLocationList->fetch_assoc();
$totalRows_rsLocationList = $rsLocationList->num_rows;
?>

<?php $pageTitle = "Update Location"; ?>
<?php include('includes/header.php'); ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <fieldset>
        <legend>Update Location</legend>
        <p>
            <label for="Vendor ID">Location ID</label>
            <?php echo $row_rsLocationList['id']; ?>
        </p>
        <p>
            <label for="Vendor">Location</label>
            <input type="text" name="location" value="<?php echo $row_rsLocationList['location']; ?>" size="32">
        </p>
        <p>
            <label for="Comments">Comments</label>
            <textarea name="comments" cols="30" rows="3"><?php echo $row_rsLocationList['comments']; ?></textarea>
        </p>
        <p class="submit">
            <input type="submit" value="Update record">
        </p>
    </fieldset>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_rsLocationList['id']; ?>">
</form>
<?php include('includes/footer.php'); ?>

<?php
$rsLocationList->free_result();
?>
