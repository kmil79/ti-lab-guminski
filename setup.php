<?php
require 'baza.php';

$pdo->exec('CREATE TABLE IF NOT EXISTS uslugi (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwa TEXT NOT NULL,
    cena INTEGER NOT NULL
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS uzytkownicy (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login TEXT NOT NULL UNIQUE,
    haslo_hash TEXT NOT NULL
)');

// Dodaj początkowe usługi, jeśli tabela jest pusta
$liczbaUslug = (int) $pdo->query('SELECT COUNT(*) FROM uslugi')->fetchColumn();
if ($liczbaUslug === 0) {
    $z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena) VALUES (:nazwa, :cena)');
    $startowe = [
        ['nazwa' => 'Przegląd podstawowy', 'cena' => 120],
        ['nazwa' => 'Przegląd generalny', 'cena' => 250],
        ['nazwa' => 'Regulacja przerzutek lub hamulców', 'cena' => 45],
        ['nazwa' => 'Centrowanie koła', 'cena' => 60],
        ['nazwa' => 'Wymiana dętki / opony', 'cena' => 35],
    ];
    foreach ($startowe as $u) {
        $z->execute($u);
    }
}

// Dodaj konto admina, jeśli tabela użytkowników jest pusta
$liczbaUserow = (int) $pdo->query('SELECT COUNT(*) FROM uzytkownicy')->fetchColumn();
if ($liczbaUserow === 0) {
    $z = $pdo->prepare('INSERT INTO uzytkownicy (login, haslo_hash) VALUES (:login, :hash)');
    $z->execute([
        'login' => 'admin',
        'hash'  => password_hash('Szprycha!2026', PASSWORD_DEFAULT),
    ]);
}

echo "Baza gotowa.\n";