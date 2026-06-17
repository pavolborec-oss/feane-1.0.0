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

$products = $repo->getAll();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Feane - Domov</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <style>
    .products-section {
      padding: 40px 0;
      background-color: #f9f9f9;
    }

    .product-item {
      background: white;
      padding: 20px;
      margin: 10px 0;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .product-name {
      font-weight: 700;
      color: #ff6b6b;
      font-size: 18px;
    }

    .product-price {
      font-weight: bold;
      color: #333;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
      font-weight: 700;
    }
  </style>
</head>

<body>

  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="">
    </div>
    <?php include 'partials/header.php'; ?>
  </div>

  <div class="products-section">
    <div class="container">
      <h2>🍽️ Naše Špecialitáky (<?php echo count($products); ?> produktov)</h2>

      <?php foreach ($products as $product): ?>
        <div class="product-item">
          <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
          <p><?php echo htmlspecialchars($product['description']); ?></p>
          <div class="product-price">Cena: €<?php echo number_format($product['price'], 2); ?></div>
          <small style="color: #999;">Kategória: <?php echo htmlspecialchars($product['category']); ?></small>
        </div>
      <?php endforeach; ?>

    </div>
  </div>

  <?php include 'partials/footer.php'; ?>

</body>

</html>
