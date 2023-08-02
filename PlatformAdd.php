<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Platform</title>
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

        .container h1 {
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

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #9c27b0;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #e91e63;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add Platform</h1>
        <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <div class="form-group">
                <label for="platform">Platform</label>
                <input type="text" name="platform" id="platform" required>
            </div>
            <div class="form-group">
                <label for="comments">Comments</label>
                <textarea name="comments" id="comments" cols="30" rows="6"></textarea>
            </div>
            <button type="submit" class="submit-btn">Insert Record</button>
            <input type="hidden" name="MM_insert" value="form1">
        </form>
    </div>
</body>

</html>
