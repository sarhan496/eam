<?php
require_once('Connections/eam.php');

// Select the database (assuming it is already connected in 'eam.php')
mysqli_select_db($eam, $database_eam);

// Prepare the query to fetch the locations list
$query_rsLocationsList = "SELECT * FROM location ORDER BY location ASC";

// Execute the query
$rsLocationsList = mysqli_query($eam, $query_rsLocationsList);
if (!$rsLocationsList) {
    die("Error: " . mysqli_error($eam));
}

$totalRows_rsLocationsList = mysqli_num_rows($rsLocationsList);
?>
<?php $pageTitle = "Locations"; ?>
<?php include('includes/header.php'); ?>
<h3>Location List</h3>
<table width="600" class="table1">
  <tr>
    <th>Location</th>
    <th>Comments</th>
    <th>Update</th>
    <th>Delete</th>
  </tr>
  <?php while ($row_rsLocationsList = mysqli_fetch_assoc($rsLocationsList)) { ?>
    <tr>
      <td><?php echo $row_rsLocationsList['location']; ?></td>
      <td><?php echo $row_rsLocationsList['comments']; ?></td>
      <td><a href="LocationUpdate.php?recordID=<?php echo $row_rsLocationsList['id']; ?>">Update</a></td>
      <td>
        <form id="delRecord" name="delRecord" method="post" action="LocationDelete.php?recordID=<?php echo $row_rsLocationsList['id']; ?>">
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

<?php include('includes/footer.php'); ?>
<?php
mysqli_free_result($rsLocationsList);
?>
