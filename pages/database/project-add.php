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




if(isset($_REQUEST['flag']) && $_REQUEST['flag'] === "insert")
{
    $validate = $_REQUEST['validate'];
    $name = $_REQUEST['name'];
    $desc = $_REQUEST['desc'];
    $status = $_REQUEST['status'];
    $client = $_REQUEST['client'];
    $leader = $_REQUEST['leader'];
    $estimatedBudget = $_REQUEST['budget'];
    $totalSpent = $_REQUEST['spent'];
    $duration = $_REQUEST['duration'];

    if(isset($_REQUEST['flag']) && $_REQUEST['flag'] === 'insert')
    {
        $query = "INSERT INTO projects (Name, Description, Status, ClientCompany, ProjectLeader) VALUES(:name, :desc, :status, :client, :leader);";
        $query_budget = "INSERT INTO budgets (Budget, Spent, Duration) VALUES (:estimatedBudget, :totalSpent, :duration);";

        $stmt = $conn -> prepare($query);
        $stmt_budget = $conn -> prepare($query_budget);
        if($stmt && $stmt_budget)
        {
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
                $data['success'] = true;
            }else
            {
                $data['success'] = false;
                $data['message'] = "Could not save records. Please try again. ";
            }
        }
    }else
    {
        // respond message
        $data['message'] = "Insertion Error! Please try again";
    }
}

echo json_encode($data);


?>