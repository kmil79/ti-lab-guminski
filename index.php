<?php
require 'baza.php';
$firma = 'Szprycha';
$rok = date('Y');
$uslugi = $pdo->query('SELECT nazwa, cena FROM uslugi ORDER BY cena')->fetchAll();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pracownia Rowerowa Szprycha – Profesjonalny Serwis w Bydgoszczy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="app.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="#" class="logo">
                <span class="logo-dot"></span>
                Pracownia <span>Szprycha</span>
            </a>
            <button class="menu-toggle" aria-expanded="false" aria-controls="main-menu" aria-label="Menu">
                ☰
            </button>
            <nav id="main-menu" class="menu">
                <a href="#uslugi">Usługi</a>
                <a href="#cennik">Cennik</a>
                <a href="#kontakt">Kontakt</a>
                <a href="login.php" class="btn-nav">Panel CMS</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Sekcja Hero w stylu nowoczesnej agencji -->
        <section class="hero">
            <div class="container hero-content">
                <span class="hero-tag">Serwis Rowerowy • Bydgoszcz</span>
                <h1>Twój rower w najlepszych rękach. <span class="gradient-text">Precyzja w każdym detalu.</span></h1>
                <p class="hero-desc">
                    Kompleksowe przeglądy, centrowanie kół i pełna regeneracja napędu. Przywracamy fabryczną płynność jazdy przed każdym sezonem.
                </p>
                <div class="hero-actions">
                    <a href="#cennik" class="btn btn-primary">Sprawdź cennik</a>
                    <a href="#kontakt" class="btn btn-secondary">Umów serwis</a>
                </div>
            </div>
        </section>

        <!-- Sekcja Usługi -->
        <section id="uslugi" class="section">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Oferta</span>
                    <h2>Nasze pakiety serwisowe</h2>
                    <p>Dobierz zakres prac dopasowany do przebiegu i stylu Twojej jazdy.</p>
                </div>

                <div class="cards">
                    <article class="card">
                        <div class="card-icon">🔧</div>
                        <h3>Przegląd podstawowy</h3>
                        <p>Regulacja hamulców i przerzutek, smarowanie łańcucha oraz kontrola ciśnienia i luzów.</p>
                        <p class="card__price">od 120 zł</p>
                    </article>

                    <article class="card card--highlight">
                        <span class="badge">Najczęściej wybierane</span>
                        <div class="card-icon">⚙️</div>
                        <h3>Przegląd generalny</h3>
                        <p>Pełny serwis z rozebraniem napędu, myciem ultradźwiękowym, wymianą smarów i centrowaniem kół.</p>
                        <p class="card__price">od 250 zł</p>
                    </article>

                    <article class="card">
                        <div class="card-icon">🛞</div>
                        <h3>Serwis kół i ogumienia</h3>
                        <p>Precyzyjne centrowanie kół, wymiana dętek, opon bezdętkowych (tubeless) oraz zaplatanie szprych.</p>
                        <p class="card__price">od 50 zł</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Sekcja Cennik (dynamiczna z bazy SQLite) -->
        <section id="cennik" class="section section-alt">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Transparentne stawki</span>
                    <h2>Cennik usług</h2>
                    <p>Aktualne ceny pobierane bezpośrednio z naszego systemu.</p>
                </div>

                <div class="table-wrapper">
                    <table class="price-table">
                        <thead>
                            <tr>
                                <th>Nazwa usługi</th>
                                <th class="num">Cena brutto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($uslugi)): ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #9ca3af;">
                                        Brak usług w bazie. Zaloguj się do Panelu CMS, aby dodać pozycje.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($uslugi as $usluga): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usluga['nazwa']) ?></td>
                                        <td class="num"><span class="price-pill"><?= (int) $usluga['cena'] ?> zł</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <!-- Stopka Kontakt -->
    <footer id="kontakt" class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-info">
                    <a href="#" class="logo">
                        <span class="logo-dot"></span>
                        Pracownia <span>Szprycha</span>
                    </a>
                    <p>Profesjonalny warsztat rowerowy w sercu Bydgoszczy. Pasja, doświadczenie i najwyższej klasy narzędzia.</p>
                </div>

                <div class="footer-contact">
                    <h3>Kontakt i lokalizacja</h3>
                    <ul>
                        <li><strong>Adres:</strong> ul. Zielona 12, 85-123 Bydgoszcz</li>
                        <li><strong>Telefon:</strong> <a href="tel:+48123456789">123 456 789</a></li>
                        <li><strong>E-mail:</strong> <a href="mailto:kontakt@szprycha.pl">kontakt@szprycha.pl</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= htmlspecialchars($rok) ?> <?= htmlspecialchars($firma) ?>. Wszelkie prawa zastrzeżone.</p>
            </div>
        </div>
    </footer>
</body>
</html>