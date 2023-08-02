<?php
// Assuming you have already defined $database_eam and created a mysqli connection

require_once('Connections/eam.php');

// Enterprise Asset Management - sarhan // 
if (!isset($_SESSION)) {
    session_start();
}

$MM_authorizedUsers = "admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {
    // For security, start by assuming the visitor is NOT authorized. 
    $isValid = false;

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
    // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
    if (!empty($UserName)) { 
        // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
        // Parse the strings into arrays. 
        $arrUsers = explode(",", $strUsers); 
        $arrGroups = explode(",", $strGroups); 
        if (in_array($UserName, $arrUsers)) { 
            $isValid = true; 
        } 
        // Or, you may restrict access to only certain users based on their username. 
        if (in_array($UserGroup, $arrGroups)) { 
            $isValid = true; 
        } 
        if (($strUsers == "") && false) { 
            $isValid = true; 
        } 
    } 
    return $isValid; 
}

$MM_restrictGoTo = "access.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
    $MM_referrer .= "?" . $QUERY_STRING;
    $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: ". $MM_restrictGoTo); 
    exit;
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    // Removed HTML comment, as it was causing a syntax error
    // $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue; 

    global $eam;

    $theValue = $eam->real_escape_string($theValue);

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

$database_eam = "eam"; // Replace "YourDBName" with your actual database name
$eam = new mysqli("localhost", "root", "", $database_eam); // Replace the credentials with your database details

if ($eam->connect_error) {
    die("Connection failed: " . $eam->connect_error);
}

$query_rsCompanyName = "SELECT company_name FROM company";
$rsCompanyName = $eam->query($query_rsCompanyName) or die($eam->error);
$row_rsCompanyName = $rsCompanyName->fetch_assoc();
$totalRows_rsCompanyName = $rsCompanyName->num_rows;

$pageTitle = "Home";
?>
<?php include('includes/header.php'); ?>
<!-- ... Rest of the code ... -->
