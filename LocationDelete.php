<?php
require_once('Connections/eam.php');

// Check if the record ID is provided and not empty
if (isset($_GET['recordID']) && !empty($_GET['recordID'])) {
    // Sanitize the record ID to prevent SQL injection
    $recordID = intval($_GET['recordID']);

    // Prepare the delete query
    $deleteSQL = "DELETE FROM location WHERE id = $recordID";

    // Execute the query
    $result = mysqli_query($eam, $deleteSQL);
    if (!$result) {
        die("Error: " . mysqli_error($eam));
    }

    // Redirect to the location list page after successful deletion
    header("Location: LocationList.php");
    exit();
}

$pageTitle = "Delete Location";
include('includes/header.php');
?>
<h3>Location Delete </h3>
<form id="delRecord" name="delRecord" method="post" action="">
    <input type="submit" name="Submit" value="Delete Record" />
</form>
<?php include('includes/footer.php'); ?>
