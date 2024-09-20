<?php
//Get incoming data
$inData = getRequestInfo();

//Extract necessary fields
$firstName = $inData['firstName'];
$lastName = $inData['lastName'];
$login = $inData['login'];
$password = $inData['password'];

// Connect to the mysql database
$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

// Check for connection errors
if ($conn->connect_error){
    returnWithError($conn->connect_error);
}else{
    $stmt = $conn->prepare("INSERT into Users (FirstName, LastName, Login, Password) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $login, $password);

    // Execute the sql statement
    if ($stmt->execute()){
        //If successful, return a success response
        returnWithInfo("User registered successfully");
    }else{
         // If there was an error during execution, return an error message
         returnWithError("Error registering user");
    }

     // Close the statement and connection to free resources
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