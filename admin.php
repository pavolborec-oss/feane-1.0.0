<?php
session_start();

// Kontrola či je užívateľ prihlásený
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kontrola timeout session (30 minút)
$timeout = 30 * 60; // 30 minút
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

// Obnovenie login času pri aktivity
$_SESSION['login_time'] = time();

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
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        if ($repo->delete($id)) {
            $success = 'Produkt bol vymazaný.';
        }
    }
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category = trim($_POST['category'] ?? '');

        if ($name === '' || $description === '' || $price <= 0 || $category === '') {
            $errors[] = 'Vyplňte všetky povinné polia správne.';
        } else {
            $repo->add([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                'image' => '',
            ]);
            $success = 'Produkt bol pridaný.';
        }
    }
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = intval($_POST['id'] ?? 0);
        $product = $repo->getById($id);
        if (!$product) {
            $errors[] = 'Produkt nebol nájdený.';
        } else {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $category = trim($_POST['category'] ?? '');

            if ($name === '' || $description === '' || $price <= 0 || $category === '') {
                $errors[] = 'Vyplňte všetky povinné polia správne.';
            } else {
                $repo->update($id, [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category' => $category,
                    'image' => $product['image'] ?? '',
                ]);
                $success = 'Produkt bol upravený.';
            }
        }
    }

    $products = $repo->getAll();
}

$editingProduct = null;
if (isset($_GET['edit'])) {
    $editingProduct = $repo->getById(intval($_GET['edit']));
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Administrácia - Feane</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f5f5f5;
    }

    .admin-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px;
      margin-bottom: 30px;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .admin-header h1 {
      margin: 0;
      font-size: 28px;
    }

    .user-info {
      text-align: right;
    }

    .user-info .username {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 8px;
      display: block;
    }

    .btn-logout {
      background-color: #ff6b6b;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s;
    }

    .btn-logout:hover {
      background-color: #ee5a6f;
      text-decoration: none;
      color: white;
    }

    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .card {
      margin-bottom: 20px;
      background: white;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      padding: 20px;
    }

    .card-title {
      margin-bottom: 20px;
      font-weight: 600;
      color: #333;
      border-bottom: 2px solid #ff6b6b;
      padding-bottom: 10px;
    }

    .product-table th {
      background: #f8f9fa;
      font-weight: 600;
      color: #333;
    }

    .product-table th,
    .product-table td {
      vertical-align: middle;
    }

    .btn-add {
      background: #ff6b6b;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .btn-add:hover {
      background: #ff5252;
      color: #fff;
      text-decoration: none;
    }

    .message-success {
      background: #d4edda;
      color: #155724;
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 20px;
      border-left: 4px solid #28a745;
    }

    .message-error {
      background: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 20px;
      border-left: 4px solid #dc3545;
    }

    .form-group label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
    }

    .btn-primary,
    .btn-success,
    .btn-danger,
    .btn-secondary {
      padding: 8px 15px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s;
    }

    .btn-primary {
      background: #667eea;
      color: white;
    }

    .btn-primary:hover {
      background: #5568d3;
      color: white;
    }

    .btn-success {
      background: #28a745;
      color: white;
    }

    .btn-success:hover {
      background: #218838;
      color: white;
    }

    .btn-danger {
      background: #dc3545;
      color: white;
    }

    .btn-danger:hover {
      background: #c82333;
      color: white;
    }

    .btn-secondary {
      background: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background: #5a6268;
      color: white;
    }
  </style>
</head>

<body>
  <div class="admin-header">
    <div>
      <h1><i class="fa fa-dashboard"></i> Administračný panel</h1>
    </div>
    <div class="user-info">
      <span class="username">
        <i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
      </span>
      <a href="logout.php" class="btn-logout">
        <i class="fa fa-sign-out"></i> Odhlásiť sa
      </a>
    </div>
  </div>

  <div class="admin-container">
    <?php if ($success): ?>
      <div class="message-success">
        <i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
      </div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="message-error">
        <i class="fa fa-exclamation-circle"></i>
        <ul style="margin-bottom: 0; margin-top: 8px;">
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><i class="fa fa-list"></i> Produkty</h5>
        <div class="table-responsive">
          <table class="table table-bordered product-table">
            <thead>
              <tr>
                <th width="5%">ID</th>
                <th>Názov</th>
                <th>Kategória</th>
                <th width="10%">Cena</th>
                <th width="20%">Akcie</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
                <tr>
                  <td><?php echo htmlspecialchars($product['id']); ?></td>
                  <td><?php echo htmlspecialchars($product['name']); ?></td>
                  <td><?php echo htmlspecialchars($product['category']); ?></td>
                  <td>€<?php echo number_format($product['price'], 2); ?></td>
                  <td>
                    <a href="admin.php?edit=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-primary">
                      <i class="fa fa-edit"></i> Upraviť
                    </a>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Naozaj vymazať?');">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                      <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Vymazať
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">
          <i class="fa fa-<?php echo $editingProduct ? 'edit' : 'plus'; ?>"></i> 
          <?php echo $editingProduct ? 'Upraviť produkt' : 'Pridať nový produkt'; ?>
        </h5>
        <form method="post">
          <input type="hidden" name="action" value="<?php echo $editingProduct ? 'update' : 'create'; ?>">
          <?php if ($editingProduct): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($editingProduct['id']); ?>">
          <?php endif; ?>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Názov</label>
              <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($editingProduct['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label>Kategória</label>
              <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($editingProduct['category'] ?? ''); ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Cena (€)</label>
              <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($editingProduct['price'] ?? ''); ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label>Popis</label>
              <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($editingProduct['description'] ?? ''); ?>" required>
            </div>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fa fa-save"></i> <?php echo $editingProduct ? 'Uložiť zmeny' : 'Pridať produkt'; ?>
          </button>
          <?php if ($editingProduct): ?>
            <a href="admin.php" class="btn btn-secondary">
              <i class="fa fa-times"></i> Zrušiť
            </a>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</body>

</html>