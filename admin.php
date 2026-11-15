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
    <title>Panel admina – Szprycha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU90FeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">CMS Szprycha</span>
            <div class="d-flex align-items-center gap-3">
                <a href="index.php" class="btn btn-sm btn-outline-light">Zobacz stronę</a>
                <a href="wyloguj.php" class="btn btn-sm btn-warning">Wyloguj (<?= htmlspecialchars($_SESSION['uzytkownik']) ?>)</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <?php if ($ok === 'dodano'): ?>
            <div class="alert alert-success">Usługa została dodana.</div>
        <?php elseif ($ok === 'usunieto'): ?>
            <div class="alert alert-warning">Usługa została usunięta.</div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <h2 class="h4 mb-3">Lista usług</h2>
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Usługa</th>
                            <th class="text-end">Cena</th>
                            <th class="text-end">Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($uslugi as $usluga): ?>
                            <tr>
                                <td><?= htmlspecialchars($usluga['nazwa']) ?></td>
                                <td class="text-end"><?= (int) $usluga['cena'] ?> zł</td>
                                <td class="text-end">
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="akcja" value="usun">
                                        <input type="hidden" name="id" value="<?= (int) $usluga['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Dodaj usługę</h2>
                        <form method="post">
                            <input type="hidden" name="akcja" value="dodaj">
                            <div class="mb-3">
                                <label for="nazwa" class="form-label">Nazwa</label>
                                <input id="nazwa" name="nazwa" class="form-control" required maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label for="cena" class="form-label">Cena (zł)</label>
                                <input id="cena" name="cena" type="number" min="1" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Dodaj</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>