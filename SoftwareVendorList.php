<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Graham Fisk - BigSmallweb.com - 2012 // 

$conn = new mysqli($hostname_eam, $username_eam, $password_eam, $database_eam);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query_rsSoftwareVendors = "SELECT * FROM vendors_software";
$rsSoftwareVendors = $conn->query($query_rsSoftwareVendors);

$pageTitle = "Software Vendor List";
include('includes/header.php');
?>
<h3>Software Vendor List</h3>

<table width="600" class="table1">
  <tr>
    <th>vendor</th>
    <th>comments</th>
    <th>Update</th>
    <th>Delete</th>
  </tr>
  <?php while ($row_rsSoftwareVendors = $rsSoftwareVendors->fetch_assoc()) { ?>
    <tr>
      <td><?php echo htmlspecialchars($row_rsSoftwareVendors['vendor']); ?></td>
      <td><?php echo htmlspecialchars($row_rsSoftwareVendors['comments']); ?></td>
      <td><a href="SoftwareVendorUpdate.php?recordID=<?php echo $row_rsSoftwareVendors['vendor_id']; ?>">Update</a></td>
      <td>
        <form id="delRecord" name="delRecord" method="post" action="SoftwareVendorDelete.php?recordID=<?php echo $row_rsSoftwareVendors['vendor_id']; ?>">
          <input name="Submit" type="submit" class="red" value="Delete This Record" />
        </form>
      </td>
    </tr>
  <?php } ?>
</table>

<table class="pagination">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<?php
$rsSoftwareVendors->free_result();
$conn->close();
include('includes/footer.php');
?>
