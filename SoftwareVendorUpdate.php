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

$conn = new mysqli($hostname_eam, $username_eam, $password_eam, $database_eam);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE vendors_software SET vendor=%s, comments=%s WHERE vendor_id=%s",
                       GetSQLValueString($conn, $_POST['vendor'], "text"),
                       GetSQLValueString($conn, $_POST['comments'], "text"),
                       GetSQLValueString($conn, $_POST['vendor_id'], "int"));

  $Result1 = $conn->query($updateSQL) or die($conn->error);

  $updateGoTo = "SoftwareVendorList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsSoftwareVendor = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsSoftwareVendor = (is_array($_GET['recordID'])) ? $_GET['recordID'] : addslashes($_GET['recordID']);
}

$query_rsSoftwareVendor = sprintf("SELECT * FROM vendors_software WHERE vendor_id = %s", $colname_rsSoftwareVendor);
$rsSoftwareVendor = $conn->query($query_rsSoftwareVendor);

$pageTitle = "Software Vendor Update";
include('includes/header.php');
?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <fieldset>
    <legend>Update Software Vendor</legend>
    <p>
      <label for="Vendor ID">Vendor id</label>
      <?php echo htmlspecialchars($rsSoftwareVendor['vendor_id']); ?>
    </p>
    <p>
      <label for="Vendor">Vendor</label>
      <input type="text" name="vendor" value="<?php echo htmlspecialchars($rsSoftwareVendor['vendor']); ?>" size="32">
    </p>
    <p>
      <label for="Comments">Comments</label>
      <textarea name="comments" cols="30" rows="3"><?php echo htmlspecialchars($rsSoftwareVendor['comments']); ?></textarea>
    </p>
    <p class="submit">
      <input type="submit" value="Update record">
    </p>
  </fieldset>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="vendor_id" value="<?php echo htmlspecialchars($rsSoftwareVendor['vendor_id']); ?>">
</form>

<?php
$rsSoftwareVendor->free_result();
$conn->close();
include('includes/footer.php');
?>
