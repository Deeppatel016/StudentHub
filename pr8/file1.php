<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "college";

$con = new mysqli($host, $user, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

echo "Connected successfully";
echo "<br>";

$result = $con->query("SHOW TABLES");

if ($result) {
    while ($row = $result->fetch_array()) {
        echo $row[0] . "<br>";
    }
}

$con->close();
?>