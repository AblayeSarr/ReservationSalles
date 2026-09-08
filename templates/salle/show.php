
<?php

$title = $salle->nom;
$currentPage = 'salles';

$escape = static function (mixed $value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};

ob_start();
?>

<div class="page-header">
    <div>
        <a
            href="/salles"
            class="btn btn--ghost btn--small"
            style="margin-bottom: 0.75rem;"
        >
            <svg
                width="14"
                height="14"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>

            Retour aux salles
        </a>

        <h1 class="page-header__title">
            <?= $escape($salle->nom) ?>
        </h1>

        <p class="page-header__subtitle">
            <?= $escape($salle->batiment) ?>
        </p>
    </div>

    <div class="page-header__actions">
        <a
            href="/salles/<?= (int) $salle->id ?>/edit"
            class="btn btn--secondary"
        >
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
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1-9.5-9.5z"></path>
            </svg>

            Modifier
        </a>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">

    <div class="card__header">
        <h3 class="card__title">
            Informations
        </h3>
    </div>

    <dl class="details">

        <dt>Nom</dt>
        <dd>
            <strong>
                <?= $escape($salle->nom) ?>
            </strong>
        </dd>

        <dt>Bâtiment</dt>
        <dd>
            <?= $escape($salle->batiment) ?>
        </dd>

        <dt>Capacité</dt>
        <dd>
            <strong>
                <?= (int) $salle->capacite ?>
            </strong>
            places
        </dd>

        <dt>Type</dt>
        <dd>
            <span class="badge badge--<?= $escape($salle->type) ?>">
                <?= $escape($salle->type) ?>
            </span>
        </dd>

        <dt>Statut</dt>
        <dd>

            <?php if ($salle->active): ?>

                <span class="status status--active">
                    <span class="status__dot"></span>
                    Active
                </span>

            <?php else: ?>

                <span class="status status--inactive">
                    <span class="status__dot"></span>
                    Inactive
                </span>

            <?php endif; ?>

        </dd>

    </dl>

</div>

<div class="card">

    <div class="card__header">

        <h3 class="card__title">
            Réservations à venir
        </h3>

        <?php if ($salle->active): ?>

            <a
                href="/reservations/create?salle_id=<?= (int) $salle->id ?>"
                class="btn btn--primary btn--small"
            >
                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>

                Réserver
            </a>

        <?php endif; ?>

    </div>

    <?php

    $reservations = $salle->reservations->filter(
        fn ($r) => $r->statut === 'confirmée'
    );

    ?>

    <?php if ($reservations->isEmpty()): ?>

        <div class="empty-state">

            <svg
                class="empty-state__icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <rect
                    x="3"
                    y="4"
                    width="18"
                    height="18"
                    rx="2"
                    ry="2"
                ></rect>

                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>

            <div class="empty-state__title">
                Aucune réservation
            </div>

            <div class="empty-state__description">
                Cette salle n'a pas encore de réservation confirmée.
            </div>

        </div>

    <?php else: ?>

        <div
            class="table-container"
            style="border: none; box-shadow: none;"
        >

            <table class="table">

                <thead>

                    <tr>
                        <th>Responsable</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Motif</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($reservations as $resa): ?>

                        <tr>

                            <td>

                                <a
                                    href="/reservations/<?= (int) $resa->id ?>"
                                    class="table__link"
                                >
                                    <?= $escape($resa->responsable) ?>
                                </a>

                            </td>

                            <td>

                                <div style="font-weight: 500;">
                                    <?= $escape(
                                        $resa->date_debut->format('d/m/Y')
                                    ) ?>
                                </div>

                                <div class="muted">
                                    <?= $escape(
                                        $resa->date_debut->format('H:i')
                                    ) ?>
                                </div>

                            </td>

                            <td>

                                <div style="font-weight: 500;">
                                    <?= $escape(
                                        $resa->date_fin->format('d/m/Y')
                                    ) ?>
                                </div>

                                <div class="muted">
                                    <?= $escape(
                                        $resa->date_fin->format('H:i')
                                    ) ?>
                                </div>

                            </td>

                            <td class="muted">
                                <?= $escape(
                                    mb_strimwidth(
                                        $resa->motif,
                                        0,
                                        50,
                                        '...'
                                    )
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layout/base.php';

?>
