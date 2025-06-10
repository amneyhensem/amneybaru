<?php
// Retrieve POST data safely
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$password = $_POST['password'];
$number = $_POST['number'];

// Database connection parameters
$servername = 'localhost';
$username = 'root';
$passwordDB = ''; // Assuming no password for local dev; change as needed
$dbname = 'donasi';

// Create connection
$conn = new mysqli($servername, $username, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} 

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO registration (firstName, lastName, gender, email, password, number) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("sssssi", $firstName, $lastName, $gender, $email, $password, $number);

// Execute statement
if ($stmt->execute()) {
    echo "Registration successful.";
} else {
    echo "Error executing query: " . $stmt->error;
}

// Close resources
$stmt->close();
$conn->close();
?>
