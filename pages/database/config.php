<?php

define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "adminlte");
define("TABLE_NAME", "projects");


try
{
    $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo ("Connection made successfully...");
}catch(PDOException $e)
{
    echo "Error connecting to server. Please try again! <br><br>";
    echo "Message: " . $e -> getMessage();
}


?>