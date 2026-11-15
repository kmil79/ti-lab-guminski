<?php
session_start();
require 'baza.php';

if (!empty($_SESSION['uzytkownik'])) {
    header('Location: admin.php');
    exit;
}

$blad = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $z = $pdo->prepare('SELECT login, haslo_hash FROM uzytkownicy WHERE login = :login');
    $z->execute(['login' => trim($_POST['login'] ?? '')]);
    $u = $z->fetch();

    if ($u && password_verify($_POST['haslo'] ?? '', $u['haslo_hash'])) {
        session_regenerate_id(true);
        $_SESSION['uzytkownik'] = $u['login'];
        header('Location: admin.php');
        exit;
    }

    $blad = 'Błędny login lub hasło.';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie – Pracownia Szprycha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #0b0f19;
            background-image: radial-gradient(circle at 50% 0%, rgba(245, 158, 11, 0.16) 0%, transparent 60%);
            color: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-box {
            width: 100%;
            max-width: 420px;
            background-color: #161f33;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }
        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .badge {
            display: inline-block;
            background-color: rgba(245, 158, 11, 0.14);
            border: 1px solid rgba(245, 158, 11, 0.35);
            color: #f59e0b;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 14px;
        }
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0 0 6px;
        }
        .brand span { color: #f59e0b; }
        .logo-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #f59e0b;
            box-shadow: 0 0 12px #f59e0b;
        }
        .subtitle {
            color: #9ca3af;
            font-size: 0.9rem;
            margin: 0;
        }
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #9ca3af;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            background-color: #0b0f19;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #f9fafb;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }
        .btn-primary {
            width: 100%;
            padding: 13px;
            margin-top: 8px;
            background-color: #f59e0b;
            border: none;
            border-radius: 10px;
            color: #0b0f19;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(245, 158, 11, 0.25);
            transition: background-color 0.2s, transform 0.15s;
        }
        .btn-primary:hover {
            background-color: #fbbf24;
            transform: translateY(-1px);
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .back-link:hover { color: #f59e0b; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <span class="badge">Panel CMS</span>
            <h1 class="brand">
                <i class="logo-dot"></i>Pracownia <span>Szprycha</span>
            </h1>
            <p class="subtitle">Zaloguj się, aby zarządzać cennikiem</p>
        </div>

        <?php if ($blad): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($blad) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="login" class="form-label">Login</label>
                <input id="login" name="login" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label for="haslo" class="form-label">Hasło</label>
                <input id="haslo" name="haslo" type="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Zaloguj się</button>
        </form>

        <a href="index.php" class="back-link">← Powrót na stronę główną</a>
    </div>
</body>
</html>