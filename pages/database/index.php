<?php

require_once('./config.php');


$data = array();

// debugging data
// $email = "ad123@asd.asd";
// $password = "asdasd";

function validate($email)
{
    $pattern = "<^\w+([\.-]?\w)+@\w+([\.]?\w)+(\.[a-zA-Z]{2,3})$>i"; // format for type: student-uid.CSE@school.alumni.edu
    if (preg_match($pattern, $email) == 0)
    {
        $GLOBALS['data']['errors'] = "Invalid Email! Please enter a valid email. ";
        $GLOBALS['data']['errorPoint'] = 'email';
        return false;
    }
    return true;
}



if(isset($_REQUEST['email']) && isset($_REQUEST['password']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password']))
{
    if(validate($_REQUEST['email']))
    {   
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        
        $query = "SELECT Name, Password FROM signup WHERE Username = :email";

        if ($stmt = $conn -> prepare($query))
        {
            $data['email'] = md5($email);
            if($stmt -> execute(array(":email" => md5($email))) && $stmt -> rowCount() != 0)
            {
                $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                $data['pass'] = md5($password);
                // check password match
                if(md5($password) == $row['Password'])
                {
                    $data['auth'] = true;
                    $data['name'] = $row['Name'];
                    $data['records'] = $stmt -> rowCount();

                    // session variables
                    $_SESSION['username'] = $email;
                    $_SESSION['auth'] = true;
                    $_SESSION['timeout'] = time();
                }else
                {
                    $data['auth'] = false;
                    $data['errors'] = "Invalid Credentials! Please try again. ";
                }
            }else
            {
                $data['auth'] = false;
                $data['errors'] = "No Record Found!";
            }
            unset($stmt);
        }
        $conn = null;
    }else
    {
        $data['auth'] = false;
    }
}else
{
    $data['auth'] = false;
    $data['errors'] = "Connection timed out. Please try again. ";
}


echo json_encode($data);


?>