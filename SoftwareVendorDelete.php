<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Graham Fisk - BigSmallweb.com - 2012 // 

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($conn, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    switch ($theType) {
      case "text":
      case "date":
        $theValue = ($theValue != "") ? "'" . mysqli_real_escape_string($conn, $theValue) . "'" : "NULL";
        break;
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

if ((isset($_GET['recordID'])) && ($_GET['recordID'] != "")) {
  $conn = new mysqli($hostname_eam, $username_eam, $password_eam, $database_eam);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $recordID = $_GET['recordID'];
  $deleteSQL = "DELETE FROM vendors_software WHERE vendor_id = ?";
  $stmt = $conn->prepare($deleteSQL);
  $stmt->bind_param("i", $recordID);
  $stmt->execute();
  $stmt->close();

  $conn->close();

  $deleteGoTo = "SoftwareVendorList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("Location: " . $deleteGoTo);
}
?>

<?php $pageTitle="Delete Software Vendor"; ?>
<?php include('includes/header.php'); ?>
<h3>Software Vendor Delete </h3>
<form id="delRecord" name="delRecord" method="post" action="">
  <label>
    <input type="submit" name="Submit" value="Delete Record" />
  </label>
</form>
<?php include('includes/footer.php'); ?>
