<? 
session_start();
session_destroy();
?>
<?php require_once('Connections/eam.php'); ?>
<?php include("includes/header.php"); ?>
  
   <div id="mainContent">
    <div style="padding:60px 0; text-align:center;">
        <fieldset>
        <p>&nbsp;</p>
        <h1>Time to go home!</h1>
        <p>&nbsp;</p>
          <p><a href="login.php">Boss called - Log in again?</a></p>
		<p>&nbsp;</p>
        </fieldset>
    </div>
</div>

<?php include("includes/footer.php"); ?>
