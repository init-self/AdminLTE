<?php

require_once('./config.php');


$data = array();

// debugging data
// $email = "ad123@asd.asd";
// $password = "asdasd";

// validate the email on user login
function validate($email)
{
    // format for type: student-uid.CSE@school.alumni.edu
    $pattern = "<^\w+([\.-]?\w)+@\w+([\.]?\w)+(\.[a-zA-Z]{2,3})$>i"; 
    if (preg_match($pattern, $email) == 0)
    {
        $GLOBALS['data']['errors'] = "Invalid Email! Please enter a valid email. ";
        $GLOBALS['data']['errorPoint'] = 'email';
        return false;
    }
    return true; // if no error is caught
}


// check if email and password is present and not empty 
if(isset($_REQUEST['email']) && isset($_REQUEST['password']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password']))
{
    // then validate the email format
    if(validate($_REQUEST['email']))
    {   
        // extract variables
        $email = $_REQUEST['email']; 
        $password = $_REQUEST['password'];
        
        $query = "SELECT Name, Password FROM signup WHERE Username = :email"; // query to match the details

        if ($stmt = $conn -> prepare($query))
        {
            /**
             * match the encrypted email to the encrytpted password and on success proceed to projects page
             */
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
                    // on failure of matching
                    $data['auth'] = false;
                    $data['errors'] = "Invalid Credentials! Please try again. ";
                }
            }else
            {
                // on No Account!
                $data['auth'] = false;
                $data['errors'] = "No Record Found!";
            }
            unset($stmt);
        }
        $conn = null;
    }else
    {
        // if email format is wrong
        $data['auth'] = false;
    }
}else
{
    // if credentials are empty
    $data['auth'] = false;
    $data['errors'] = "Invalid Credentials. Please try again. ";
}


echo json_encode($data);


?>