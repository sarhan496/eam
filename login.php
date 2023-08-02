<?php
require_once('Connections/eam.php');

session_start();

// Enterprise Asset Management - Sarhan //

function GetSQLValueString($mysqli, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $mysqli->real_escape_string($theValue) . "'" : "NULL";
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

// *** Validate request to login to this site.
if (isset($_POST['myusername'])) {
    $loginUsername = $_POST['myusername'];
    $password = $_POST['mypassword'];
    $MM_fldUserAuthorization = "role";
    $MM_redirectLoginSuccess = "index.php";
    $MM_redirectLoginFailed = "login.php";
    $MM_redirecttoReferrer = false;

    $mysqli = new mysqli("localhost", "root", "", "eam"); // Replace the credentials with your database details

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $LoginRS__query = "SELECT username, password, role FROM members WHERE username=? AND password=?";
    $stmt = $mysqli->prepare($LoginRS__query);
    $stmt->bind_param("ss", $loginUsername, $password);
    $stmt->execute();
    $LoginRS = $stmt->get_result();

    $loginFoundUser = $LoginRS->num_rows;

    if ($loginFoundUser) {
        $row = $LoginRS->fetch_assoc();
        $loginStrGroup = $row['role'];

        // Declare two session variables and assign them
        $_SESSION['MM_Username'] = $loginUsername;
        $_SESSION['MM_UserGroup'] = $loginStrGroup;

        if (isset($_SESSION['PrevUrl']) && false) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
        }
        $stmt->close();
        $mysqli->close();
        header("Location: " . $MM_redirectLoginSuccess);
        exit;
    } else {
        $stmt->close();
        $mysqli->close();
        header("Location: " . $MM_redirectLoginFailed);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #9c27b0, #e91e63);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333333;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .login-form input[type="submit"] {
            padding: 10px;
            font-size: 16px;
            background-color: #333333;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-form input[type="submit"]:hover {
            background-color: #444444;
        }

        .error-message {
            color: #ff0000;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
        h1 {
      font-weight: bold;
      color: white;
      background: linear-gradient(to bottom right, #9c27b0, #e91e63);
      padding: 20px;
      text-align: center;
    }
    </style>
</head>
 
<body>
    
    <div class="login-container">
    <center><img src="images/PUNB.png" alt="PUNB Logo";"></center>
    <h1 style="font-weight: bold; color: #eff3fb;" >PUNB Asset Tagging Web Apps</h1>
        <center><h2>Login</h2></center>
        <form class="login-form" id="form1" name="form1" method="post" action="">
            <label for="myusername">Username:</label>
            <input type="text" name="myusername" id="myusername" required />
            <label for="mypassword">Password:</label>
            <input type="password" name="mypassword" id="mypassword" required />
            <input type="submit" name="Submit" id="Submit" value="Login" />
            <?php
            // Display error message here if needed
            ?>
        </form>
    </div>
</body>

</html>

