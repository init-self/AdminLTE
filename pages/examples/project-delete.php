<?php

require_once("../database/config.php");

$id = $_GET['id'];

$query = "SELECT Name FROM projects WHERE ID=" . $id; // query for dropping the whole project
if($stmt = $conn -> query($query))
{
    if($stmt -> rowCount() >= 1) // proceed only if row is found
    {
        $row = $stmt -> fetch(PDO::FETCH_ASSOC); // row for projects deletion
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project | Delete</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <form method="POST">
            <div class="text-center">
                <h1 class="display-4">Delete the project<em> <?php echo $row['Name'];?> </em>?</h1>
                <a href="./projects.php" class="btn btn-light m-3">No, Cancel!</a>
                <button type="submit" class="btn btn-danger m-3" name="delete" id="delete">Yes, Delete!</button>
                <?php
                /**
                 * GET the id of the project to delete and run the query to delete the file
                 */
                    // check if delete request is also made
                    if(isset($_GET['id']) && !empty($_GET['id']) && isset($_POST['delete'])) 
                    {
                        $id = trim($_GET['id']);
                        $query = "DELETE FROM projects WHERE Id = " . $id; // make the query
                        $query_budget = "DELETE FROM budgets WHERE Id = " . $id;
                    
                        $stmt = $conn -> query($query);
                        $stmt_budget = $conn -> query($query_budget);
                    
                        if($stmt && $stmt_budget)
                        {
                            header("location: ./projects.php"); // redirect on successful deletion
                            exit();
                        }
                        else
                        {
                            echo "Error deleting the record! Please try again!";
                        }
                    }
                ?>
                <br><br><br>
                <i class="bi bi-trash-fill" style=" display: block; font-size: 10rem"></i>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
</body>
</html>