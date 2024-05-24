<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
$port = 3307; // Ovdje specificiraj port MySQL servera ako nije defaultni 3306

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Omogućuje detaljnije izvještavanje o greškama
$spoj = new mysqli($servername, $username, $password, $dbname, $port);

// Provjeri konekciju
if ($spoj->connect_error) {
    die("Došlo je do greške: " . $spoj->connect_error);
}
?>
