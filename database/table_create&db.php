<?php
include "connection.php";

if (!$conn) {
  die("Connection failed: " . $conn->connect_error);
}
/*
// creating table
$sql = "CREATE TABLE if not exists MyGuests12 (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
  } else {
    echo "Error creating table: " . $conn->error;
  }
  $conn->close();
*/


$a='ZUIdfgFGHSO';
$b='ZUIFhftGHSO';
$c='ZUIFGHSO';
$sql1 = "INSERT INTO MyGuests12 (firstname, lastname, email)
VALUES ('$a','$b', '$c')";

print_r($conn->query($sql1));die;

if ($conn->query($sql1) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql1 . "<br>" . $conn->error;
}
$conn->close();


?>
