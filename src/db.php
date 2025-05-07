<?php
header('Content-Type: text/html; charset=utf-8');

$host = 'db'; 
$db = 'shop';
$user = 'root';
$pass = 'example';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        echo "ID: " . htmlspecialchars($product['ID']) . "<br>";
        echo "Name: " . htmlspecialchars($product['Name']) . "<br>";
        echo "Price: " . htmlspecialchars($product['Price']) . "<br>";
        echo "Image: " . htmlspecialchars($product['Image']) . "<br>";
        echo "Description: " . htmlspecialchars($product['Description']) . "<br><br>";
    }

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
