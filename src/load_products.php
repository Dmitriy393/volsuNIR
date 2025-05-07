<?php
$host = 'db'; 
$db = 'shop';
$user = 'root';
$pass = 'example';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

    $stmt = $pdo->prepare("SELECT * FROM products LIMIT :offset, :limit");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['products' => $products]);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
