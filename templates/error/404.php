
<?php

$title = '404 — Page introuvable';

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
    <div class="error-page__code">404</div>

    <h2 class="error-page__title">
        Page introuvable
    </h2>

    <p class="error-page__message">
        <?= $escape(
            $message
            ?? 'Désolé, la page que vous recherchez n\'existe pas ou a été déplacée.'
        ) ?>
    </p>

    <a href="/" class="btn btn--primary">
        <svg
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>

        Retour à l'accueil
    </a>
</div>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layout/base.php';
?>
