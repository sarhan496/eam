<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Graham Fisk - BigSmallweb.com - 2012 // 
if (!function_exists("GetSQLValueString")) {
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = "UPDATE members SET username=?, password=?, firstname=?, lastname=?, `role`=? WHERE id=?";

  $stmt = $conn->prepare($updateSQL);
  $stmt->bind_param(
    "sssssi",
    $_POST['username'],
    $_POST['password'],
    $_POST['firstname'],
    $_POST['lastname'],
    $_POST['role'],
    $_POST['id']
  );

  $stmt->execute();
  $stmt->close();

  $updateGoTo = "UserList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("Location: " . $updateGoTo);
}

$colname_rsMembers = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsMembers = $_GET['recordID'];
}

$query_rsMembers = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($query_rsMembers);
$stmt->bind_param("i", $colname_rsMembers);
$stmt->execute();
$rsMembers = $stmt->get_result();
$row_rsMembers = $rsMembers->fetch_assoc();
$totalRows_rsMembers = $rsMembers->num_rows;

$query_rsMemberRoles = "SELECT `role` FROM members";
$rsMemberRoles = $conn->query($query_rsMemberRoles);
$row_rsMemberRoles = $rsMemberRoles->fetch_assoc();
$totalRows_rsMemberRoles = $rsMemberRoles->num_rows;

$pageTitle = "User Update";
include('includes/header.php');
?>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <fieldset>
    <legend>Update User</legend>
    <p>
      <label for="User ID">User ID</label>
      <?php echo $row_rsMembers['id']; ?>
    </p>
    <p>
      <label for="First Name">First Name</label>
      <input type="text" name="firstname" value="<?php echo htmlentities($row_rsMembers['firstname'], ENT_COMPAT, 'UTF-8'); ?>" size="32">
    </p>
    <p>
      <label for="Last Name">Last Name</label>
      <input type="text" name="lastname" value="<?php echo htmlentities($row_rsMembers['lastname'], ENT_COMPAT, 'UTF-8'); ?>" size="32">
    </p>
    <p>
      <label for="Username">Username</label>
      <input type="text" name="username" value="<?php echo htmlentities($row_rsMembers['username'], ENT_COMPAT, 'UTF-8'); ?>" size="32">
    </p>
    <p>
      <label for="Password">Password</label>
      <input type="text" name="password" value="<?php echo htmlentities($row_rsMembers['password'], ENT_COMPAT, 'UTF-8'); ?>" size="32">
    </p>
    <p>
      <label for="Role">Role</label>
      <select name="role">
        <option value="admin" <?php if ($row_rsMembers['role'] === "admin") echo "selected"; ?>>Admin - Full Rights</option>
        <option value="editor" <?php if ($row_rsMembers['role'] === "editor") echo "selected"; ?>>Editor - Manage Assets, Reporting</option>
        <?php
        do {
          $currentRole = $row_rsMemberRoles['role'];
          $selected = ($row_rsMembers['role'] === $currentRole) ? "selected" : "";
          echo '<option value="' . $currentRole . '" ' . $selected . '>' . $currentRole . ' &nbsp;&nbsp;<- current role</option>';
        } while ($row_rsMemberRoles = $rsMemberRoles->fetch_assoc());
        ?>
      </select>
    </p>
    <p class="submit">
      <input type="submit" value="Update User">
    </p>
  </fieldset>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_rsMembers['id']; ?>">
</form>
<?php include('includes/footer.php');
