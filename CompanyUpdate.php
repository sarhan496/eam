<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan // 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
  }

  $theValue = $mysqli->real_escape_string($theValue);

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
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  $mysqli->close();
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
  }

  $updateSQL = sprintf("UPDATE company SET company_name=%s WHERE company_id=%s",
                       GetSQLValueString($_POST['company_name'], "text", $mysqli),
                       GetSQLValueString($_POST['company_id'], "int", $mysqli));

  $result = $mysqli->query($updateSQL);
  if ($result === TRUE) {
      $updateGoTo = "Admin.php";
      if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
      }
      header("Location: " . $updateGoTo);
      exit;
  } else {
      die("Error updating record: " . $mysqli->error);
  }

  $mysqli->close();
}

$mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query_rsCompanyUpdate = "SELECT * FROM company";
$rsCompanyUpdate = $mysqli->query($query_rsCompanyUpdate);
$row_rsCompanyUpdate = $rsCompanyUpdate->fetch_assoc();
$totalRows_rsCompanyUpdate = $rsCompanyUpdate->num_rows;

$mysqli->close();
?>

<?php include('includes/header.php'); ?>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <fieldset>
      <legend>Company Name - Update</legend>

      <p>
        <label for="Company Name">Company Name:</label>
        <input name="company_name" type="text" value="<?php echo htmlentities($row_rsCompanyUpdate['company_name'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="200" />
      <p class="submit">
        <input type="submit" value="Update record" />
      </p>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="company_id" value="<?php echo $row_rsCompanyUpdate['company_id']; ?>" />


      <p>&nbsp;</p>
      <p>&nbsp;</p>
      </fieldset>
    </form>
    <?php include('includes/footer.php'); ?>
<?php
$rsCompanyUpdate->free_result();
?>
