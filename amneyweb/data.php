<?php
$servername = "localhost"; $username = "root";
$password = "";
$database = "donasi";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$amount = $_POST['amount'];
$date = $_POST['date'];


 //INSERT DATA INTO THE DATABASE

 $sql = "INSERT INTO bayaran (name, email, gender, amount) 
 VALUES ('$name', '$email', '$gender', '$amount')";

 

 if ($conn->query($sql) === TRUE) {
 echo "<script>alert('Checkout complete');</script>";
 echo "<script>window.setTimeout(function(){ window.location.href = 'index.html'; }, 1000);</script>";
} else {
echo "Error: ". $sql, "<br>".$conn->error;
}
?>