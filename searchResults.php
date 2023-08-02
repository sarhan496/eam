<?php
// Require the database connection file
require_once('Connections/eam.php');

// Function to sanitize user input and prevent SQL injection
function sanitizeString($conn, $value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    $value = mysqli_real_escape_string($conn, $value);
    return $value;
}

// Replace "hostname", "username", "password", and "database_name" with your actual database credentials
$database_eam = mysqli_connect("localhost", "root", "", "eam");
if (!$database_eam) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Enterprise Asset Management - Graham Fisk - BigSmallweb.com - 2013 // 
$maxRows_rsHardwareAssets = 50;
$pageNum_rsHardwareAssets = 0;
if (isset($_GET['pageNum_rsHardwareAssets'])) {
    $pageNum_rsHardwareAssets = $_GET['pageNum_rsHardwareAssets'];
}
$startRow_rsHardwareAssets = $pageNum_rsHardwareAssets * $maxRows_rsHardwareAssets;

$varModel_rsHardwareAssets = "-1";
if (isset($_POST['model'])) {
    $varModel_rsHardwareAssets = sanitizeString($database_eam, $_POST['model']);
}
$varUser_rsHardwareAssets = "-1";
if (isset($_POST['user'])) {
    $varUser_rsHardwareAssets = sanitizeString($database_eam, $_POST['user']);
}
$varUserAccount_rsHardwareAssets = "-1";
if (isset($_POST['user_account'])) {
    $varUserAccount_rsHardwareAssets = sanitizeString($database_eam, $_POST['user_account']);
}
$varLocation_rsHardwareAssets = "-1";
if (isset($_POST['location'])) {
    $varLocation_rsHardwareAssets = sanitizeString($database_eam, $_POST['location']);
}
$varSerialnumber_rsHardwareAssets = "-1";
if (isset($_POST['serialnumber'])) {
    $varSerialnumber_rsHardwareAssets = sanitizeString($database_eam, $_POST['serialnumber']);
}
$varAssettype_rsHardwareAssets = "-1";
if (isset($_POST['asset_type'])) {
    $varAssettype_rsHardwareAssets = sanitizeString($database_eam, $_POST['asset_type']);
}
$varVendor_rsHardwareAssets = "-1";
if (isset($_POST['vendor'])) {
    $varVendor_rsHardwareAssets = sanitizeString($database_eam, $_POST['vendor']);
}
$varPlatform_rsHardwareAssets = "-1";
if (isset($_POST['platform'])) {
    $varPlatform_rsHardwareAssets = sanitizeString($database_eam, $_POST['platform']);
}
$varAssetTag_rsHardwareAssets = "-1";
if (isset($_POST['asset_tag'])) {
    $varAssetTag_rsHardwareAssets = sanitizeString($database_eam, $_POST['asset_tag']);
}
$varPurchaseOrder_rsHardwareAssets = "-1";
if (isset($_POST['purchase_order'])) {
    $varPurchaseOrder_rsHardwareAssets = sanitizeString($database_eam, $_POST['purchase_order']);
}

mysqli_select_db($database_eam, "eam") or die("Database selection failed: " . mysqli_error($database_eam));

$query_rsHardwareAssets = "SELECT * FROM assets_hardware WHERE 
    vendor = '$varVendor_rsHardwareAssets' OR
    platform = '$varPlatform_rsHardwareAssets' OR
    asset_type = '$varAssettype_rsHardwareAssets' OR
    serialnumber = '$varSerialnumber_rsHardwareAssets' OR
    user = '$varUser_rsHardwareAssets' OR
    user_account = '$varUserAccount_rsHardwareAssets' OR
    location = '$varLocation_rsHardwareAssets' OR
    model = '$varModel_rsHardwareAssets' OR
    asset_tag = '$varAssetTag_rsHardwareAssets' OR
    purchase_order = '$varPurchaseOrder_rsHardwareAssets' 
    ORDER BY assets_hardware.`user`";

$rsHardwareAssets = mysqli_query($database_eam, $query_rsHardwareAssets) or die(mysqli_error($database_eam));
$row_rsHardwareAssets = mysqli_fetch_assoc($rsHardwareAssets);

$totalRows_rsHardwareAssets = mysqli_num_rows($rsHardwareAssets);
$totalPages_rsHardwareAssets = ceil($totalRows_rsHardwareAssets / $maxRows_rsHardwareAssets) - 1;

$pageTitle = "Search";
include('includes/header.php');
$currentPage = $_SERVER["PHP_SELF"];
?>

<!-- Rest of the code remains the same -->


<!-- Rest of the code above remains the same -->


<h3>Search Assets</h3>
<table class="table1">
    <tr>
        <th>Purchase Order</th>
        <th>Asset Tag</th>
        <th>Serial Number</th>
        <th>Vendor</th>
        <th>Platform</th>
        <th>Type</th>
        <th>Model</th>
        <th>Status</th>
        <th>Location</th>
        <th>User</th>
        <th>User Account</th>
        <th>View</th>
        <th>Edit</th>
    </tr>
    <?php
    if ($totalRows_rsHardwareAssets > 0) {
        do {
            ?>
            <tr onmouseover="this.bgColor='#F2F7FF'" onmouseout="this.bgColor='#FFFFFF';">
                <td><?php echo $row_rsHardwareAssets['purchase_order']; ?></td>
                <td><?php echo $row_rsHardwareAssets['asset_tag']; ?></td>
                <td><?php echo $row_rsHardwareAssets['serialnumber']; ?></td>
                <td><?php echo $row_rsHardwareAssets['vendor']; ?></td>
                <td><?php echo $row_rsHardwareAssets['platform']; ?></td>
                <td><?php echo $row_rsHardwareAssets['asset_type']; ?></td>
                <td><?php echo $row_rsHardwareAssets['model']; ?></td>
                <td><?php echo $row_rsHardwareAssets['status']; ?></td>
                <td><?php echo $row_rsHardwareAssets['location']; ?></td>
                <td><?php echo $row_rsHardwareAssets['user']; ?></td>
                <td><?php echo $row_rsHardwareAssets['user_account']; ?></td>
                <td><a href="HardwareDetail.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">View</a></td>
                <td><a href="HardwareUpdate.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">Edit</a></td>
            </tr>
        <?php } while ($row_rsHardwareAssets = mysqli_fetch_assoc($rsHardwareAssets));
    } else {
        // If no results are found, display a message or take appropriate action
        echo '<tr><td colspan="13">No results found.</td></tr>';
    }
    ?>
</table>
<table class="pagination">
    <!-- Rest of the code below remains the same -->
</table>
<?php include('includes/footer.php'); ?>
<?php mysqli_free_result($rsHardwareAssets); ?>
