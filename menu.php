<?php
require_once __DIR__ . '/autoload.php';

use App\Database;
use App\ProductRepository;
use App\ProductSeeder;

$database = new Database(__DIR__ . '/data/feane.db');
$repo = new ProductRepository($database);
if (count($repo->getAll()) === 0) {
    ProductSeeder::seedFromJson($repo, __DIR__ . '/products.json');
}

$selectedCategory = $_GET['category'] ?? '';
$products = $repo->getAll();
$categories = array_values(array_unique(array_column($products, 'category')));
$filteredProducts = $selectedCategory ? $repo->getByCategory($selectedCategory) : $products;
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Feane - Menu</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />

  <style>
    .menu-container {
      padding: 40px 0;
      background-color: #f9f9f9;
    }

    .filter-buttons {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 30px;
    }

    .filter-btn {
      padding: 10px 20px;
      border: 2px solid #ff6b6b;
      background-color: white;
      color: #ff6b6b;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
      text-decoration: none;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background-color: #ff6b6b;
      color: white;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }

    .product-card {
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
      padding: 20px;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .product-category {
      font-size: 12px;
      color: #ff6b6b;
      font-weight: 600;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .product-name {
      font-size: 18px;
      font-weight: 700;
      color: #333;
      margin-bottom: 8px;
    }

    .product-description {
      font-size: 13px;
      color: #666;
      margin-bottom: 15px;
    }

    .product-price {
      font-size: 22px;
      font-weight: 700;
      color: #ff6b6b;
    }

    .section-title {
      font-size: 32px;
      font-weight: 700;
      color: #333;
      text-align: center;
      margin-bottom: 30px;
    }
  </style>
</head>

<body>

  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="">
    </div>
    <?php include 'templates/header.php'; ?>
  </div>

  <div class="menu-container">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="section-title">📋 Naše Menu</h2>

          <div class="filter-buttons">
            <a href="menu.php" class="filter-btn <?php echo empty($selectedCategory) ? 'active' : ''; ?>">
              Všetky produkty
            </a>
            <?php foreach ($categories as $cat): ?>
              <a href="menu.php?category=<?php echo urlencode($cat); ?>"
                class="filter-btn <?php echo $selectedCategory === $cat ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat); ?>
              </a>
            <?php endforeach; ?>

          </div>

          <div class="products-grid">
            <?php foreach ($filteredProducts as $product): ?>
              <div class="product-card">
                <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="product-price">€<?php echo number_format($product['price'], 2); ?></div>
              </div>
            <?php endforeach; ?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'templates/footer.php'; ?>

</body>

</html>
