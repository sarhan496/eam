<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan // 
function GetSQLValueString($database_eam, $theValue, $theType)
{
  $theValue = $database_eam->real_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
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
    default:
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form1") {
  $assets_hardware_type = $_POST['assets_hardware_type'];
  $id = intval($_POST['id']);
  $comments = $_POST['comments'];

  $database_eam = new mysqli('localhost', 'username', 'password', 'database_name');
  if ($database_eam->connect_error) {
    die("Connection failed: " . $database_eam->connect_error);
  }

  $insertSQL = "INSERT INTO assets_hardware_type (assets_hardware_type, id, comments) VALUES (?, ?, ?)";
  $stmt = $database_eam->prepare($insertSQL);
  $stmt->bind_param("sis", $assets_hardware_type, $id, $comments);
  $stmt->execute();
  $stmt->close();

  $database_eam->close();

  $insertGoTo = "HardwareTypeList.php";
  header("Location: $insertGoTo");
  exit;
}
?>
<?php $pageTitle = "Add Hardware Type"; ?>
<?php include('includes/header.php'); ?>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <fieldset>
    <legend>Add Hardware Type</legend>
    <p>
      <label for="Vendor">Hardware Type</label>
      <input type="text" name="assets_hardware_type" value="" size="32">
    </p>
    <p>
      <label for="Comments">Comments</label>
      <textarea name="comments" cols="30" rows="6" id="comments"></textarea>
    </p>
    <p class="submit">
      <input type="submit" value="Insert record">
    </p>
  </fieldset>

  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php include('includes/footer.php'); ?>
