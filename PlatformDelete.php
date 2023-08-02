<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            color: #333333;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #e91e63;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #9c27b0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Platform Delete</h3>
        <form id="delRecord" name="delRecord" method="post" action="">
            <div class="form-group">
                <label for="confirmDelete">Are you sure you want to delete this record?</label>
            </div>
            <button type="submit" name="Submit" class="submit-btn">Delete Record</button>
        </form>
    </div>
</body>

</html>
