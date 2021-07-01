<?php

require_once("./config.php");


$_data = array("name" => $_REQUEST['name'], "email" => $_REQUEST['email'], "password" => $_REQUEST['password'], "check" => $_REQUEST['check']);

$data = array();


// validate the credentials
function validate($data)
{
    if(empty($data['name']) && empty($data['email']) && empty($data['password']) && empty($data['check']))
    {
        return false;
    }
    if(isset($data['name']))
    {
        $pattern = "<^([a-zA-Z_\s])+$>i";
        if(preg_match($pattern, $data['name']) == 0)
        {
            $GLOBALS['data']['errors'] = "Invalid Name! Please fill according to our standards. ";
            $GLOBALS['data']['errorPoint'] = "name";
            return false;
        }
    }
    if(isset($data['email']))
    {
        $pattern = "<^\w+([\.-]?\w)+@\w+([\.]?\w)+(\.[a-zA-Z]{2,3})$>i"; // format for type: student-uid.CSE@school.alumni.edu
        if (preg_match($pattern, $data['email']) == 0)
        {
            $GLOBALS['data']['errors'] = "Invalid Email! Please enter a valid email. ";
            $GLOBALS['data']['errorPoint'] = 'email';
            return false;
        }
    }
    return true;
}


/**
 * check if fields are filled
 * make the query to save the credentials
 * update the values
 * success
 */

// check the fields
if(isset($_data['name']) && isset($_data['email']) && isset($_data['password']))
{
    if(validate($_data)) // validation
    {
        // the query to save data
        $query = "INSERT INTO signup (Name, Username, Password) VALUES (:name, :email, :password);";

        if($stmt = $conn -> prepare($query))
        {
            if($stmt -> execute(array(':name' => $_data['name'], ':email' => md5($_data['email']), ':password' => md5($_data['password']))))
            {
                $data['success'] = true; // Voila! Happy face ☺
            }else
            {
                // Ufff! Errors and Bugs...
                $data['errors'] = "Could not save records. Please try again!"; 
            }
            unset($stmt);
        }else
        {
            // redirect to error page
            header("location: ./404.html");
        }
        $conn = null;
    }
}else
{
    // Backend error
    $data['errors'] = "Oops! Looks like we have some problem on our end. We will try to resolve soon. ";
}

echo json_encode($data);

?>