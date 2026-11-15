<?php
require 'auth.php';
require 'baza.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $akcja = $_POST['akcja'] ?? '';

    if ($akcja === 'dodaj') {
        $nazwa = trim($_POST['nazwa'] ?? '');
        $cena = (int) ($_POST['cena'] ?? 0);

        if ($nazwa !== '' && $cena > 0) {
            $z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena) VALUES (:nazwa, :cena)');
            $z->execute(['nazwa' => $nazwa, 'cena' => $cena]);
            header('Location: admin.php?ok=dodano');
            exit;
        }
    }

    if ($akcja === 'usun') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $z = $pdo->prepare('DELETE FROM uslugi WHERE id = :id');
            $z->execute(['id' => $id]);
            header('Location: admin.php?ok=usunieto');
            exit;
        }
    }
}

$uslugi = $pdo->query('SELECT id, nazwa, cena FROM uslugi ORDER BY nazwa')->fetchAll();
$ok = $_GET['ok'] ?? '';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel CMS – Pracownia Szprycha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #0b0f19;
            color: #f9fafb;
            line-height: 1.5;
        }
        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
        }
        /* NAVBAR */
        .navbar {
            background-color: rgba(17, 24, 39, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 16px 0;
            margin-bottom: 36px;
        }
        .navbar-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            font-weight: 800;
            color: #f9fafb;
        }
        .navbar-brand span.accent { color: #f59e0b; }
        .badge-cms {
            background-color: rgba(255, 255, 255, 0.08);
            color: #9ca3af;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }
        .logo-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #f59e0b;
            box-shadow: 0 0 12px #f59e0b;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        /* ALERTY */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .alert-success {
            background-color: rgba(16, 185, 129, 0.14);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
        }
        .alert-warning {
            background-color: rgba(245, 158, 11, 0.14);
            border: 1px solid rgba(245, 158, 11, 0.4);
            color: #fcd34d;
        }
        /* SIATKA 8 + 4 KOLUMNY */
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 28px;
            align-items: flex-start;
        }
        .col-lg-8 {
            flex: 1 1 600px;
        }
        .col-lg-4 {
            flex: 1 1 320px;
        }
        /* KARTY */
        .panel-card {
            background-color: #161f33;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }
        .panel-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0 0 20px;
        }
        /* TABELA */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 14px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: left;
        }
        .table th {
            color: #9ca3af;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-end { text-align: right !important; }
        .price-pill {
            display: inline-block;
            background-color: rgba(245, 158, 11, 0.14);
            color: #f59e0b;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        /* FORMULARZ I PRZYCISKI */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #9ca3af;
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            background-color: #0b0f19;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #f9fafb;
            font-family: inherit;
            font-size: 0.95rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #f59e0b;
            border: none;
            color: #0b0f19;
            font-weight: 700;
            font-size: 0.95rem;
            margin-top: 6px;
        }
        .btn-primary:hover { background-color: #fbbf24; }
        .btn-outline-light {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #f9fafb;
        }
        .btn-outline-light:hover {
            border-color: #f9fafb;
            background: rgba(255, 255, 255, 0.06);
        }
        .btn-outline-warning {
            background: transparent;
            border: 1px solid rgba(245, 158, 11, 0.5);
            color: #f59e0b;
        }
        .btn-outline-warning:hover {
            background: #f59e0b;
            color: #0b0f19;
        }
        .btn-outline-danger {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #f87171;
            padding: 5px 12px;
            font-size: 0.82rem;
        }
        .btn-outline-danger:hover {
            background: #ef4444;
            color: #ffffff;
        }
        .d-inline { display: inline; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-inner">
            <span class="navbar-brand">
                <i class="logo-dot"></i>Pracownia <span class="accent">Szprycha</span>
                <span class="badge-cms">Panel CMS</span>
            </span>
            <div class="nav-actions">
                <a href="index.php" class="btn btn-outline-light">Podgląd strony</a>
                <a href="wyloguj.php" class="btn btn-outline-warning">Wyloguj (<?= htmlspecialchars($_SESSION['uzytkownik']) ?>)</a>
            </div>
        </div>
    </nav>

    <main class="container" style="padding-bottom: 60px;">
        <?php if ($ok === 'dodano'): ?>
            <div class="alert alert-success">Usługa została dodana do cennika.</div>
        <?php elseif ($ok === 'usunieto'): ?>
            <div class="alert alert-warning">Usługa została usunięta z cennika.</div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="panel-card">
                    <h2 class="panel-title">Lista usług w bazie</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Usługa</th>
                                <th class="text-end">Cena</th>
                                <th class="text-end">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($uslugi)): ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #9ca3af; padding: 24px;">
                                        Brak usług w bazie. Dodaj pierwszą usługę w formularzu obok.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($uslugi as $usluga): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usluga['nazwa']) ?></td>
                                        <td class="text-end"><span class="price-pill"><?= (int) $usluga['cena'] ?> zł</span></td>
                                        <td class="text-end">
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="akcja" value="usun">
                                                <input type="hidden" name="id" value="<?= (int) $usluga['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel-card">
                    <h2 class="panel-title">Dodaj usługę</h2>
                    <form method="post">
                        <input type="hidden" name="akcja" value="dodaj">
                        <div class="form-group">
                            <label for="nazwa" class="form-label">Nazwa usługi</label>
                            <input id="nazwa" name="nazwa" class="form-control" placeholder="np. Wymiana łańcucha" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="cena" class="form-label">Cena (zł)</label>
                            <input id="cena" name="cena" type="number" min="1" class="form-control" placeholder="np. 50" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj do cennika</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>