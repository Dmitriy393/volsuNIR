<?php
require_once 'db.php';

// Получаем текущую страницу из параметров URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 30;
$offset = ($page - 1) * $limit;

// Получаем товары
$stmt = $pdo->prepare("SELECT * FROM products WHERE Name IS NOT NULL AND Name != '' LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Проверяем количество товаров
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE Name IS NOT NULL AND Name != ''")->fetchColumn();
$totalPages = ceil($totalProducts / $limit);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товаров</title>
    <style>
        /* Общие стили */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        /* Контейнер для товаров */
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        /* Карточка продукта */
        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 15px;
            overflow: hidden;
            width: 250px; /* Фиксированная ширина карточки */
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px); /* Эффект при наведении */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Увеличенная тень при наведении */
        }

        /* Изображение продукта */
        .product-image {
            width: 100%;
            height: 200px; /* Фиксированная высота для всех изображений */
            object-fit: cover; /* Обеспечивает обрезку изображения по размеру контейнера */
        }

        /* Заголовок и описание продукта */
        .product-card h3 {
            font-size: 18px;
            margin: 10px;
            color: #333;
        }

        .price {
            color: #e67e22; /* Цвет цены */
            font-weight: bold;
            margin: 10px;
        }

        .description {
            font-size: 14px;
            color: #555;
            margin: 10px;
        }

        /* Пагинация */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 10px;
            text-decoration: none;
            color: #3498db;
        }

        .pagination a:hover {
            text-decoration: underline;
        }

        /* Загрузка */
        #loading {
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Каталог товаров</h1>
    
    <div class="products-container" id="products-container">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="/images/<?= htmlspecialchars($product['Image']) ?>" 
                         alt="<?= htmlspecialchars($product['Name']) ?>"
                         class="product-image">
                    <h3><?= htmlspecialchars($product['Name']) ?></h3>
                    <p class="price">Цена: <?= number_format($product['Price'], 2, '.', ' ') ?> руб.</p>
                    <?php if (!empty($product['Description'])): ?>
                        <p class="description"><?= htmlspecialchars($product['Description']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Товары отсутствуют.</p>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« Назад</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Вперед »</a>
        <?php endif; ?>
    </div>

    <div id="loading" style="display: none;">
        Загрузка товаров...
    </div>

    <script src="/js/script.js"></script>
</body>
</html>