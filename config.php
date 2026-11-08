
<?php

$host = "localhost";
$dbname = "ctwa_act1";
$username = "root";
$password = "";

try {

    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Philippine time (UTC+8)
    $conn->exec("SET time_zone = '+08:00'");

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}

?>
