<?php
session_start();

// Ak je užívateľ už prihlásený, presmeruj na admin
if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit();
}

$error = '';
$success = '';

// Spracovanie formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ochrana pred CSRF útokmi
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Bezpečnostný token je neplatný!';
    } else {
        // Sanitizácia vstupov
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Validácia
        if (empty($username) || empty($password)) {
            $error = 'Prosím, vyplňte všetky polia!';
        } else {
            // Demo databáza používateľov (v reálnej aplikácii by to bola databáza)
            // Heslo je hashované pomocou password_hash()
            $users = [
                'admin' => password_hash('admin123', PASSWORD_BCRYPT),
                'user' => password_hash('user123', PASSWORD_BCRYPT)
            ];

            // Overenie prihlasovacích údajov
            if (isset($users[$username]) && password_verify($password, $users[$username])) {
                // Prihlásenie úspešné
                $_SESSION['user_id'] = md5($username);
                $_SESSION['username'] = htmlspecialchars($username);
                $_SESSION['login_time'] = time();

                $success = 'Prihlásenie bolo úspešné! Presmerovávam...';
                header("Refresh: 2; url=admin.php");
            } else {
                $error = 'Nesprávne meno alebo heslo!';
                // Log failed attempt (optional security measure)
                error_log("Neúspešný pokus o prihlásenie: " . date('Y-m-d H:i:s'));
            }
        }
    }
}

// Generovanie CSRF tokenu
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Prihlásenie - Feane</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 5px rgba(255, 107, 107, 0.3);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .login-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 13px;
            color: #666;
        }

        .login-footer a {
            color: #ff6b6b;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .demo-credentials {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 13px;
        }

        .demo-credentials strong {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .demo-credentials p {
            margin: 5px 0;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Feane</h1>
            <p>Administrátor</p>
        </div>

        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="form-group">
                    <label for="username">Meno používateľa:</label>
                    <input type="text" id="username" name="username" required placeholder="Zadajte meno" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password">Heslo:</label>
                    <input type="password" id="password" name="password" required placeholder="Zadajte heslo">
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa fa-sign-in"></i> Prihlásiť sa
                </button>
            </form>

            <div class="demo-credentials">
                <strong>Demo účty:</strong>
                <p><strong>Admin:</strong> admin / admin123</p>
                
            </div>

            <div class="login-footer">
                
            </div>
        </div>
    </div>
</body>

</html>