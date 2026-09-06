
<?php

$title = $title ?? 'ReservationSalles';
$content = $content ?? '';

$escape = static function (mixed $value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $escape($title) ?> — ReservationSalles</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>

<div class="app-layout">

    <aside class="sidebar">

        <div class="sidebar__brand">
            <a href="/" class="sidebar__logo">

                <span class="sidebar__logo-icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        width="20"
                        height="20"
                    >
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </span>

                ReservationSalles
            </a>
        </div>

        <nav class="sidebar__nav">

            <div class="sidebar__section">

                <div class="sidebar__section-title">
                    Navigation
                </div>

                <a
                    href="/"
                    class="sidebar__link <?= ($currentPage ?? '') === 'home'
                        ? 'sidebar__link--active'
                        : '' ?>"
                >
                    Accueil
                </a>

            </div>

            <div class="sidebar__section">

                <div class="sidebar__section-title">
                    Gestion
                </div>

                <a
                    href="/salles"
                    class="sidebar__link <?= ($currentPage ?? '') === 'salles'
                        ? 'sidebar__link--active'
                        : '' ?>"
                >
                    Salles
                </a>

                <a
                    href="/reservations"
                    class="sidebar__link <?= ($currentPage ?? '') === 'reservations'
                        ? 'sidebar__link--active'
                        : '' ?>"
                >
                    Réservations
                </a>

            </div>

        </nav>

        <div class="sidebar__footer">
            <small>
                ReservationSalles — <?= date('Y') ?>
            </small>
        </div>

    </aside>

    <main class="main">

        <?= $content ?>

    </main>

</div>

</body>
</html>
