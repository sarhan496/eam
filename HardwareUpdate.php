<?php
require_once('Connections/eam.php');

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($conn, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
    {
        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($conn, $theValue) : $theValue;

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
        return $theValue;
    }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf("UPDATE assets_hardware SET asset_type=%s, vendor=%s, model=%s, serialnumber=%s, location=%s, date_purchase=%s, date_decomission=%s, status=%s, `user`=%s, division=%s, platform=%s, comments=%s, monitor_size=%s, warranty=%s, `cube`=%s, field_address=%s, user_account=%s, asset_tag=%s, purchase_order=%s WHERE asset_hardware_id=%s",
        GetSQLValueString($eam, $_POST['asset_type'], "text"),
        GetSQLValueString($eam, $_POST['vendor'], "text"),
        GetSQLValueString($eam, $_POST['model'], "text"),
        GetSQLValueString($eam, $_POST['serialnumber'], "text"),
        GetSQLValueString($eam, $_POST['location'], "text"),
        GetSQLValueString($eam, $_POST['date_purchase'], "text"),
        GetSQLValueString($eam, $_POST['date_decomission'], "text"),
        GetSQLValueString($eam, $_POST['status'], "text"),
        GetSQLValueString($eam, $_POST['user'], "text"),
        GetSQLValueString($eam, $_POST['division'], "text"),
        GetSQLValueString($eam, $_POST['platform'], "text"),
        GetSQLValueString($eam, $_POST['comments'], "text"),
        GetSQLValueString($eam, $_POST['monitor_size'], "int"),
        GetSQLValueString($eam, $_POST['warranty'], "text"),
        GetSQLValueString($eam, $_POST['cube'], "text"),
        GetSQLValueString($eam, $_POST['field_address'], "text"),
        GetSQLValueString($eam, $_POST['user_account'], "text"),
        GetSQLValueString($eam, $_POST['asset_tag'], "text"),
        GetSQLValueString($eam, $_POST['purchase_order'], "text"),
        GetSQLValueString($eam, $_POST['asset_hardware_id'], "int")
    );

    $Result1 = mysqli_query($eam, $updateSQL) or die(mysqli_error($eam));

    $updateGoTo = "HardwareList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header("Location: " . $updateGoTo);
}

$colname_rsHardwareUpdate = "-1";
if (isset($_GET['recordID'])) {
    $colname_rsHardwareUpdate = $_GET['recordID'];
}
$query_rsHardwareUpdate = sprintf("SELECT * FROM assets_hardware WHERE asset_hardware_id = %s", GetSQLValueString($eam, $colname_rsHardwareUpdate, "int"));
$rsHardwareUpdate = mysqli_query($eam, $query_rsHardwareUpdate) or die(mysqli_error($eam));
$row_rsHardwareUpdate = mysqli_fetch_assoc($rsHardwareUpdate);
$totalRows_rsHardwareUpdate = mysqli_num_rows($rsHardwareUpdate);

$query_rsVendors = "SELECT * FROM vendors ORDER BY vendor ASC";
$rsVendors = mysqli_query($eam, $query_rsVendors) or die(mysqli_error($eam));
$row_rsVendors = mysqli_fetch_assoc($rsVendors);
$totalRows_rsVendors = mysqli_num_rows($rsVendors);

$query_rsHardwarePlatform = "SELECT * FROM assets_hardware_platform ORDER BY platform ASC";
$rsHardwarePlatform = mysqli_query($eam, $query_rsHardwarePlatform) or die(mysqli_error($eam));
$row_rsHardwarePlatform = mysqli_fetch_assoc($rsHardwarePlatform);
$totalRows_rsHardwarePlatform = mysqli_num_rows($rsHardwarePlatform);

$query_rsHardwareType = "SELECT * FROM assets_hardware_type ORDER BY assets_hardware_type ASC";
$rsHardwareType = mysqli_query($eam, $query_rsHardwareType) or die(mysqli_error($eam));
$row_rsHardwareType = mysqli_fetch_assoc($rsHardwareType);
$totalRows_rsHardwareType = mysqli_num_rows($rsHardwareType);

$query_rsDivision = "SELECT * FROM division ORDER BY division ASC";
$rsDivision = mysqli_query($eam, $query_rsDivision) or die(mysqli_error($eam));
$row_rsDivision = mysqli_fetch_assoc($rsDivision);
$totalRows_rsDivision = mysqli_num_rows($rsDivision);

$query_rsLocation = "SELECT * FROM location ORDER BY location ASC";
$rsLocation = mysqli_query($eam, $query_rsLocation) or die(mysqli_error($eam));
$row_rsLocation = mysqli_fetch_assoc($rsLocation);
$totalRows_rsLocation = mysqli_num_rows($rsLocation);

$query_rsHardwareStatus = "SELECT * FROM assets_hardware_status ORDER BY assets_hardware_status ASC";
$rsHardwareStatus = mysqli_query($eam, $query_rsHardwareStatus) or die(mysqli_error($eam));
$row_rsHardwareStatus = mysqli_fetch_assoc($rsHardwareStatus);
$totalRows_rsHardwareStatus = mysqli_num_rows($rsHardwareStatus);

$query_rsMonitorSize = "SELECT * FROM assets_hardware_monitor_size";
$rsMonitorSize = mysqli_query($eam, $query_rsMonitorSize) or die(mysqli_error($eam));
$row_rsMonitorSize = mysqli_fetch_assoc($rsMonitorSize);
$totalRows_rsMonitorSize = mysqli_num_rows($rsMonitorSize);
?>
<?php $pageTitle = "Update Hardware Asset"; ?>
<?php include('includes/header.php'); ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <fieldset>
    <legend>Hardware - Update</legend>
    <!-- ... Rest of the form ... -->
    <p>
      <label for="Asset Type">Asset Type</label>
      <select name="asset_type">
        <?php
        do {
          ?>
          <option value="<?php echo htmlspecialchars($row_rsHardwareType['assets_hardware_type']); ?>" <?php if ($row_rsHardwareType['assets_hardware_type'] == $row_rsHardwareUpdate['asset_type']) {echo "selected=\"selected\"";} ?>><?php echo htmlspecialchars($row_rsHardwareType['assets_hardware_type']); ?></option>
        <?php
        } while ($row_rsHardwareType = mysqli_fetch_assoc($rsHardwareType));
        ?>
      </select>
    </p>

    <!-- Conditional for Monitors -->
    <?php if ($row_rsHardwareUpdate['asset_type'] == "Monitor") {
    ?>
      <p>
        <label for="Monitor size">Monitor size</label>
        <select name="monitor_size">
          <option value="0" <?php if ($row_rsHardwareUpdate['monitor_size'] == 0) {echo "selected=\"selected\"";} ?>>None</option>
          <?php
          do {
            ?>
            <option value="<?php echo htmlspecialchars($row_rsMonitorSize['size']); ?>" <?php if ($row_rsMonitorSize['size'] == $row_rsHardwareUpdate['monitor_size']) {echo "selected=\"selected\"";} ?>><?php echo htmlspecialchars($row_rsMonitorSize['size']); ?></option>
          <?php
          } while ($row_rsMonitorSize = mysqli_fetch_assoc($rsMonitorSize));
          $rows = mysqli_num_rows($rsMonitorSize);
          if ($rows > 0) {
            mysqli_data_seek($rsMonitorSize, 0);
            $row_rsMonitorSize = mysqli_fetch_assoc($rsMonitorSize);
          }
          ?>
        </select>
      </p>
    <?php
    }
    ?>

    <p>
      <label for="Platform">Platform</label>
      <select name="platform">
        <?php
        do {
          ?>
          <option value="<?php echo htmlspecialchars($row_rsHardwarePlatform['platform']); ?>" <?php if ($row_rsHardwarePlatform['platform'] == $row_rsHardwareUpdate['platform']) {echo "selected=\"selected\"";} ?>><?php echo htmlspecialchars($row_rsHardwarePlatform['platform']); ?></option>
        <?php
        } while ($row_rsHardwarePlatform = mysqli_fetch_assoc($rsHardwarePlatform));
        ?>
      </select>
    </p>


        <label for="Model">Model</label>
        <input name="model" type="text" value="<?php echo $row_rsHardwareUpdate['model']; ?>" size="8" maxlength="30" /> 
		<span class="tiny"> D610, GX620, MacPro, MBP...</span> 
      </p>
      <p>
        <label for="Serial number">Serial number</label>
        <input name="serialnumber" type="text" value="<?php echo $row_rsHardwareUpdate['serialnumber']; ?>" size="12" maxlength="30" />
      </p>
      <p>
        <label for="Asset Tag">Asset Tag</label>
        <input name="asset_tag" type="text" value="<?php echo $row_rsHardwareUpdate['asset_tag']; ?>" size="12" maxlength="30" />
      </p>
      <p>
        <label for="Asset Tag">Purchse Order</label>
        <input name="purchase_order" type="text" value="<?php echo $row_rsHardwareUpdate['purchase_order']; ?>" size="12" maxlength="30" />
      </p>           
      <hr />
      <p>
        <label for="Status">Status</label>
        <select name="status">
          <?php 
do {  
?>
          <option value="<?php echo $row_rsHardwareStatus['assets_hardware_status']?>" <?php if (!(strcmp($row_rsHardwareStatus['assets_hardware_status'], $row_rsHardwareUpdate['status']))) {echo "SELECTED";} ?>><?php echo $row_rsHardwareStatus['assets_hardware_status']?></option>
          <?php
} while ($row_rsHardwareStatus = mysqli_fetch_assoc($rsHardwareStatus));
?>
        </select>
      </p>
      <p>
        <label for="Date purchased">Date purchased</label>
        <input type="text" name="date_purchase" value="<?php echo $row_rsHardwareUpdate['date_purchase']; ?>" size="8" />
        <img src='images/scw.gif' title='Click Here' alt='Click Here' onclick="cal.select(document.forms['form1'].date_purchase,'anchor2','MM/dd/yyyy'); return false;" name="anchor2" id="anchor2" style="cursor:hand" /> </p>
      <p>
        <label for="Warrant Date ">Warranty Date </label>
        <input type="text" name="warranty" value="<?php echo $row_rsHardwareUpdate['warranty']; ?>" size="8" />
        <img src='images/scw.gif' title='Click Here' alt='Click Here' onclick="cal.select(document.forms['form1'].warranty,'anchor1','MM/dd/yyyy'); return false;" name="anchor1" id="anchor1" style="cursor:hand" /> </p>
      <p>
        <label for="Division">Date decomissioned</label>
        <input type="text" name="date_decomission" value="<?php echo $row_rsHardwareUpdate['date_decomission']; ?>" size="8" />
        <img src='images/scw.gif' title='Click Here' alt='Click Here' onclick="cal.select(document.forms['form1'].date_decomission,'anchor3','MM/dd/yyyy'); return false;" name="anchor3" id="anchor3" style="cursor:hand" /> </p>
      <hr />
      <p>
        <label for="User">User</label>
        <input name="user" type="text" value="<?php echo $row_rsHardwareUpdate['user']; ?>" size="32" maxlength="50" />
        <span class="tiny">Full name</span> </p>
      <p>
        <label for="User">User Account</label>
        <input name="user_account" type="text" value="<?php echo $row_rsHardwareUpdate['user_account']; ?>" size="12" maxlength="12" />
        <span class="tiny">AD account</span> </p>
      <p>
        <label for="Division">Division</label>
        <select name="division">
          <?php 
do {  
?>
          <option value="<?php echo $row_rsDivision['division']?>" <?php if (!(strcmp($row_rsDivision['division'], $row_rsHardwareUpdate['division']))) {echo "SELECTED";} ?>><?php echo $row_rsDivision['division']?></option>
          <?php
} while ($row_rsDivision = mysqli_fetch_assoc($rsDivision));
?>
        </select
	>
      </p>
      <p>
        <label for="Location">Location</label>
        <select name="location">
          <?php 
do {  
?>
          <option value="<?php echo $row_rsLocation['location']?>" <?php if (!(strcmp($row_rsLocation['location'], $row_rsHardwareUpdate['location']))) {echo "SELECTED";} ?>><?php echo $row_rsLocation['location']?></option>
          <?php
} while ($row_rsLocation = mysqli_fetch_assoc($rsLocation));
?>
        </select>
      </p>
      <p>
        <label for="User">Cube</label>
        <input name="cube" type="text" value="<?php echo $row_rsHardwareUpdate['cube']; ?>" size="10" maxlength="10" />
      </p>
      <p>
        <label for="Division">Field Address</label>
        <textarea name="field_address" cols="20" rows="3" wrap="physical"><?php echo $row_rsHardwareUpdate['field_address']; ?></textarea>
        <span class="tiny">Field address + phone</span> </p>
      <p>
        <label for="Division">Comments</label>
        <textarea name="comments" cols="30" rows="3"><?php echo $row_rsHardwareUpdate['comments']; ?></textarea>
      </p>


      <!-- ... Rest of the form ... -->

    <p class="submit">
      <input type="submit" value="Update record" />
    </p>
  </fieldset>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="asset_hardware_id" value="<?php echo htmlspecialchars($row_rsHardwareUpdate['asset_hardware_id']); ?>">
</form>
<?php include('includes/footer.php'); ?>
<?php
mysqli_free_result($rsHardwareUpdate);
mysqli_free_result($rsVendors);
mysqli_free_result($rsHardwarePlatform);
mysqli_free_result($rsHardwareType);
mysqli_free_result($rsDivision);
mysqli_free_result($rsLocation);
mysqli_free_result($rsHardwareStatus);
mysqli_free_result($rsMonitorSize);
?>