<?php
$host = 'db';
$db = 'shop';
$user = 'root';
$pass = 'example';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Проверяем существует ли таблица
    $tableExists = $pdo->query("SHOW TABLES LIKE 'products'")->fetch();
    
    if (!$tableExists) {
        $pdo->exec("CREATE TABLE products (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(100) NOT NULL,
            Price DECIMAL(10,2) NOT NULL,
            Image VARCHAR(100),
            Description TEXT
        )");
        
    }
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>