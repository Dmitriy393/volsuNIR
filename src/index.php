<?php
header('Content-Type: text/html; charset=utf-8');

$host = 'db'; 
$db = 'shop';
$user = 'root';
$pass = 'example';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Интернет-магазин</title>
</head>
<body>
    <h1>Добро пожаловать в наш интернет-магазин!</h1>
    <div class="product-container" id="product-container"></div>
    <div id="loading" style="display: none;">Загрузка...</div>

    <script>
        let offset = 0;
        const limit = 10;

        function loadProducts() {
            const loading = document.getElementById('loading');
            loading.style.display = 'block';

            fetch(`load_products.php?offset=${offset}&limit=${limit}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('product-container');
                    data.products.forEach(product => {
                        const productDiv = document.createElement('div');
                        productDiv.className = 'product';
                        productDiv.innerHTML = `
                            <img src="images/${product.Image}" alt="${product.Name}">
                            <h2>${product.Name}</h2>
                            <p>Цена: ${product.Price} руб.</p>
                            <a href="product.php?id=${product.ID}">Посмотреть</a>
                        `;
                        container.appendChild(productDiv);
                    });
                    offset += data.products.length;
                    loading.style.display = 'none';
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    loading.style.display = 'none';
                });
        }

        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                loadProducts();
            }
        });

        // Загрузка первых товаров при загрузке страницы
        loadProducts();
    </script>
</body>
</html>
