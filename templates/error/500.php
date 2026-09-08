<?php

$title = '500 — Erreur interne';

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
    <div class="error-page__code">500</div>

    <h2 class="error-page__title">
        Une erreur est survenue
    </h2>

    <p class="error-page__message">
        <?= $escape(
            $message
            ?? 'Une erreur interne est survenue. Veuillez réessayer plus tard.'
        ) ?>
    </p>

    <a href="/" class="btn btn--primary">
        Retour à l'accueil
    </a>
</div>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layout/base.php';
?>
