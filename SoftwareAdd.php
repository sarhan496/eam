<?php
require_once('Connections/eam.php');

// Create a new MySQLi instance
$mysqli = new mysqli($hostname_eam, $username_eam, $password_eam, $database_eam);

// Check the connection
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    global $mysqli;
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $insertSQL = "INSERT INTO assets_software (asset, vendor, version, date_purchase, license_type, status, `user`, division, comments, platform, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertSQL);

    if (!$stmt) {
        die("Error preparing statement: " . $mysqli->error);
    }

    $asset = $_POST['asset'];
    $vendor = $_POST['vendor'];
    $version = $_POST['version'];
    $date_purchase = $_POST['date_purchase'];
    $license_type = $_POST['license_type'];
    $status = $_POST['status'];
    $user = $_POST['user'];
    $division = $_POST['division'];
    $comments = $_POST['comments'];
    $platform = $_POST['platform'];
    $location = $_POST['location'];

    $stmt->bind_param("sssssssssss", $asset, $vendor, $version, $date_purchase, $license_type, $status, $user, $division, $comments, $platform, $location);

    if ($stmt->execute()) {
        $stmt->close();
        $insertGoTo = "SoftwareList.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            $insertGoTo .= $_SERVER['QUERY_STRING'];
        }
        header("Location: " . $insertGoTo);
        exit;
    } else {
        die("Error inserting record: " . $mysqli->error);
    }
}

$query_rsSoftwareLicenseType = "SELECT * FROM assets_software_license";
$rsSoftwareLicenseType = $mysqli->query($query_rsSoftwareLicenseType);
$row_rsSoftwareLicenseType = $rsSoftwareLicenseType->fetch_assoc();
$totalRows_rsSoftwareLicenseType = $rsSoftwareLicenseType->num_rows;

$query_rsSoftwareVendors = "SELECT * FROM vendors_software";
$rsSoftwareVendors = $mysqli->query($query_rsSoftwareVendors);
$row_rsSoftwareVendors = $rsSoftwareVendors->fetch_assoc();
$totalRows_rsSoftwareVendors = $rsSoftwareVendors->num_rows;

$query_rsDivision = "SELECT * FROM division";
$rsDivision = $mysqli->query($query_rsDivision);
$row_rsDivision = $rsDivision->fetch_assoc();
$totalRows_rsDivision = $rsDivision->num_rows;

$query_rsLocation = "SELECT * FROM location";
$rsLocation = $mysqli->query($query_rsLocation);
$row_rsLocation = $rsLocation->fetch_assoc();
$totalRows_rsLocation = $rsLocation->num_rows;

$query_rsHardwareStatus = "SELECT * FROM assets_hardware_status ORDER BY assets_hardware_status ASC";
$rsHardwareStatus = $mysqli->query($query_rsHardwareStatus);
$row_rsHardwareStatus = $rsHardwareStatus->fetch_assoc();
$totalRows_rsHardwareStatus = $rsHardwareStatus->num_rows;

$query_rsSoftwarePlatform = "SELECT * FROM assets_software_platform";
$rsSoftwarePlatform = $mysqli->query($query_rsSoftwarePlatform);
$row_rsSoftwarePlatform = $rsSoftwarePlatform->fetch_assoc();
$totalRows_rsSoftwarePlatform = $rsSoftwarePlatform->num_rows;
?>

<?php $pageTitle="Add Software"; ?>
<?php include('includes/header.php'); ?>

<style>
    form {
        /* Your form styles */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ccc;
    }

    th {
        text-align: left;
    }

    .submit {
        text-align: center;
    }

    .submit input[type="submit"] {
        /* Your submit button styles */
    }
</style>

<form method="post" name="form1" action="<?php echo htmlspecialchars($editFormAction); ?>">
    <fieldset>
        <legend>Add Software</legend>
        <!-- Include the form elements for asset name, version, platform, vendor, etc. -->
        <table>
            <tr>
                <th><label for="Software Name">Software Name:</label></th>
                <td><input name="asset" type="text" size="32" maxlength="100" /></td>
            </tr>
            <tr>
                <th><label for="Version">Version:</label></th>
                <td><input name="version" type="text" size="10" maxlength="20" /></td>
            </tr>
            <tr>
                <th><label for="Platform">Platform:</label></th>
                <td>
                    <select name="platform">
                        <option value="">Choose</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsSoftwarePlatform['assets_software_type'] ?>"><?php echo $row_rsSoftwarePlatform['assets_software_type'] ?></option>
                            <?php
                        } while ($row_rsSoftwarePlatform = $rsSoftwarePlatform->fetch_assoc());
                        $rsSoftwarePlatform->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="Vendor">Vendor:</label></th>
                <td>
                    <select name="vendor">
                        <option value="">Choose</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsSoftwareVendors['vendor'] ?>"><?php echo $row_rsSoftwareVendors['vendor'] ?></option>
                            <?php
                        } while ($row_rsSoftwareVendors = $rsSoftwareVendors->fetch_assoc());
                        $rsSoftwareVendors->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="License type">License type:</label></th>
                <td>
                    <select name="license_type">
                        <option value="">Choose</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsSoftwareLicenseType['assets_software_license'] ?>"><?php echo $row_rsSoftwareLicenseType['assets_software_license'] ?></option>
                            <?php
                        } while ($row_rsSoftwareLicenseType = $rsSoftwareLicenseType->fetch_assoc());
                        $rsSoftwareLicenseType->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="Seats #">Seats #:</label></th>
                <td><input name="seats" type="text" size="10" maxlength="12" /></td>
            </tr>
            <tr>
                <th><label for="Date purchased">Date purchased:</label></th>
                <td>
                    <input type="date" name="date_purchase" value="" size="8" />
                    <img src='images/scw.gif' title='Click Here' alt='Click Here' onclick="cal.select(document.forms['form1'].date_purchase,'anchor2','MM/dd/yyyy'); return false;" name="anchor2" id="anchor2" style="cursor:hand" />
                    <hr />
                </td>
            </tr>
            <tr>
            <th><label for="status">Status:</label></th>
                <td>
                    <select name="status" id="status" required>
                        <option value="">- Select Status -</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsHardwareStatus['assets_hardware_status'] ?>"><?php echo $row_rsHardwareStatus['assets_hardware_status'] ?></option>
                            <?php
                        } while ($row_rsHardwareStatus = $rsHardwareStatus->fetch_assoc());
                        $rsHardwareStatus->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="User / Owner">User / Owner:</label></th>
                <td><input name="user" type="text" size="32" maxlength="50" /></td>
            </tr>
            <tr>
                <th><label for="Division">Division:</label></th>
                <td>
                    <select name="division">
                        <option value="">Choose</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsDivision['division'] ?>"><?php echo $row_rsDivision['division'] ?></option>
                            <?php
                        } while ($row_rsDivision = $rsDivision->fetch_assoc());
                        $rsDivision->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="Location">Location:</label></th>
                <td>
                    <select name="location">
                        <option value="">Choose</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsLocation['location'] ?>"><?php echo $row_rsLocation['location'] ?></option>
                            <?php
                        } while ($row_rsLocation = $rsLocation->fetch_assoc());
                        $rsLocation->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="Comments">Comments:</label></th>
                <td><textarea name="comments" cols="30"></textarea></td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" value="Insert record">
        </p>
    </fieldset>
    <input type="hidden" name="MM_insert" value="form1">
</form>


<?php include('includes/footer.php'); ?>
<?php
$rsSoftwareLicenseType->free_result();
$rsSoftwareVendors->free_result();
$rsDivision->free_result();
$rsLocation->free_result();
$rsSoftwarePlatform->free_result();
?>
