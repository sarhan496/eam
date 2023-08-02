<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan // 
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        $theValue = stripslashes($theValue);

        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE assets_hardware_type SET assets_hardware_type=%s, comments=%s WHERE id=%s",
        GetSQLValueString($_POST['assets_hardware_type'], "text", $eam),
        GetSQLValueString($_POST['comments'], "text", $eam),
        GetSQLValueString($_POST['id'], "int", $eam)
    );

    mysqli_select_db($eam, $database_eam);
    $Result1 = mysqli_query($eam, $updateSQL) or die(mysqli_error($eam));

    $updateGoTo = "HardwareTypeList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsHardwareType = "-1";
if (isset($_GET['recordID'])) {
    $colname_rsHardwareType = $_GET['recordID'];
}
mysqli_select_db($eam, $database_eam);
$query_rsHardwareType = sprintf("SELECT * FROM assets_hardware_type WHERE id = %s", GetSQLValueString($colname_rsHardwareType, "int", $eam));
$rsHardwareType = mysqli_query($eam, $query_rsHardwareType) or die(mysqli_error($eam));
$row_rsHardwareType = mysqli_fetch_assoc($rsHardwareType);
$totalRows_rsHardwareType = mysqli_num_rows($rsHardwareType);

$pageTitle = "Update Hardware Type";
include('includes/header.php');
?>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <fieldset>
        <legend>Update Hardware Type </legend>
        <p>
            <label for="Vendor ID">Hardware Type ID</label>
            <?php echo $row_rsHardwareType['id']; ?>
        </p>
        <p>
            <label for="Vendor">Hardware Type</label>
            <input type="text" name="assets_hardware_type" value="<?php echo $row_rsHardwareType['assets_hardware_type']; ?>" size="32">
        </p>
        <p>
            <label for="Comments">Comments</label>
            <textarea name="comments" cols="30" rows="3"><?php echo $row_rsHardwareType['comments']; ?></textarea>
        </p>
        <p class="submit">
            <input type="submit" value="Update record">
        </p>
    </fieldset>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_rsHardwareType['id']; ?>">
</form>

<?php include('includes/footer.php'); ?>
<?php
mysqli_free_result($rsHardwareType);
?>
