<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Graham Fisk - Sarhan // 
function GetSQLValueString($conn, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . mysqli_real_escape_string($conn, $theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $id = intval($_POST['id']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    $insertSQL = sprintf(
        "INSERT INTO location (location, id, comments) VALUES ('%s', %d, '%s')",
        $location,
        $id,
        $comments
    );

    $Result1 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));

    mysqli_close($conn);

    $insertGoTo = "LocationList.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}
?>

<?php $pageTitle = "Add Location"; ?>
<?php include('includes/header.php'); ?>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <fieldset>
        <legend>Add Location</legend>
        <p>
            <label for="Vendor">Location</label>
            <input type="text" name="location" value="" size="32">
        </p>
        <p>
            <label for="Comments">Comments</label>
            <textarea name="comments" cols="30" rows="6" id="comments"></textarea>
        </p>
        <p class="submit">
            <input type="submit" value="Insert record">
        </p>
    </fieldset>

    <input type="hidden" name="MM_insert" value="form1">
</form>

<?php include('includes/footer.php'); ?>
