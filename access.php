<?php
require_once('Connections/eam.php');

// Enterprise Asset Management - Sarhan //

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    // Do not use get_magic_quotes_gpc(), as it is deprecated
    // $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue; 

    $mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $theValue = $mysqli->real_escape_string($theValue);

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

    // Do not close the connection here
    return $theValue;
}

session_start();

// ... Rest of the code remains the same ...
?>
