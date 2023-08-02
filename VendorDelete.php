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

if ((isset($_GET['recordID'])) && ($_GET['recordID'] != "")) {
  $deleteSQL = "DELETE FROM vendors WHERE vendor_id = ?";

  $stmt = $conn->prepare($deleteSQL);
  $stmt->bind_param("i", $_GET['recordID']);

  $stmt->execute();
  $stmt->close();

  $deleteGoTo = "VendorList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("Location: " . $deleteGoTo);
}
?>

<?php $pageTitle = "Delete Vendor"; ?>
<?php include('includes/header.php'); ?>
<h3>Vendor Delete</h3>
<form id="delRecord" name="delRecord" method="post" action="">
  <label>
  <input type="submit" name="Submit" value="Delete Record" />
  </label>
</form>
<?php include('includes/footer.php'); ?>
