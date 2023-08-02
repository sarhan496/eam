<?php
require_once('Connections/eam.php');

// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    // Perform input validation and sanitization for required fields
    $requiredFields = array('assets_hardware_type', 'vendor', 'model', 'serialnumber', 'location', 'date_purchase', 'status', 'user', 'division', 'platform', 'comments', 'monitor_size', 'warranty', 'cube', 'field_address', 'user_account', 'asset_tag', 'purchase_order');

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            die("Error: " . ucwords(str_replace('_', ' ', $field)) . " is required.");
        }
    }

    // Connect to the database
    $mysqli = new mysqli("localhost", "root", "", "eam"); // Replace with your database details

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute the SQL statement
    $insertSQL = "INSERT INTO assets_hardware (asset_type, vendor, model, serialnumber, location, date_purchase, status, `user`, division, platform, comments, monitor_size, warranty, `cube`, field_address, user_account, asset_tag, purchase_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertSQL);

    if (!$stmt) {
        die("Error preparing statement: " . $mysqli->error);
    }

    $stmt->bind_param(
        "sssssssssssissssss",
        $_POST['assets_hardware_type'],
        $_POST['vendor'],
        $_POST['model'],
        $_POST['serialnumber'],
        $_POST['location'],
        $_POST['date_purchase'],
        $_POST['status'],
        $_POST['user'],
        $_POST['division'],
        $_POST['platform'],
        $_POST['comments'],
        $_POST['monitor_size'],
        $_POST['warranty'],
        $_POST['cube'],
        $_POST['field_address'],
        $_POST['user_account'],
        $_POST['asset_tag'],
        $_POST['purchase_order']
    );

    if ($stmt->execute()) {
        $stmt->close();
        $mysqli->close();
        $insertGoTo = "HardwareList.php";
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

$mysqli = new mysqli("localhost", "root", "", "eam"); // Replace with your database details

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query_rsVendors = "SELECT * FROM vendors ORDER BY vendor ASC";
$rsVendors = $mysqli->query($query_rsVendors);
$row_rsVendors = $rsVendors->fetch_assoc();
$totalRows_rsVendors = $rsVendors->num_rows;

$query_rsPlatform = "SELECT * FROM assets_hardware_platform ORDER BY platform ASC";
$rsPlatform = $mysqli->query($query_rsPlatform);
$row_rsPlatform = $rsPlatform->fetch_assoc();
$totalRows_rsPlatform = $rsPlatform->num_rows;

$query_rsHardwareType = "SELECT * FROM assets_hardware_type";
$rsHardwareType = $mysqli->query($query_rsHardwareType);
$row_rsHardwareType = $rsHardwareType->fetch_assoc();
$totalRows_rsHardwareType = $rsHardwareType->num_rows;

$query_rsDivision = "SELECT * FROM division ORDER BY division ASC";
$rsDivision = $mysqli->query($query_rsDivision);
$row_rsDivision = $rsDivision->fetch_assoc();
$totalRows_rsDivision = $rsDivision->num_rows;

$query_rsLocation = "SELECT * FROM location ORDER BY location ASC";
$rsLocation = $mysqli->query($query_rsLocation);
$row_rsLocation = $rsLocation->fetch_assoc();
$totalRows_rsLocation = $rsLocation->num_rows;

$query_rsHardwareStatus = "SELECT * FROM assets_hardware_status ORDER BY assets_hardware_status ASC";
$rsHardwareStatus = $mysqli->query($query_rsHardwareStatus);
$row_rsHardwareStatus = $rsHardwareStatus->fetch_assoc();
$totalRows_rsHardwareStatus = $rsHardwareStatus->num_rows;

$query_rsMonitorSize = "SELECT * FROM assets_hardware_monitor_size";
$rsMonitorSize = $mysqli->query($query_rsMonitorSize);
$row_rsMonitorSize = $rsMonitorSize->fetch_assoc();
$totalRows_rsMonitorSize = $rsMonitorSize->num_rows;
?>

<?php $pageTitle = "Add Hardware"; ?>
<?php include('includes/header.php'); ?>

<!-- Rest of the PHP code remains unchanged -->

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

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="form1">
    <fieldset>
        <legend>Add Hardware</legend>
        <!-- Include the form elements for asset type, vendor, model, serial number, location, etc. -->
        <table>
            <tr>
                <th><label for="assets_hardware_type">Asset Type:</label></th>
                <td>
                    <select name="assets_hardware_type" id="assets_hardware_type" required>
                        <option value="">- Select Asset Type -</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsHardwareType['assets_hardware_type'] ?>"><?php echo $row_rsHardwareType['assets_hardware_type'] ?></option>
                            <?php
                        } while ($row_rsHardwareType = $rsHardwareType->fetch_assoc());
                        $rsHardwareType->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
            <th><label for="vendor">Vendors:</label></th>
                <td>
                    <select name="vendor" id="vendor" required>
                        <option value="">- Select Vendors -</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsVendors['vendor'] ?>"><?php echo $row_rsVendors['vendor'] ?></option>
                            <?php
                        } while ($row_rsVendors = $rsVendors->fetch_assoc());
                        $rsVendors->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="model">Model:</label></th>
                <td><input type="text" name="model" id="model" required></td>
            </tr>
            <tr>
                <th><label for="serialnumber">Serial Number:</label></th>
                <td><input type="text" name="serialnumber" id="serialnumber" required></td>
            </tr>
            <tr>
                <th><label for="location">Location:</label></th>
                <td>
                    <select name="location" id="location" required>
                        <option value="">- Select Location -</option>
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
            <!-- Include more table rows (tr) for additional form elements -->
            <tr>
                <th><label for="date_purchase">Date Purchased:</label></th>
                <td><input type="date" name="date_purchase" id="date_purchase" required></td>
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
                <th><label for="user">User:</label></th>
                <td><input type="text" name="user" id="user" required></td>
            </tr>
            <tr>
            <th><label for="division">Division:</label></th>
                <td>
                    <select name="division" id="division" required>
                        <option value="">- Select Division -</option>
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
                <th><label for="platform">Platform:</label></th>
                <td>
                    <select name="platform" id="platform" required>
                        <option value="">- Select Platform -</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsPlatform['platform'] ?>"><?php echo $row_rsPlatform['platform'] ?></option>
                            <?php
                        } while ($row_rsPlatform = $rsPlatform->fetch_assoc());
                        $rsPlatform->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="comments">Comments:</label></th>
                <td><textarea name="comments" id="comments" cols="30" rows="5"></textarea></td>
            </tr>
            <tr>
                <th><label for="monitor_size">Monitor Size:</label></th>
                <td>
                    <select name="monitor_size" id="id" required>
                        <option value="">- Select Monitor Size -</option>
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $row_rsMonitorSize['id'] ?>"><?php echo $row_rsMonitorSize['id'] ?></option>
                            <?php
                        } while ($row_rsMonitorSize = $rsMonitorSize->fetch_assoc());
                        $rsMonitorSize->data_seek(0); // Reset the result set pointer
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="warranty">Warranty Date:</label></th>
                <td><input type="date" name="warranty" id="warranty" required></td>
            </tr>
            <tr>
                <th><label for="cube">Store:</label></th>
                <td><input type="text" name="cube" id="cube" required></td>
            </tr>
            <tr>
                <th><label for="field_address">Field User Address:</label></th>
                <td><textarea name="field_address" id="field_address" cols="30" rows="5"></textarea></td>
            </tr>
            <tr>
                <th><label for="user_account">User Account:</label></th>
                <td><input type="text" name="user_account" id="user_account" required></td>
            </tr>
            <tr>
                <th><label for="asset_tag">Asset Tag:</label></th>
                <td><input type="text" name="asset_tag" id="asset_tag" required></td>
            </tr>
            <tr>
                <th><label for="purchase_order">Purchase Order:</label></th>
                <td><input type="text" name="purchase_order" id="purchase_order" required></td>
            </tr>

            <!-- ... (add more table rows for additional data) ... -->
        </table>

        <p class="submit">
            <input type="submit" value="Add Asset">
        </p>
    </fieldset>
    <input type="hidden" name="MM_insert" value="form1">
</form>


<!-- Rest of the PHP code remains unchanged -->

<?php include('includes/footer.php'); ?>
