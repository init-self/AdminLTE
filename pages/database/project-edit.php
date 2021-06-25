<?php

require_once("../database/config.php");

$data = array();
$file_list_names = array();
$file_list_sizes = array();

$id = trim($_REQUEST['id']);



/**-------------------------------------------SHOW RECORDS------------------------------------------------------
 * 
 * 
 * To show the data when user clicks on the edit button from projects page
 * 
 * get the id paramter from URL
 * get all the records from the database
 * respond with data of all the records
 */

// check the flag to see what request is being posted

if(isset($_REQUEST['flag']) && $_REQUEST['flag'] === "show")
{
	$id = trim($id); // collect the id form URL

	// make the sql query
	$query = "SELECT * FROM projects WHERE id = :id;";
	$query_budget = "SELECT * FROM budgets WHERE id = :id;";

	$stmt = $conn -> prepare($query);
	$stmt_budget = $conn -> prepare($query_budget);


	if ($stmt && $stmt_budget)
	{
		$stmt -> bindParam(":id", $id);
		$stmt_budget -> bindParam(":id", $id);

		if ($stmt -> execute() && $stmt_budget -> execute())
		{
			if($stmt -> rowCount() >= 1) // if data is found
			{
				// respond with the records from the database
				$row = $stmt -> fetch(PDO::FETCH_ASSOC);
				$row_budget = $stmt_budget -> fetch(PDO::FETCH_ASSOC);

                $data['projects'] = $row;
                $data['budgets'] = $row_budget;
				$data['records'] = true;
			}else
			{
				// if data not found
				$data['records'] = false;
			}
		}
	}
}else
{
	/**------------------------------------UPDATE------------------------------------
	 * 
	 * 
	 * To UPDATE records into the database when user posts data (clicks 'Save Changes' button)
	 * 
	 * check the flag to which request is being made
	 * collect all the variables
	 * make the query 
	 * respond with success
	 */

	function validate($data)
	{
		// validate email
		$pattern = "<^[^0-9]$>";
		if(preg_match($pattern, $data) == 0)
		{
			$GLOBALS['data']['errors'] = "Invalid Project Leader or Client Company name.";
			$GLOBALS['data']['success'] = false;
			return false;
		}
		return true;
	}
    // check the flag
	if(isset($_REQUEST['flag']) && $_REQUEST['flag'] === "update")
	{
		
		if($_REQUEST['client'] && $_REQUEST['leader'] )
		{
			// collect all the variables
			$name = $_REQUEST['name'];
			$desc = $_REQUEST['desc'];
			$status = $_REQUEST['status'];
			$client = $_REQUEST['client'];
			$leader = $_REQUEST['leader'];
			$estimatedBudget = $_REQUEST['budget'];
			$totalSpent = $_REQUEST['spent'];
			$duration = $_REQUEST['duration'];
			$id = $_REQUEST['id'];
			
			// make the query and update the records
			$query = "UPDATE projects SET Name=:name, Description=:desc, Status=:status, ClientCompany=:client, ProjectLeader=:leader WHERE Id=:id;";
			$query_budget = "UPDATE budgets SET Budget=:budget, Spent=:spent, Duration=:duration WHERE Id=:id;";

			if(($stmt = $conn -> prepare($query)) && ($stmt_budget = $conn -> prepare($query_budget)))
			{
			
				$stmt -> execute(array(":name" => $name, ":desc" => $desc, ":status" => $status, ":client" => $client, ":leader" => $leader, ":id" => $id));
				$stmt_budget -> execute(array(":budget" => $estimatedBudget, ":spent" => $totalSpent, ":duration" => $duration, ":id" => $id));

				// On success, respond with success else failure
				if($stmt && $stmt_budget)
				{
					$data['success'] = true;
				}
			}else
			{
				$data['success'] = false;
			}
		}
	}else
	{
		$data['errors'] = "Looks like we are having some kind of problem. Please try again!";
	}
}

unset($stmt);
$conn = null;

echo json_encode($data);
?>