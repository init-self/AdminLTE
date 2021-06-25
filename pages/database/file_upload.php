<?php
session_start();
ob_start();

require_once("./config.php");

$data = array();
$file_names = "";
$file_sizes = "";

if(isset($_POST['submit']))
{
    // Count total files
    $_fileCount = count($_FILES['inputFile']['name']);

    // Looping through all files to upload
    for($i = 0; $i < $_fileCount; $i++)
    {
        $filename = $_FILES['inputFile']['name'][$i]; // get file name
        $filesize = $_FILES['inputFile']['size'][$i]; // get file size

        $filedir = "../uploads/" . $filename; // upload directory

        // Strings of file_names and file_sizes to update in database
        $file_names .= $filename . ",";
        $file_sizes .= $filesize . ",";

        // Upload file
        if(move_uploaded_file($_FILES['inputFile']['tmp_name'][$i], $filedir))
        {
            $upload = true; // set flag
        }else
        {
            $upload = false; // set flag
        }
    }
    // check flag and insert names and sizes into database
    if($upload)
    {
        $id = trim($_GET['id']);
        $query = "UPDATE projects SET Files = :files, FileSize = :fileSize WHERE Id = :id;";
        if($stmt = $conn -> prepare($query))
        {
            if($stmt -> execute(array(":files" => $file_names, ":fileSize" => $file_sizes ,":id" => $id)))
            {
                $data['upload'] = $flag = true;
            }else
            {
                $data['upload'] = $flag = false;
            }
        }
        sleep(1);
    }
}
?>

<!-- View | Template -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <!-- Bootstrap 5  -->
    <link rel="stylesheet" href="../../plugins/bootstrap5/css/bootstrap.min.css">

</head>
<style>
    .container
    {
        width: 800px;
    }
</style>
<body>
    <div class="container">
        <div class="messageBox">
            <?php 
            if(isset($flag) && $flag == true)
            {
                echo "<div class=\"alert alert-success\" role=\"alert\">";
                echo "<p class=\"text-success display-6\">Files saved successfully <i class=\"bi bi-check2-circle\"></i>";
                echo "</div>";
                unset($_SESSION['success']);
                session_destroy();
                header("location: ../examples/project-edit.html?id=" . $id);
                ob_end_flush();
                exit("Could Not Redirect!");
            }
            ?>
        </div>
    <div class="container mt-5">
        <div class="display-4 m-4">
            <form method="POST" action='' enctype='multipart/form-data'>
                <p class="text-dark text-center">
                    Upload files to the Server 
                    <input type="file" class="form-control file_input mt-4" id="inputFile" name="inputFile[]" multiple>
                </p>
                <input type="submit" class="btn btn-dark d-block" id="submit" name="submit" value="Upload">
            </form>
        </div>
    </div>


    <!-- Bootstrap 5 -->
    <script src="../../plugins/bootstrap5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

