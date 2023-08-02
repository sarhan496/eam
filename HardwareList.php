<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 

$connection = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsHardwareAssets = 50;
$pageNum_rsHardwareAssets = 0;
if (isset($_GET['pageNum_rsHardwareAssets'])) {
    $pageNum_rsHardwareAssets = $_GET['pageNum_rsHardwareAssets'];
}
$startRow_rsHardwareAssets = $pageNum_rsHardwareAssets * $maxRows_rsHardwareAssets;

$query_rsHardwareAssets = "SELECT * FROM assets_hardware";
$query_limit_rsHardwareAssets = sprintf("%s LIMIT %d, %d", $query_rsHardwareAssets, $startRow_rsHardwareAssets, $maxRows_rsHardwareAssets);
$rsHardwareAssets = $connection->query($query_limit_rsHardwareAssets);

if (!$rsHardwareAssets) {
    die("Error executing query: " . $connection->error);
}

$row_rsHardwareAssets = $rsHardwareAssets->fetch_assoc();

if (isset($_GET['totalRows_rsHardwareAssets'])) {
    $totalRows_rsHardwareAssets = $_GET['totalRows_rsHardwareAssets'];
} else {
    $all_rsHardwareAssets = $connection->query($query_rsHardwareAssets);
    $totalRows_rsHardwareAssets = $all_rsHardwareAssets->num_rows;
}
$totalPages_rsHardwareAssets = ceil($totalRows_rsHardwareAssets / $maxRows_rsHardwareAssets) - 1;

$query_rsVendors = "SELECT * FROM vendors";
$rsVendors = $connection->query($query_rsVendors);

if (!$rsVendors) {
    die("Error executing query: " . $connection->error);
}

$row_rsVendors = $rsVendors->fetch_assoc();

$query_rsPlatform = "SELECT * FROM assets_hardware_platform";
$rsPlatform = $connection->query($query_rsPlatform);

if (!$rsPlatform) {
    die("Error executing query: " . $connection->error);
}

$row_rsPlatform = $rsPlatform->fetch_assoc();

$colname_rsHardwareUpdate = "-1";
if (isset($_GET['recordID'])) {
    $colname_rsHardwareUpdate = isset($_GET['recordID']) ? $_GET['recordID'] : '';
    if (!empty($colname_rsHardwareUpdate) && ini_get('magic_quotes_gpc')) {
        $colname_rsHardwareUpdate = stripslashes($colname_rsHardwareUpdate);
    }
}

$query_rsHardwareUpdate = sprintf("SELECT * FROM assets_hardware WHERE asset_hardware_id = %s", $connection->real_escape_string($colname_rsHardwareUpdate));
$rsHardwareUpdate = $connection->query($query_rsHardwareUpdate);

if (!$rsHardwareUpdate) {
    die("Error executing query: " . $connection->error);
}

$row_rsHardwareUpdate = $rsHardwareUpdate->fetch_assoc();
$totalRows_rsHardwareUpdate = $rsHardwareUpdate->num_rows;

// ...
?>

<?php $pageTitle = "Hardware Assets List"; ?>
<?php include('includes/header.php'); ?>
<h2 style="background-color:Orange;">Hardware Assets List</h2>
<table border=1 class="table1">
  <tr>
    <th>Asset Type</th>
    <th>Vendor</th>
    <th>Model</th>
    <th>Platform</th>
    <th>Location</th>
    <th>Status</th>
    <th>User</th>
    <th>Division</th>
    <th>View &nbsp;</th>
    <th>QR &nbsp;</th>
    <th>Edit &nbsp;</th>
  </tr>
  <?php do { ?>
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
      <td>
        <div>
          <a href="#" onclick="openQRCode('<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>', '<?php echo $row_rsHardwareAssets['model']; ?>')">
            <img src="https://chart.googleapis.com/chart?chs=90x90&cht=qr&chl=http://192.168.100.7/eam/HardwareDetail.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>&choe=UTF-8" alt="QR Code">
          </a>
        </div>
        <div><?php echo $row_rsHardwareAssets['model']; ?></div>
        <div><button onclick="printQRCode('<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>', '<?php echo $row_rsHardwareAssets['model']; ?>')" >Print</button></div>
      </td>
      <td> <a href="HardwareUpdate.php?recordID=<?php echo $row_rsHardwareAssets['asset_hardware_id']; ?>">Edit</a></td>
    </tr>
  <?php } while ($row_rsHardwareAssets = $rsHardwareAssets->fetch_assoc()); ?>
</table>

<!-- JavaScript function to open the QR code as a view image -->
<script>
  function openQRCode(recordID, model) {
    var qrCodeURL = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=http://192.168.100.7/eam/HardwareDetail.php?recordID=' + recordID + '&choe=UTF-8';
    var img = '<img src="' + qrCodeURL + '" alt="QR Code">' + '<div>' + model + '</div>' + '<button onclick="window.print()">Print this page</button>';
    var newWindow = window.open('', 'QR Code', 'width=200,height=500');
    newWindow.document.body.innerHTML = img;
  }

  function printQRCode(recordID, model) {
    var qrCodeURL = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=http://192.168.100.7/eam/HardwareDetail.php?recordID=' + recordID + '&choe=UTF-8';
    var img = '<center>'+'<img src="' + qrCodeURL + '" alt="QR Code">' + '<div>' + model + '</div>'+'</center>';
    var newWindow = window.open('', 'Print', 'width=1500,height=1500');
    newWindow.document.body.innerHTML = img;
    newWindow.onload = function() {
      newWindow.print();
    };
  }
</script>


<!-- ... (The rest of your code remains unchanged) -->

<table class="pagination">
  <tr>
    <td>
      <div style="float:left;">Records <?php echo ($startRow_rsHardwareAssets + 1) ?> to <?php echo min($startRow_rsHardwareAssets + $maxRows_rsHardwareAssets, $totalRows_rsHardwareAssets) ?> of <?php echo $totalRows_rsHardwareAssets ?></div>
      <div style="float:right;">
        <table class="pagination1">
          <tr>
            <?php if ($pageNum_rsHardwareAssets > 0) { // Show if not first page ?>
              <td> <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, 0, $queryString_rsHardwareAssets); ?>"><img src="images/First.gif" alt="First Page" title="First Page" /></a> </td>
            <?php } // Show if not first page ?>

            <?php if ($pageNum_rsHardwareAssets > 0) { // Show if not first page ?>		
              <td> <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, max(0, $pageNum_rsHardwareAssets - 1), $queryString_rsHardwareAssets); ?>"><img src="images/Previous.gif" alt="Previous Page" title="Previous Page" /></a> </td>
            <?php } // Show if not first page ?>

            <?php if ($pageNum_rsHardwareAssets < $totalPages_rsHardwareAssets) { // Show if not last page ?>
              <td> <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, min($totalPages_rsHardwareAssets, $pageNum_rsHardwareAssets + 1), $queryString_rsHardwareAssets); ?>"><img src="images/Next.gif" alt="Next Page" title="Next Page" /></a> </td>
            <?php } // Show if not last page ?>

            <?php if ($pageNum_rsHardwareAssets < $totalPages_rsHardwareAssets) { // Show if not last page ?>
              <td> <a href="<?php printf("%s?pageNum_rsHardwareAssets=%d%s", $currentPage, $totalPages_rsHardwareAssets, $queryString_rsHardwareAssets); ?>"><img src="images/Last.gif" alt="Last Page" title="Last Page" /></a> </td>
            <?php } // Show if not last page ?>		
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>

<?php include('includes/footer.php'); ?>

<?php
##$rsHardwareAssets->close();
##$rsVendors->close();
##$rsPlatform->close();
##$rsHardwareUpdate->close();
##$rsDivision->close();
##$rsHardwareType->close();
##$rsLocation->close();
#$rsHardwareStatus->close();
#$connection->close();
?>
