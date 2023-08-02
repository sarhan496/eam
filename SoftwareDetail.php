<?php require_once('Connections/eam.php'); ?>
<?php
$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;

if (isset($_GET['pageNum_DetailRS1'])) {
    $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}

$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$mysqli = new mysqli($hostname_eam, $username_eam, $password_eam, $database_eam);
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

$recordID = $_GET['recordID'];
$query_DetailRS1 = "SELECT * FROM assets_software WHERE asset_software_id = $recordID";
$DetailRS1 = $mysqli->query($query_DetailRS1);

$row_DetailRS1 = $DetailRS1->fetch_assoc();

$totalRows_DetailRS1 = $DetailRS1->num_rows;

$totalPages_DetailRS1 = ceil($totalRows_DetailRS1 / $maxRows_DetailRS1) - 1;
?>

<?php $pageTitle = "Software Detail"; ?>
<?php include('includes/header.php'); ?>
    <table class="tableDetails1">
        <tr>
            <td style="font-size:100%">
                <fieldset>
                    <legend>Software Detail</legend>
                    <p>
                        <label for="Asset Id">Asset Id</label>
                        <?php echo $row_DetailRS1['asset_software_id']; ?> &nbsp;
                    </p>
                    <p>
                        <label for="Software">Software</label>
                        <?php echo $row_DetailRS1['asset']; ?> &nbsp;
                    </p>
                    <!-- Other details here -->
                    <hr />
                    <p>
                        <label for="Status">Status</label>
                        <?php echo $row_DetailRS1['status']; ?> &nbsp;
                    </p>
                    <!-- More details here -->
                </fieldset>
            </td>
            <td valign="top" style="font-size:90%;">
                <p>&nbsp;</p>
                <fieldset style="width:180px;">
                    <legend>Manage Record</legend>
                    <table class="table1">
                        <tr>
                            <td class="green">
                                <form id="upRecord" name="upRecord" method="post"
                                    action="SoftwareUpdate.php?recordID=<?php echo $row_DetailRS1['asset_software_id']; ?>">
                                    <input type="submit" name="Submit2" value="Edit This Record" />
                                </form>
                            </td>
                        </tr>
                    </table>
                    <br />
                    <table class="table1">
                        <tr>
                            <td class="red">
                                <form id="delRecord" name="delRecord" method="post"
                                    action="SoftwareDelete.php?recordID=<?php echo $row_DetailRS1['asset_software_id']; ?>">
                                    <input type="submit" name="Submit" value="Delete This Record" />
                                </form>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php include('includes/footer.php'); ?>
<?php
$DetailRS1->free_result();
$mysqli->close();
?>
