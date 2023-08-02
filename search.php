<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Graham Fisk - BigSmallweb.com - 2013 // 
mysqli_select_db($eam, $database_eam);

$query_rsVendors = "SELECT * FROM vendors ORDER BY vendor ASC";
$rsVendors = mysqli_query($eam, $query_rsVendors) or die(mysqli_error($eam));
$row_rsVendors = mysqli_fetch_assoc($rsVendors);
$totalRows_rsVendors = mysqli_num_rows($rsVendors);

$query_rsPlatforms = "SELECT * FROM assets_hardware_platform ORDER BY platform ASC";
$rsPlatforms = mysqli_query($eam, $query_rsPlatforms) or die(mysqli_error($eam));
$row_rsPlatforms = mysqli_fetch_assoc($rsPlatforms);
$totalRows_rsPlatforms = mysqli_num_rows($rsPlatforms);

$query_rsHardwareType = "SELECT * FROM assets_hardware_type";
$rsHardwareType = mysqli_query($eam, $query_rsHardwareType) or die(mysqli_error($eam));
$row_rsHardwareType = mysqli_fetch_assoc($rsHardwareType);
$totalRows_rsHardwareType = mysqli_num_rows($rsHardwareType);

$query_rsAssets = "SELECT DISTINCT model FROM assets_hardware ORDER BY model ASC";
$rsAssets = mysqli_query($eam, $query_rsAssets) or die(mysqli_error($eam));
$row_rsAssets = mysqli_fetch_assoc($rsAssets);
$totalRows_rsAssets = mysqli_num_rows($rsAssets);

$pageTitle = "Search";
?>
<?php include('includes/header.php'); ?>
<fieldset>
<legend>Search Assets</legend>
<form id="searchAssets" name="searchAssets" method="post" action="searchResults.php">
  <table class="tableSearch">
    <tr>
      <th>Purchase Order</th>
      <td><input type="text" name="purchase_order" /></td>
      <td><input type="submit" name="submitPurchaseOrder" value="Search" /></td>
    </tr>
    <tr>
      <th>Asset Tag</th>
      <td><input type="text" name="asset_tag" /></td>
      <td><input type="submit" name="submitAssetTag" value="Search" /></td>
    </tr>
    <tr>
      <th>Serial Number</th>
      <td><input type="text" name="serialnumber" /></td>
      <td><input type="submit" name="submitSerialNumber" value="Search" /></td>
    </tr>
    <tr>
      <th>Vendor</th>
      <td>
        <select name="vendor" id="vendor">
          <option value="value">Select Vendor</option>
          <?php do { ?>
            <option value="<?php echo $row_rsVendors['vendor']?>"><?php echo $row_rsVendors['vendor']?></option>
          <?php } while ($row_rsVendors = mysqli_fetch_assoc($rsVendors)); ?>
        </select>
      </td>
      <td>
        <input type="submit" name="submitVendor" value="Search" />
      </td>
    </tr>
    <!-- Rest of the form fields -->
  </table>
</form>
</fieldset>
<?php include('includes/footer.php'); ?>
<?php
mysqli_free_result($rsVendors);
mysqli_free_result($rsPlatforms);
mysqli_free_result($rsHardwareType);
mysqli_free_result($rsAssets);
?>
