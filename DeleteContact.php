<?php
// Get the incoming data
$inData = getRequestInfo();

//Extract the first name, last name, user id from incoming data
$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$userId = $inData["userId"];

//Connect to mysql database
$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

//Check for connection errors
if ($conn->connect_error){
    returnWithError($conn->connect_error);
}else{

    // Prepare an SQL statement to delete the contact by first name, last name, and ID
    $stmt = $conn->prepare("DELETE from Contacts WHERE FirstName = ? AND LastName = ? AND UserId=?");
    //Bind the parameters (firstName, lastName, id) to the Sql statement
    $stmt->bind_param("ssi", $firstName, $lastName, $userId);

    //Execute the SQL statement
    if ($stmt->execute()){
        if ($stmt->affected_rows > 0){
            //If the contact was sucessfully deleted, return a success message
            returnWithInfo("Contact deleted successfully.");
        }else{
            //If no rows were affected, it means the userId was not found
            returnWithError("No contact found with the given id.");
        }
}else{
    //If there was an error during execution, return an error message.
    returnWithError("Error deleting contact.");
}
//Close the statement and connection to free resources
$stmt->close();
$conn->close();
}

// Function to decode the incoming JSON request
function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

// Function to send JSON data as a response
function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    echo $obj;
}

// Function to return an error message in JSON format
function returnWithError($err)
{
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

// Function to return a success message in JSON format
function returnWithInfo($message)
{
    $retValue = '{"message":"' . $message . '","error":""}';
    sendResultInfoAsJson($retValue);
}

?>