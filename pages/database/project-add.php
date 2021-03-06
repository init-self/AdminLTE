<?php

require_once("./config.php");


$data = array();

// debugging data
// $name = "Name";
// $desc = "Description";
// $status = "Status";
// $client = "Client";
// $leader = "Leader";
// $estimatedBudget = 10000;
// $totalSpent = 1000;
// $duration = 15;



// checck the request flag from project add page
if(isset($_REQUEST['flag']) && $_REQUEST['flag'] === "insert")
{
    // extract the variables
    $validate = $_REQUEST['validate'];
    $name = $_REQUEST['name'];
    $desc = $_REQUEST['desc'];
    $status = $_REQUEST['status'];
    $client = $_REQUEST['client'];
    $leader = $_REQUEST['leader'];
    $estimatedBudget = $_REQUEST['budget'];
    $totalSpent = $_REQUEST['spent'];
    $duration = $_REQUEST['duration'];

    // is insertion request made ?
    if(isset($_REQUEST['flag']) && $_REQUEST['flag'] === 'insert')
    {
        // make the queries to insert data into projects  and budgets
        $query = "INSERT INTO projects (Name, Description, Status, ClientCompany, ProjectLeader) VALUES(:name, :desc, :status, :client, :leader);";
        $query_budget = "INSERT INTO budgets (Budget, Spent, Duration) VALUES (:estimatedBudget, :totalSpent, :duration);";

        $stmt = $conn -> prepare($query);
        $stmt_budget = $conn -> prepare($query_budget);
        if($stmt && $stmt_budget)
        {
            // update the values
            $stmt -> bindParam(":name", $name);
            $stmt -> bindParam(":desc", $desc);
            $stmt -> bindParam(":status", $status);
            $stmt -> bindParam(":client", $client);
            $stmt -> bindParam(":leader", $leader);
            $stmt_budget -> bindParam(":estimatedBudget", $estimatedBudget);
            $stmt_budget -> bindParam(":totalSpent", $totalSpent);
            $stmt_budget -> bindParam(":duration", $duration);

            if($stmt -> execute() && $stmt_budget -> execute())
            {
                $data['success'] = true; // success
            }else
            {
                $data['success'] = false; // failure
                $data['message'] = "Could not save records. Please try again. ";
            }
        }
    }else
    {
        // respond message for incorrect flag request
        $data['message'] = "Insertion Error! Please try again";
    }
}

// return data
echo json_encode($data);


?>