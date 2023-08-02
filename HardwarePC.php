<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsHardwareAssets = 50;
$pageNum_rsHardwareAssets = 0;
if (isset($_GET['pageNum_rsHardwareAssets'])) {
  $pageNum_rsHardwareAssets = (int)$_GET['pageNum_rsHardwareAssets'];
}
$startRow_rsHardwareAssets = $pageNum_rsHardwareAssets * $maxRows_rsHardwareAssets;

// Connect to the database using mysqli
$mysqli = new mysqli("localhost", "your_username", "your_password", "your_database_name");
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$query_rsHardwareAssets = "SELECT * FROM assets_hardware WHERE assets_hardware.platform = 'PC' LIMIT ?, ?";
$stmt = $mysqli->prepare($query_rsHardwareAssets);
$stmt->bind_param("ii", $startRow_rsHardwareAssets, $maxRows_rsHardwareAssets);
$stmt->execute();
$rsHardwareAssets = $stmt->get_result();

if (isset($_GET['totalRows_rsHardwareAssets'])) {
  $totalRows_rsHardwareAssets = (int)$_GET['totalRows_rsHardwareAssets'];
} else {
  $query_rsHardwareAssetsCount = "SELECT COUNT(*) as total FROM assets_hardware WHERE assets_hardware.platform = 'PC'";
  $result = $mysqli->query($query_rsHardwareAssetsCount);
  $row = $result->fetch_assoc();
  $totalRows_rsHardwareAssets = (int)$row['total'];
}
$totalPages_rsHardwareAssets = ceil($totalRows_rsHardwareAssets / $maxRows_rsHardwareAssets) - 1;

// Other queries can be updated similarly using prepared statements

?>

<?php $pageTitle = "Hardware Assets List"; ?>
<?php include('includes/header.php'); ?>
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
        <tr onmouseover="this.bgColor='#F2F7FF'" onmouseout="this.bgColor='#FFFFFF';">
          <td><?php echo $row_rsHardwareAssets['asset_type']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['vendor']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['model']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['platform']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['location']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['status']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['user']; ?>&nbsp;</td>
          <td><?php echo $row_rsHardwareAssets['division']; ?>&nbsp;</td>
          <td><a href="HardwareDetail.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">View</a></td>
          <td><a href="HardwareUpdate.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">Edit</a></td>
        </tr>
      <?php } ?>
    </table>

    <table class="pagination">
      <tr>
	  <td>Records <?php echo ($startRow_rsHardwareAssets + 1) ?> to <?php echo min($startRow_rsHardwareAssets + $maxRows_rsHardwareAssets, $totalRows_rsHardwareAssets) ?> of <?php echo $totalRows_rsHardwareAssets ?></td>
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
    
    <?php include('includes/footer.php'); ?>
<?php
$stmt->close();
$mysqli->close();
?>
