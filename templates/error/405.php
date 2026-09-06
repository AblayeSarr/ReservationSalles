
<?php

$title = '405 — Méthode non autorisée';

$escape = static function (mixed $value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};

ob_start();
?>

<div class="error-page">
    <div class="error-page__code">405</div>

    <h2 class="error-page__title">
        Méthode non autorisée
    </h2>

    <p class="error-page__message">
        La méthode HTTP utilisée n'est pas autorisée pour cette ressource.

        <?php if (!empty($allowed)): ?>
            <br>
            Méthodes autorisées :
            <strong><?= $escape($allowed) ?></strong>.
        <?php endif; ?>
    </p>

    <a href="/" class="btn btn--primary">
        Retour à l'accueil
    </a>
</div>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layout/base.php';
?>
