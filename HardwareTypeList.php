<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 

$database_eam = new mysqli('localhost', 'root', '', 'eam');
if ($database_eam->connect_error) {
    die("Connection failed: " . $database_eam->connect_error);
}

$query_rsHardwareTypes = "SELECT * FROM assets_hardware_type";
$rsHardwareTypes = $database_eam->query($query_rsHardwareTypes);
$row_rsHardwareTypes = $rsHardwareTypes->fetch_assoc();
$totalRows_rsHardwareTypes = $rsHardwareTypes->num_rows;
?>
<?php $pageTitle = "List Hardware Types"; ?>
<?php include('includes/header.php'); ?>
<h3>List Hardware Types</h3>
<table width="600" class="table1">
    <tr>
        <th>Hardware Types</th>
        <th>Comments</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>
    <?php do { ?>
        <tr>
            <td><?php echo $row_rsHardwareTypes['assets_hardware_type']; ?></td>
            <td><?php echo $row_rsHardwareTypes['comments']; ?></td>
            <td><a href="HardwareTypeUpdate.php?recordID=<?php echo $row_rsHardwareTypes['id']; ?>">Update</a></td>
            <td>
                <form id="delRecord" name="delRecord" method="post" action="HardwareTypeDelete.php?recordID=<?php echo $row_rsHardwareTypes['id']; ?>">
                    <input name="Submit" type="submit" class="red" value="Delete This Record" />
                </form>
            </td>
        </tr>
    <?php } while ($row_rsHardwareTypes = $rsHardwareTypes->fetch_assoc()); ?>
</table>
<table class="pagination">
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
<?php include('includes/footer.php'); ?>
<?php
$rsHardwareTypes->free_result();
$database_eam->close();
?>
