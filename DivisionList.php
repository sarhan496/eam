<?php require_once('Connections/eam.php'); ?>
<?php // Enterprise Asset Management - Sarhan // 
$connection = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$query_rsDivisionList = "SELECT * FROM division";
$rsDivisionList = $connection->query($query_rsDivisionList);

if (!$rsDivisionList) {
    die("Error executing query: " . $connection->error);
}

$row_rsDivisionList = $rsDivisionList->fetch_assoc();
$totalRows_rsDivisionList = $rsDivisionList->num_rows;
?>

<?php 
$pageTitle = "Divisions";
include('includes/header.php');
?>
<h3>Division List</h3>

<table border=1 width="900" class="table1">
  <tr>
    <th>Division</th>
    <th>Comments</th>
    <th>Update</th>
    <th>Delete</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsDivisionList['division']; ?></td>
      <td><?php echo $row_rsDivisionList['comments']; ?></td>
      <td><a href="DivisionUpdate.php?recordID=<?php echo $row_rsDivisionList['id']; ?>">Update</a></td>
      <td>
        <form id="delRecord" name="delRecord" method="post" action="DivisionDelete.php?recordID=<?php echo $row_rsDivisionList['id']; ?>">
           <center><input name="Submit" type="submit" class="red" value="Delete This Record" /></center>
        </form>      
      </td>
    </tr>
    <?php } while ($row_rsDivisionList = $rsDivisionList->fetch_assoc()); ?>
</table>
<table class="pagination">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table> 

<?php include('includes/footer.php'); ?>
<?php
$connection->close();
?>
