<?php
require_once('Connections/eam.php');
// Enterprise Asset Management - Sarhan //

$currentPage = $_SERVER["PHP_SELF"];
$maxRows_rsHardwareAssets = 50;
$pageNum_rsHardwareAssets = 0;

if (isset($_GET['pageNum_rsHardwareAssets'])) {
  $pageNum_rsHardwareAssets = intval($_GET['pageNum_rsHardwareAssets']);
}

$startRow_rsHardwareAssets = $pageNum_rsHardwareAssets * $maxRows_rsHardwareAssets;

$mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query_rsHardwareAssets = "SELECT * FROM assets_hardware WHERE assets_hardware.status = 'Deployed' ";
$query_limit_rsHardwareAssets = $query_rsHardwareAssets . " LIMIT ?, ?";
$stmt = $mysqli->prepare($query_limit_rsHardwareAssets);
$stmt->bind_param("ii", $startRow_rsHardwareAssets, $maxRows_rsHardwareAssets);
$stmt->execute();
$rsHardwareAssets = $stmt->get_result();

if (isset($_GET['totalRows_rsHardwareAssets'])) {
  $totalRows_rsHardwareAssets = intval($_GET['totalRows_rsHardwareAssets']);
} else {
  $queryAll_rsHardwareAssets = "SELECT * FROM assets_hardware WHERE assets_hardware.status = 'Deployed'";
  $all_rsHardwareAssets = $mysqli->query($queryAll_rsHardwareAssets);
  $totalRows_rsHardwareAssets = $all_rsHardwareAssets->num_rows;
}
$totalPages_rsHardwareAssets = ceil($totalRows_rsHardwareAssets / $maxRows_rsHardwareAssets) - 1;

$query_rsVendors = "SELECT * FROM vendors";
$rsVendors = $mysqli->query($query_rsVendors);
$row_rsVendors = $rsVendors->fetch_assoc();
$totalRows_rsVendors = $rsVendors->num_rows;

$query_rsPlatform = "SELECT * FROM assets_hardware_platform";
$rsPlatform = $mysqli->query($query_rsPlatform);
$row_rsPlatform = $rsPlatform->fetch_assoc();
$totalRows_rsPlatform = $rsPlatform->num_rows;

$colname_rsHardwareUpdate = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsHardwareUpdate = intval($_GET['recordID']);
}

$query_rsHardwareUpdate = "SELECT * FROM assets_hardware WHERE asset_hardware_id = ?";
$stmt = $mysqli->prepare($query_rsHardwareUpdate);
$stmt->bind_param("i", $colname_rsHardwareUpdate);
$stmt->execute();
$rsHardwareUpdate = $stmt->get_result();
$row_rsHardwareUpdate = $rsHardwareUpdate->fetch_assoc();
$totalRows_rsHardwareUpdate = $rsHardwareUpdate->num_rows;

$query_rsDivision = "SELECT * FROM division";
$rsDivision = $mysqli->query($query_rsDivision);
$row_rsDivision = $rsDivision->fetch_assoc();
$totalRows_rsDivision = $rsDivision->num_rows;

$query_rsHardwareType = "SELECT * FROM assets_hardware_type";
$rsHardwareType = $mysqli->query($query_rsHardwareType);
$row_rsHardwareType = $rsHardwareType->fetch_assoc();
$totalRows_rsHardwareType = $rsHardwareType->num_rows;

$query_rsLocation = "SELECT * FROM location";
$rsLocation = $mysqli->query($query_rsLocation);
$row_rsLocation = $rsLocation->fetch_assoc();
$totalRows_rsLocation = $rsLocation->num_rows;

$query_rsHardwareStatus = "SELECT * FROM assets_hardware_status";
$rsHardwareStatus = $mysqli->query($query_rsHardwareStatus);
$row_rsHardwareStatus = $rsHardwareStatus->fetch_assoc();
$totalRows_rsHardwareStatus = $rsHardwareStatus->num_rows;

$queryString_rsHardwareAssets = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsHardwareAssets") == false && 
        stristr($param, "totalRows_rsHardwareAssets") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsHardwareAssets = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsHardwareAssets = sprintf("&totalRows_rsHardwareAssets=%d%s", $totalRows_rsHardwareAssets, $queryString_rsHardwareAssets);

$pageTitle = "Hardware Assets List";
include('includes/header.php');
?>
<h3>Hardware Assets</h3>
<table class="table1">
  <tr>
    <th>Asset Type</th>
    <th>Vendor</th>
    <th>Model</th>
    <th>Platform</th>
    <th>Location</th>
    <th>Status</th>
    <th>User</th>
    <th>Division</th>
    <th>View</th>
    <th>Edit</th>
  </tr>
  <?php while ($row_rsHardwareAssets = $rsHardwareAssets->fetch_assoc()) { ?>
    <tr onmouseover="this.bgColor='#F2F7FF'" onmouseout="this.bgColor='#FFFFFF'";>
      <td><?php echo $row_rsHardwareAssets['asset_type']; ?></td>
      <td><?php echo $row_rsHardwareAssets['vendor']; ?></td>
      <td><?php echo $row_rsHardwareAssets['model']; ?></td>
      <td><?php echo $row_rsHardwareAssets['platform']; ?></td>
      <td><?php echo $row_rsHardwareAssets['location']; ?></td>
      <td><?php echo $row_rsHardwareAssets['status']; ?></td>
      <td><?php echo $row_rsHardwareAssets['user']; ?></td>
      <td><?php echo $row_rsHardwareAssets['division']; ?></td>
      <td><a href="HardwareDetail.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">View</a></td>
      <td><a href="HardwareUpdate.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">Edit</a></td>
    </tr>
  <?php } ?>
</table>
<table class="pagination">
  <tr>
    <td>Records <?php echo ($startRow_rsHardwareAssets + 1); ?> to <?php echo min($startRow_rsHardwareAssets + $maxRows_rsHardwareAssets, $totalRows_rsHardwareAssets); ?> of <?php echo $totalRows_rsHardwareAssets; ?></td>
    <td>
      <table>
        <tr>
          <?php if ($pageNum_rsHardwareAssets > 0) { // Show if not first page ?>
            <td width="23%" align="center">
              <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, 0, $queryString_rsHardwareAssets); ?>">First</a>
            </td>
          <?php } // Show if not first page ?>

          <?php if ($pageNum_rsHardwareAssets > 0) { // Show if not first page ?>		
            <td width="31%" align="center">
              <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, max(0, $pageNum_rsHardwareAssets - 1), $queryString_rsHardwareAssets); ?>">Previous</a>
            </td>
          <?php } // Show if not first page ?>


          <?php if ($pageNum_rsHardwareAssets < $totalPages_rsHardwareAssets) { // Show if not last page ?>
            <td width="23%" align="center">
              <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, min($totalPages_rsHardwareAssets, $pageNum_rsHardwareAssets + 1), $queryString_rsHardwareAssets); ?>">Next</a>
            </td>
          <?php } // Show if not last page ?>

          <?php if ($pageNum_rsHardwareAssets < $totalPages_rsHardwareAssets) { // Show if not last page ?>
            <td width="23%" align="center">
              <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, $totalPages_rsHardwareAssets, $queryString_rsHardwareAssets); ?>">Last</a>
            </td>
          <?php } // Show if not last page ?>		
        </tr>
      </table>
    </td>
  </tr>
</table>

<?php
$rsHardwareAssets->free_result();
$rsVendors->free_result();
$rsPlatform->free_result();
$stmt->close();
$rsDivision->free_result();
$rsHardwareType->free_result();
$rsLocation->free_result();
$rsHardwareStatus->free_result();
$mysqli->close();

include('includes/footer.php');
?>
