<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Graham Fisk - BigSmallweb.com - 2012 // 

function GetSQLValueString($conn, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (!is_array($theValue)) {
    $theValue = addslashes($theValue);
  }

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $conn->real_escape_string($theValue) . "'" : "NULL";
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

if ((isset($_POST['vendor_id'])) && ($_POST['vendor_id'] != "")) {
  $deleteSQL = "DELETE FROM vendors WHERE vendor_id = ?";

  $stmt = $conn->prepare($deleteSQL);
  $stmt->bind_param("i", $_POST['vendor_id']);

  $stmt->execute();
  $stmt->close();

  $deleteGoTo = "VendorList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("Location: " . $deleteGoTo);
}

$conn->select_db($database_eam);
$query_rsVendorList = "SELECT * FROM vendors ORDER BY vendor ASC";
$rsVendorList = $conn->query($query_rsVendorList) or die($conn->error);
$row_rsVendorList = $rsVendorList->fetch_assoc();
$totalRows_rsVendorList = $rsVendorList->num_rows;
?>
<?php $pageTitle = "Vendor List"; ?>
<?php include('includes/header.php'); ?>
<h3>Vendor List</h3>
<table width="600" class="table1">
  <tr>
    <th>Vendor</th>
    <th>Comments</th>
    <th>Update</th>
    <th>Delete</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsVendorList['vendor']; ?></td>
      <td><?php echo $row_rsVendorList['comments']; ?></td>
      <td><a href="VendorUpdate.php?vendor_id=<?php echo $row_rsVendorList['vendor_id']; ?>">Update</a></td>
      <td>
        <form id="delRecord" name="delRecord" method="post" action="VendorDelete.php">
          <input type="hidden" name="vendor_id" value="<?php echo $row_rsVendorList['vendor_id']; ?>">
          <input name="Submit" type="submit" class="red" value="Delete This Record" />
        </form>
      </td>
    </tr>
  <?php } while ($row_rsVendorList = $rsVendorList->fetch_assoc()); ?>
</table>
<table class="pagination">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php include('includes/footer.php'); ?>
<?php
$rsVendorList->free_result();
?>
