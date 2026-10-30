
<?php
$host = "localhost";
$user = "root";
$password = null;
$database = "College";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connection successful";
    echo "<br>";

    $result = $conn->query("SHOW TABLES");
    $table = $result->fetchAll(PDO::FETCH_COLUMN);

    print_r($table);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
