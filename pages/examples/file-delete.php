<?php

require_once("../database/config.php");

$id = $_GET['id']; // get the project id from URL


if(isset($_GET['flag']) && $_GET['flag'] === 'file_deletion')
{
    /**
     * Part to delete only a particular file of the project
     * 
     * here we are only making the query and getting the records from database
     * get all the required values in order to make the deletion possible
     * the deletion will be executed on the user clicking on deletion button in project-edit page.
     */
    $data = array();

    $query = "SELECT Files, FileSize FROM projects WHERE Id = " . $id;
    if($stmt = $conn -> query($query))
    {
        if($stmt -> rowCount() >= 1) // proceed only of row is found
        {
            $row = $stmt -> fetch(PDO::FETCH_ASSOC); // row for file deletion
            $file = $_GET['file'];
            $fileSize = $row['FileSize'];

            // get file names and sizes from database
            $file_name = unserialize($row['Files']);
            $file_size = unserialize($row['FileSize']);

            // as file will always be present, directy replace the string with an empty string
            unset($file_name[array_search($file, $file_name)]); // trimming the extra space
            unset($file_size[array_search($fileSize, $file_size)]); // updating file sizes also

            $updated_files = serialize($file_name);
            $updated_files = serialize($file_size);

            $conn -> query("UPDATE projects SET Files = '" . $updated_files . "' WHERE Id = " . $id);
            $conn -> query("UPDATE projects SET FileSize = '" . $updated_sizes . "' WHERE Id = " . $id);
            $data['file_deletion'] = true;
            echo json_encode($data);

        }
    }
}else
{
    /**
     * Part to delete the whole project
     */
    $query = "SELECT Name FROM projects WHERE ID=" . $id; // query for dropping the whole project
    if($stmt = $conn -> query($query))
    {
        if($stmt -> rowCount() >= 1) // proceed only if row is found
        {
            $row = $stmt -> fetch(PDO::FETCH_ASSOC); // row for projects deletion
        }
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
    <!-- Google Font - Work Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@800&display=swap" rel="stylesheet"> 
</head>
<body>
    <div class="container mt-5">
        <form method="POST">
            <div class="text-center">
                <h1 class="" style="font-family: 'Work Sans' sans-serif; font-size: 3.5rem;">
                    Delete the
                    <?php
                    if(isset($_GET['file']))
                    {
                        // echo "file - <em><u>" . $row['Name']  . "</u></em>";
                    }else
                    {
                        echo "project - <em><u>" . $row['Name'] . "</u></em>";
                    }
                    ?>
                ?</h1>
                <a href="javascript:history.go(-1)" class="btn btn-light m-3">No, Cancel!</a>
                <button type="submit" class="btn btn-danger m-3" name="delete" id="delete">Yes, Delete!</button>
                <?php
                    if(isset($_GET['id']) && !empty($_GET['id']) && isset($_POST['delete']))
                    {
                        $id = trim($_GET['id']);
                        $query = "DELETE FROM projects WHERE Id = " . $id;
                        $query_budget = "DELETE FROM budgets WHERE Id = " . $id;
                    
                        $stmt = $conn -> query($query);
                        $stmt_budget = $conn -> query($query_budget);
                    
                        if($stmt && $stmt_budget)
                        {
                            echo "<div class=\"text-success\"> Project deleted successfully. </div>";
                            sleep(2);
                            header('location: ./projects.php');
                            exit();
                        }else
                        {
                            echo "<div class=\"text-danger\">Error deleting the record! Please try again!</div>";
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
    <script>
        
    </script>
</body>
</html>
<?php

?>