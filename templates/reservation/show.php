
<?php

$title = 'Réservation #' . $reservation->id;
$currentPage = 'reservations';

$escape = static function (mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

ob_start();
?>

<div class="page-header">
    <div>
        <a href="/reservations" class="btn btn--ghost btn--small" style="margin-bottom: 0.75rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Retour aux réservations
        </a>

        <h1 class="page-header__title">
            Réservation #<?= (int) $reservation->id ?>
        </h1>

        <p class="page-header__subtitle">
            <?php if ($reservation->statut === 'confirmée'): ?>
                <span class="status status--confirmed">
                    <span class="status__dot"></span>
                    Confirmée
                </span>
            <?php else: ?>
                <span class="status status--cancelled">
                    <span class="status__dot"></span>
                    Annulée
                </span>
            <?php endif; ?>
        </p>
    </div>

    <div class="page-header__actions">
        <?php if ($reservation->statut === 'confirmée'): ?>
            <form
                method="POST"
                action="/reservations/<?= (int) $reservation->id ?>/cancel"
                onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');"
            >
                <button type="submit" class="btn btn--danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Annuler
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <h3 class="card__title">Détails de la réservation</h3>
    </div>

    <dl class="details">
        <dt>Salle</dt>
        <dd>
            <?php if ($reservation->salle !== null): ?>
                <a href="/salles/<?= (int) $reservation->salle->id ?>" style="font-weight: 500;">
                    <?= $escape($reservation->salle->nom) ?>
                </a>
                <span class="muted">
                    — <?= $escape($reservation->salle->batiment) ?>
                </span>
            <?php else: ?>
                Salle inconnue
            <?php endif; ?>
        </dd>

        <dt>Responsable</dt>
        <dd><strong><?= $escape($reservation->responsable) ?></strong></dd>

        <dt>Email</dt>
        <dd>
            <a href="mailto:<?= $escape($reservation->email) ?>">
                <?= $escape($reservation->email) ?>
            </a>
        </dd>

        <dt>Motif</dt>
        <dd style="line-height: 1.6;">
            <?= nl2br($escape($reservation->motif)) ?>
        </dd>

        <dt>Début</dt>
        <dd>
            <strong><?= $escape($reservation->date_debut->format('d/m/Y')) ?></strong>
            à <?= $escape($reservation->date_debut->format('H:i')) ?>
        </dd>

        <dt>Fin</dt>
        <dd>
            <strong><?= $escape($reservation->date_fin->format('d/m/Y')) ?></strong>
            à <?= $escape($reservation->date_fin->format('H:i')) ?>
        </dd>

        <dt>Durée</dt>
        <dd>
            <?php
            $diff = $reservation->date_debut->diff($reservation->date_fin);
            $heures = ($diff->days * 24) + $diff->h;
            $minutes = $diff->i;
            ?>
            <?= $heures ?>h<?= sprintf('%02d', $minutes) ?>
        </dd>
    </dl>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/base.php';
?>
