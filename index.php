<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    global $eam;
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    $theValue = $eam->real_escape_string($theValue);

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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsAssets = 10;
$pageNum_rsAssets = 0;
if (isset($_GET['pageNum_rsAssets'])) {
    $pageNum_rsAssets = $_GET['pageNum_rsAssets'];
}
$startRow_rsAssets = $pageNum_rsAssets * $maxRows_rsAssets;

$query_rsAssets = "SELECT COUNT(*) FROM assets_hardware";
$result_rsAssets = $eam->query($query_rsAssets);
$row_rsAssets = $result_rsAssets->fetch_assoc();

$totalRows_rsAssets = $row_rsAssets['COUNT(*)'];
$totalPages_rsAssets = ceil($totalRows_rsAssets / $maxRows_rsAssets) - 1;

$maxRows_rsHardwareCount = 10;
$pageNum_rsHardwareCount = 0;
if (isset($_GET['pageNum_rsHardwareCount'])) {
    $pageNum_rsHardwareCount = $_GET['pageNum_rsHardwareCount'];
}
$startRow_rsHardwareCount = $pageNum_rsHardwareCount * $maxRows_rsHardwareCount;

$query_rsHardwareCount = "SELECT assets_hardware.asset_type, COUNT(*) FROM assets_hardware GROUP BY assets_hardware.asset_type";
$result_rsHardwareCount = $eam->query($query_rsHardwareCount);
$row_rsHardwareCount = $result_rsHardwareCount->fetch_assoc();

$totalRows_rsHardwareCount = $result_rsHardwareCount->num_rows;
$totalPages_rsHardwareCount = ceil($totalRows_rsHardwareCount / $maxRows_rsHardwareCount) - 1;

$query_rsCompanyName = "SELECT company_name FROM company";
$result_rsCompanyName = $eam->query($query_rsCompanyName);
$row_rsCompanyName = $result_rsCompanyName->fetch_assoc();

// Fetch data from the database for asset type count
$query_rsAssetTypeCount = "SELECT assets_hardware.asset_type, COUNT(*) AS asset_count FROM assets_hardware GROUP BY assets_hardware.asset_type";
$result_rsAssetTypeCount = $eam->query($query_rsAssetTypeCount);
$assetTypeData = array();
$assetCountData = array();
while ($row = $result_rsAssetTypeCount->fetch_assoc()) {
    $assetTypeData[] = $row['asset_type'];
    $assetCountData[] = (int) $row['asset_count'];
}




// Fetch data from the database for date of purchase count
// Original code for vendor data
$query_rsVendorCount = "SELECT assets_hardware.vendor, COUNT(*) as vendor_count FROM assets_hardware GROUP BY assets_hardware.vendor";
$result_rsVendorCount = $eam->query($query_rsVendorCount);
$purchaseDateData = array();
$purchaseCountData = array();
while ($row = $result_rsVendorCount->fetch_assoc()) {
    $purchaseDateData[] = $row['vendor'];
    $purchaseCountData[] = (int) $row['vendor_count'];
}

$pageTitle = "Home";
?>

<?php include('includes/header.php'); ?>
<h3><?php echo $row_rsCompanyName['company_name']; ?> - Home</h3>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Asset Type Count Dashboard -->
<div class="dashboard">
    <h2 style="background-color:Orange;">Asset Type Count</h2>
    <canvas id="assetTypeChart" width="800" height="200"></canvas>
    <script>
        var assetTypeData = <?php echo json_encode($assetTypeData); ?>;
        var assetCountData = <?php echo json_encode($assetCountData); ?>;
        var ctx = document.getElementById("assetTypeChart").getContext("2d");
        var assetTypeChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: assetTypeData,
                datasets: [{
                    label: "Asset Type",
                    data: assetCountData,
                    backgroundColor: ["#00bfff", "#ff0000", "#ffff00"]
                    // You can add more background colors if needed for more asset types
                }]
            },
            options: {
                title: {
                    text: "Asset Type Count"
                }
            }
        });
    </script>
</div>



<!-- ... Rest of your HTML code ... -->
<div class="dashboard">
    <h2>Vendor</h2>
    <canvas id="vendorChart" width="800" height="200"></canvas>
    <script>
        var ctx = document.getElementById("vendorChart").getContext("2d");
        var vendorChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: [
                    <?php
                    while ($row_rsVendorCount = $result_rsVendorCount->fetch_assoc()) {
                        echo '"' . $row_rsVendorCount['vendor'] . '",';
                    }
                    ?>
                ],
                datasets: [{
                    label: "Vendor Data",
                    data: [
                        <?php
                        $result_rsVendorCount->data_seek(0);
                        while ($row_rsVendorCount = $result_rsVendorCount->fetch_assoc()) {
                            echo $row_rsVendorCount['vendor_count'] . ',';
                        }
                        ?>
                    ],
                    backgroundColor: [
                        <?php
                        $result_rsVendorCount->data_seek(0);
                        while ($row_rsVendorCount = $result_rsVendorCount->fetch_assoc()) {
                            echo '"' . randomColor() . '",';
                        }
                        ?>
                    ]
                }]
            },
            options: {
                title: {
                    text: "Vendor Data Count"
                }
            }
        });
    </script>
</div>

<table class="pagination">
    <!-- Rest of your HTML code goes here -->
</table>
<?php include('includes/footerphp.'); ?>

<?php
$result_rsAssets->free_result();
$result_rsHardwareCount->free_result();
$result_rsCompanyName->free_result();
?>
