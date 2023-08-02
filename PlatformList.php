<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platforms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
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

        .table-container {
            max-width: 600px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 5px 10px;
            font-size: 14px;
            color: #ffffff;
            background-color: #e91e63;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #9c27b0;
        }

        .delete-btn {
            background-color: #f44336;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Platform List</h3>
        <div class="table-container">
            <table>
                <tr>
                    <th>Platform</th>
                    <th>Comments</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
                <?php do { ?>
                <tr>
                    <td><?php echo $row_rsPlatformList['platform']; ?></td>
                    <td><?php echo $row_rsPlatformList['comments']; ?></td>
                    <td><a href="PlatformUpdate.php?platform_id=<?php echo $row_rsPlatformList['platform_id']; ?>" class="action-btn">Update</a></td>
                    <td>
                        <form id="delRecord" name="delRecord" method="post" action="PlatformDelete.php?recordID=<?php echo $row_rsPlatformList['platform_id']; ?>">
                            <button type="submit" name="Submit" class="action-btn delete-btn">Delete This Record</button>
                        </form>
                    </td>
                </tr>
                <?php } while ($row_rsPlatformList = mysqli_fetch_assoc($rsPlatformList)); ?>
            </table>
        </div>
    </div>
</body>

</html>
