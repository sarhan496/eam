<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan
function GetSQLValueString($mysqli, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = trim($theValue);
  
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = $mysqli->real_escape_string($theValue);

  switch ($theType) {
    case "text":
    case "date":
      $theValue = ($theValue !== "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue !== "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue !== "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue !== "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
$mysqli = new mysqli("localhost", "root", "", "eam");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = "UPDATE division SET division=?, comments=? WHERE id=?";

  $stmt = $mysqli->prepare($updateSQL);
  if (!$stmt) {
    die("Error preparing statement: " . $mysqli->error);
  }

  $division = $_POST['division'];
  $comments = $_POST['comments'];
  $id = $_POST['id'];

  $stmt->bind_param("ssi", $division, $comments, $id);

  if ($stmt->execute()) {
    $stmt->close();
    $updateGoTo = "DivisionList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
      $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
      $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header("Location: " . $updateGoTo);
    exit;
  } else {
    die("Error updating record: " . $mysqli->error);
  }
}

$colname_rsDivisionUpdate = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsDivisionUpdate = ($_GET['recordID']);
}

$query_rsDivisionUpdate = "SELECT * FROM division WHERE id = ?";
$stmt = $mysqli->prepare($query_rsDivisionUpdate);
if (!$stmt) {
  die("Error preparing statement: " . $mysqli->error);
}

$stmt->bind_param("i", $colname_rsDivisionUpdate);
$stmt->execute();
$rsDivisionUpdate = $stmt->get_result();
$row_rsDivisionUpdate = $rsDivisionUpdate->fetch_assoc();
$totalRows_rsDivisionUpdate = $rsDivisionUpdate->num_rows;
?>

<?php $pageTitle = "Update Division"; ?>
<?php include('includes/header.php'); ?>
<form method="post" name="form1" action="<?php echo htmlspecialchars($editFormAction); ?>">
  <fieldset>
    <legend>Update Division</legend>
    <p>
      <label for="Vendor ID">Division ID</label>
      <?php echo $row_rsDivisionUpdate['id']; ?>
    </p>
    <p>
      <label for="Vendor">Division</label>
      <input type="text" name="division" value="<?php echo $row_rsDivisionUpdate['division']; ?>" size="32" />
    </p>
    <p>
      <label for="Comments">Comments</label>
      <textarea name="comments" cols="30" rows="3"><?php echo $row_rsDivisionUpdate['comments']; ?></textarea>
    </p>
    <p class="submit">
      <input type="submit" value="Update record">
    </p>
  </fieldset>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_rsDivisionUpdate['id']; ?>">
</form>
<?php include('includes/footer.php'); ?>

<?php
$rsDivisionUpdate->free_result();
?>
