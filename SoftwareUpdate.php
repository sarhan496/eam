<?php
$hostname_eam = "localhost";
$database_eam = "eam";
$username_eam = "root";
$password_eam = "";
$conn_eam = new mysqli($hostname_eam, $username_eam, $password_eam, $database_eam);

// Check connection
if ($conn_eam->connect_error) {
    die("Connection failed: " . $conn_eam->connect_error);
}
?>
<?php // Enterprise Asset Management - Sarhan // 
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    global $conn_eam; // Use the correct connection variable

    $theValue = (!$conn_eam->real_escape_string) ? addslashes($theValue) : $conn_eam->real_escape_string($theValue);

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
    $updateSQL = sprintf("UPDATE assets_software SET asset=%s, vendor=%s, version=%s, date_purchase=%s, license_type=%s, status=%s, `user`=%s, division=%s, comments=%s, platform=%s, location=%s, seats=%s WHERE asset_software_id=%s",
        GetSQLValueString($_POST['asset'], "text"),
        GetSQLValueString($_POST['vendor'], "text"),
        GetSQLValueString($_POST['version'], "text"),
        GetSQLValueString($_POST['date_purchase'], "text"),
        GetSQLValueString($_POST['license_type'], "text"),
        GetSQLValueString($_POST['status'], "int"),
        GetSQLValueString($_POST['user'], "text"),
        GetSQLValueString($_POST['division'], "text"),
        GetSQLValueString($_POST['comments'], "text"),
        GetSQLValueString($_POST['platform'], "text"),
        GetSQLValueString($_POST['location'], "text"),
        GetSQLValueString($_POST['seats'], "int"),
        GetSQLValueString($_POST['asset_software_id'], "int"));

    $result = $conn_eam->query($updateSQL) or die($conn_eam->error);

    $updateGoTo = "SoftwareList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsSoftwareUpdate = "-1";
if (isset($_GET['recordID'])) {
    $colname_rsSoftwareUpdate = ($_GET['recordID']);
}
$query_rsSoftwareUpdate = sprintf("SELECT * FROM assets_software WHERE asset_software_id = %s", GetSQLValueString($colname_rsSoftwareUpdate, "int"));
$rsSoftwareUpdate = $conn_eam->query($query_rsSoftwareUpdate) or die($conn_eam->error);
$row_rsSoftwareUpdate = $rsSoftwareUpdate->fetch_assoc();
$totalRows_rsSoftwareUpdate = $rsSoftwareUpdate->num_rows;

$query_rsVendors = "SELECT * FROM vendors_software";
$rsVendors = $conn_eam->query($query_rsVendors) or die($conn_eam->error);
$row_rsVendors = $rsVendors->fetch_assoc();
$totalRows_rsVendors = $rsVendors->num_rows;

$query_rsLocation = "SELECT * FROM location";
$rsLocation = $conn_eam->query($query_rsLocation) or die($conn_eam->error);
$row_rsLocation = $rsLocation->fetch_assoc();
$totalRows_rsLocation = $rsLocation->num_rows;

$query_rsDivision = "SELECT * FROM division";
$rsDivision = $conn_eam->query($query_rsDivision) or die($conn_eam->error);
$row_rsDivision = $rsDivision->fetch_assoc();
$totalRows_rsDivision = $rsDivision->num_rows;

$query_rsSoftwarePlatform = "SELECT * FROM assets_software_platform";
$rsSoftwarePlatform = $conn_eam->query($query_rsSoftwarePlatform) or die($conn_eam->error);
$row_rsSoftwarePlatform = $rsSoftwarePlatform->fetch_assoc();
$totalRows_rsSoftwarePlatform = $rsSoftwarePlatform->num_rows;

$query_rsSoftwareLicense = "SELECT * FROM assets_software_license";
$rsSoftwareLicense = $conn_eam->query($query_rsSoftwareLicense) or die($conn_eam->error);
$row_rsSoftwareLicense = $rsSoftwareLicense->fetch_assoc();
$totalRows_rsSoftwareLicense = $rsSoftwareLicense->num_rows;
 
$pageTitle="Update Software Asset";
?>
<?php include('includes/header.php'); ?>	
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <fieldset>
        <legend>Software Update</legend>	
        <p>
            <label for="Asset Id">Asset Id</label>
            <?php echo $row_rsSoftwareUpdate['asset_software_id']; ?>
        </p>
        <p>
            <label for="Software Name">Software Name</label>
            <input type="text" name="asset" value="<?php echo $row_rsSoftwareUpdate['asset']; ?>" size="32">
        </p>
        <p>
            <label for="Version">Version</label>
            <input type="text" name="version" value="<?php echo $row_rsSoftwareUpdate['version']; ?>" size="32" />
        </p>
        <p>
            <label for="Platform">Platform</label>
            <select name="platform">
                <?php 
                do {  
                ?>
                <option value="<?php echo $row_rsSoftwarePlatform['assets_software_type']?>" <?php if (!(strcmp($row_rsSoftwarePlatform['assets_software_type'], $row_rsSoftwareUpdate['platform']))) {echo "SELECTED";} ?>><?php echo $row_rsSoftwarePlatform['assets_software_type']?></option>
                <?php
                } while ($row_rsSoftwarePlatform = $rsSoftwarePlatform->fetch_assoc());
                ?>
            </select>
        </p>
        <p>
            <label for="Vendor">Vendor</label>
            <select name="vendor">
                <?php 
                do {  
                ?>
                <option value="<?php echo $row_rsVendors['vendor']?>" <?php if (!(strcmp($row_rsVendors['vendor'], $row_rsSoftwareUpdate['vendor']))) {echo "SELECTED";} ?>><?php echo $row_rsVendors['vendor']?></option>
                <?php
                } while ($row_rsVendors = $rsVendors->fetch_assoc());
                ?>
            </select>
        </p>
        <p>
            <label for="License type">License type</label>
            <select name="license_type">
                <?php 
                do {  
                ?>
                <option value="<?php echo $row_rsSoftwareLicense['assets_software_license']?>" <?php if (!(strcmp($row_rsSoftwareLicense['assets_software_license'], $row_rsSoftwareUpdate['license_type']))) {echo "SELECTED";} ?>><?php echo $row_rsSoftwareLicense['assets_software_license']?></option>
                <?php
                } while ($row_rsSoftwareLicense = $rsSoftwareLicense->fetch_assoc());
                ?>
            </select>
        </p>
        <p>
            <label for="Seats #">Seats #</label>
            <input type="text" name="seats" value="<?php echo $row_rsSoftwareUpdate['seats']; ?>" size="10" />
        </p>
        <p>
            <label for="Date purchased">Date purchased</label>
            <input type="text" name="date_purchase" value="<?php echo $row_rsSoftwareUpdate['date_purchase']; ?>" size="8">
            <img src='images/scw.gif' title='Click Here' alt='Click Here'onclick="cal.select(document.forms['form1'].date_purchase,'anchor3','MM/dd/yyyy'); return false;" name="anchor3" id="anchor3" style="cursor:hand" />

        </p>

        <hr />

        <p>
            <label for="Status">Status</label>
            <input type="text" name="status" value="<?php echo $row_rsSoftwareUpdate['status']; ?>" size="32">
        </p>
        <p>
            <label for="User / Owner">User / Owner</label>
            <input type="text" name="user" value="<?php echo $row_rsSoftwareUpdate['user']; ?>" size="32">
        </p>
        <p>
            <label for="Location">Location</label>
            <select name="location">
                <?php 
                do {  
                ?>
                <option value="<?php echo $row_rsLocation['location']?>" <?php if (!(strcmp($row_rsLocation['location'], $row_rsSoftwareUpdate['location']))) {echo "SELECTED";} ?>><?php echo $row_rsLocation['location']?></option>
                <?php
                } while ($row_rsLocation = $rsLocation->fetch_assoc());
                ?>
            </select>
        </p>
        <p>
            <label for="Division">Division</label>
            <select name="division">
                <?php 
                do {  
                ?>
                <option value="<?php echo $row_rsDivision['division']?>" <?php if (!(strcmp($row_rsDivision['division'], $row_rsSoftwareUpdate['division']))) {echo "SELECTED";} ?>><?php echo $row_rsDivision['division']?></option>
                <?php
                } while ($row_rsDivision = $rsDivision->fetch_assoc());
                ?>
            </select>
        </p>
        <p>
            <label for="Model">Comments</label>
            <textarea name="comments" cols="30" rows="3"><?php echo $row_rsSoftwareUpdate['comments']; ?></textarea>
        </p>
        <p class="submit">

            <input type="submit" value="Update record" />
        </p>
    </fieldset>

    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="asset_software_id" value="<?php echo $row_rsSoftwareUpdate['asset_software_id']; ?>">
</form>
<?php include('includes/footer.php'); ?>
<?php
$rsSoftwareUpdate->free_result();
$rsVendors->free_result();
$rsLocation->free_result();
$rsDivision->free_result();
$rsSoftwarePlatform->free_result();
$rsSoftwareLicense->free_result();
?>
