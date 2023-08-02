<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
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

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        .submit-btn {
            padding: 10px 20px;
            font-size: 14px;
            color: #ffffff;
            background-color: #e91e63;
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
        <h3>Update Platform</h3>
        <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <div class="form-group">
                <label for="platform_id">Platform ID:</label>
                <input type="text" name="platform_id" value="<?php echo $row_rsPlatformUpdate['platform_id']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="platform">Platform:</label>
                <input type="text" name="platform" value="<?php echo $row_rsPlatformUpdate['platform']; ?>">
            </div>
            <div class="form-group">
                <label for="comments">Comments:</label>
                <textarea name="comments" rows="3" id="comments"><?php echo $row_rsPlatformUpdate['comments']; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Update record" class="submit-btn">
            </div>
            <input type="hidden" name="MM_update" value="form1">
        </form>
    </div>
</body>

</html>
