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

// Query for asset type count
$query_rsAssetTypeCount = "SELECT asset_type, COUNT(*) as type_count FROM assets_hardware GROUP BY asset_type";
$result_rsAssetTypeCount = $eam->query($query_rsAssetTypeCount);

// Query for vendor count
$query_rsVendorCount = "SELECT vendor, COUNT(*) as vendor_count FROM assets_hardware GROUP BY vendor";
$result_rsVendorCount = $eam->query($query_rsVendorCount);

// Query for division count
$query_rsDivisionCount = "SELECT division, COUNT(*) as division_count FROM assets_hardware GROUP BY division";
$result_rsDivisionCount = $eam->query($query_rsDivisionCount);

// Query for platform count
$query_rsPlatformCount = "SELECT platform, COUNT(*) as platform_count FROM assets_hardware GROUP BY platform";
$result_rsPlatformCount = $eam->query($query_rsPlatformCount);

// ... (Rest of the code remains unchanged)

$query_rsCompanyName = "SELECT company_name FROM company";
$result_rsCompanyName = $eam->query($query_rsCompanyName);
$row_rsCompanyName = $result_rsCompanyName->fetch_assoc();

$pageTitle = "Home";
?>

<?php include('includes/header.php'); ?>
<h3><?php echo $row_rsCompanyName['company_name']; ?> - Home</h3>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard">

    <h2>Dashboard PUNB</h2>

    <!-- Bar Chart for Asset Type Count -->
    <h3>Asset Type Count</h3>
    <canvas id="assetTypeChart" width="400" height="200"></canvas>

    <!-- Bar Chart for Vendor Count -->
    <h3>Vendor Count</h3>
    <canvas id="vendorChart" width="400" height="200"></canvas>

    <!-- Pie Chart for Division Count -->
    <h3>Division Count</h3>
    <canvas id="divisionChart" width="400" height="200"></canvas>

    <!-- Pie Chart for Platform Count -->
    <h3>Platform Count</h3>
    <canvas id="platformChart" width="400" height="200"></canvas>

    <script>
        // Function to generate random colors
        function randomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16);
        }

        // Bar Chart for Asset Type Count
        var assetTypeCtx = document.getElementById("assetTypeChart").getContext("2d");
        var assetTypeChart = new Chart(assetTypeCtx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($assetTypeLabels); ?>,
                datasets: [{
                    label: "Asset Type Count",
                    data: <?php echo json_encode($assetTypeData); ?>,
                    backgroundColor: <?php echo json_encode($assetTypeColors); ?>
                }]
            },
            options: {
                title: {
                    text: "Asset Type Count"
                }
            }
        });

        // Bar Chart for Vendor Count
        var vendorCtx = document.getElementById("vendorChart").getContext("2d");
        var vendorChart = new Chart(vendorCtx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($vendorLabels); ?>,
                datasets: [{
                    label: "Vendor Count",
                    data: <?php echo json_encode($vendorData); ?>,
                    backgroundColor: <?php echo json_encode($vendorColors); ?>
                }]
            },
            options: {
                title: {
                    text: "Vendor Count"
                }
            }
        });

        // Pie Chart for Division Count
        var divisionCtx = document.getElementById("divisionChart").getContext("2d");
        var divisionChart = new Chart(divisionCtx, {
            type: "pie",
            data: {
                labels: <?php echo json_encode($divisionLabels); ?>,
                datasets: [{
                    label: "Division Count",
                    data: <?php echo json_encode($divisionData); ?>,
                    backgroundColor: <?php echo json_encode($divisionColors); ?>
                }]
            },
            options: {
                title: {
                    text: "Division Count"
                }
            }
        });

        // Pie Chart for Platform Count
        var platformCtx = document.getElementById("platformChart").getContext("2d");
        var platformChart = new Chart(platformCtx, {
            type: "pie",
            data: {
                labels: <?php echo json_encode($platformLabels); ?>,
                datasets: [{
                    label: "Platform Count",
                    data: <?php echo json_encode($platformData); ?>,
                    backgroundColor: <?php echo json_encode($platformColors); ?>
                }]
            },
            options: {
                title: {
                    text: "Platform Count"
                }
            }
        });
    </script>
</div>

<p>&nbsp;</p>

<table class="pagination">
    <!-- Rest of your HTML code goes here -->
</table>
<?php include('includes/footer.php'); ?>

<?php

$result_rsAssetTypeCount->free_result();
$result_rsVendorCount->free_result();
$result_rsDivisionCount->free_result();
$result_rsPlatformCount->free_result();
$result_rsCompanyName->free_result();
?>
