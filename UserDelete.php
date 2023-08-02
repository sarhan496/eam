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

if (isset($_GET['recordID']) && $_GET['recordID'] != "") {
  $deleteSQL = "DELETE FROM members WHERE id = ?";
  $stmt = $conn->prepare($deleteSQL);
  $stmt->bind_param("i", $_GET['recordID']);
  $stmt->execute();
  $stmt->close();

  $deleteGoTo = "UserList.php";
  header("Location: " . $deleteGoTo);
  exit;
}

$pageTitle = "Delete User";
include('includes/header.php');
?>
<h3>User Delete</h3>
<form id="delRecord" name="delRecord" method="post" action="">
  <label>
    <input type="submit" name="Submit" value="Delete Record" />
  </label>
</form>
<?php include('includes/footer.php'); ?>
