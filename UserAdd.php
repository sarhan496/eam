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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $insertSQL = "INSERT INTO members (username, password, firstname, lastname, role) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($insertSQL);
  $stmt->bind_param("sssss", $_POST['username'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['role']);
  $stmt->execute();
  $stmt->close();

  $insertGoTo = "UserList.php";
  header("Location: " . $insertGoTo);
  exit;
}

$pageTitle = "Add User";
include('includes/header.php');
?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="addUser">
  <fieldset>
    <legend>Add User</legend>
    <p>
      <label for="First Name">First Name</label> 
      <input name="firstname" type="text" id="firstname" size="32" maxlength="100" required>
    </p> 
    <p>
      <label for="Last Name">Last Name</label> 
      <input name="lastname" type="text" id="lastname" value="" size="32" maxlength="100" required>
    </p> 
    <p>
      <label for="Username">Username</label> 
      <input name="username" type="text" id="username" value="" size="32" maxlength="30" required>
    </p> 
    <p>
      <label for="Password">Password</label> 
      <input name="password" type="password" id="password" value="" size="32" required>
    </p> 
    <p>
      <label for="Role">Role</label> 
      <select name="role" required>
        <option value="" disabled selected> -- Choose Role --</option>
        <option value="admin">Admin - Full Rights</option>
        <option value="editor">Editor - Manage Assets, Reporting</option>
      </select>
    </p> 
    <p class="submit">
      <input type="submit" value="Add user"> 
    </p>
  </fieldset>
</form>
<?php include('includes/footer.php'); ?>
