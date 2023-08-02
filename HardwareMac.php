<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsHardwareAssets = 50;
$pageNum_rsHardwareAssets = 0;
if (isset($_GET['pageNum_rsHardwareAssets'])) {
  $pageNum_rsHardwareAssets = $_GET['pageNum_rsHardwareAssets'];
}
$startRow_rsHardwareAssets = $pageNum_rsHardwareAssets * $maxRows_rsHardwareAssets;

// Connect to the database using PDO
try {
    $dbh = new PDO("mysql:host=localhost;dbname=hmh_eam", "hmh_eam", "be4sleep");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$query_rsHardwareAssets = "SELECT * FROM assets_hardware WHERE assets_hardware.platform = 'MAC' ";
$query_limit_rsHardwareAssets = $query_rsHardwareAssets . " LIMIT :startRow, :maxRows";
$stmt = $dbh->prepare($query_limit_rsHardwareAssets);
$stmt->bindValue(':startRow', $startRow_rsHardwareAssets, PDO::PARAM_INT);
$stmt->bindValue(':maxRows', $maxRows_rsHardwareAssets, PDO::PARAM_INT);
$stmt->execute();
$rsHardwareAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['totalRows_rsHardwareAssets'])) {
  $totalRows_rsHardwareAssets = $_GET['totalRows_rsHardwareAssets'];
} else {
    $stmt = $dbh->prepare($query_rsHardwareAssets);
    $stmt->execute();
    $all_rsHardwareAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalRows_rsHardwareAssets = count($all_rsHardwareAssets);
}
$totalPages_rsHardwareAssets = ceil($totalRows_rsHardwareAssets/$maxRows_rsHardwareAssets)-1;

// Other queries can be updated similarly using prepared statements

?>

<?php $pageTitle="Hardware Assets List"; ?>
<?php include('includes/header.php'); ?>
    <h3>Hardware Assets </h3>
    <table class="table1">
      <tr>
        <th>Asset Type </th>
        <th>Vendor</th>
        <th>Model</th>
        <th>Platform</th>
        <th>Location</th>
        <th>Status</th>
        <th>User</th>
        <th>Division</th>
        <th>View &nbsp; </th>
        <th>Edit &nbsp; </th>
      </tr>
      <?php foreach ($rsHardwareAssets as $row_rsHardwareAssets) { ?>
        <tr onmouseover="this.bgColor='#F2F7FF'" onmouseout="this.bgColor='#FFFFFF'";>
          <td> <?php echo $row_rsHardwareAssets['asset_type']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['vendor']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['model']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['platform']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['location']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['status']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['user']; ?>&nbsp; </td>
          <td> <?php echo $row_rsHardwareAssets['division']; ?>&nbsp; </td>
          <td> <a href="HardwareDetail.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">View</a></td>
          <td> <a href="HardwareUpdate.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">Edit</a></td>
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
// Close the database connection
$dbh = null;
?>
