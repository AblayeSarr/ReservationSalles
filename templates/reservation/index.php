<?php

$title = 'Réservations';
$currentPage = 'reservations';

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
        <h1 class="page-header__title">
            Réservations
        </h1>

        <p class="page-header__subtitle">
            <?= count($reservations) ?> réservation(s) trouvée(s)
        </p>
    </div>

    <div class="page-header__actions">

        <a
            href="/reservations/create"
            class="btn btn--primary"
        >
            <svg
                width="16"
                height="16"
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

            Nouvelle réservation
        </a>

    </div>

</div>


<form
    method="GET"
    action="/reservations"
    class="filter-bar"
>

    <label
        class="filter-bar__label"
        for="salle_id"
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
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
        </svg>

        Filtrer par salle :
    </label>


    <select
        name="salle_id"
        id="salle_id"
    >

        <option value="">
            Toutes les salles
        </option>

        <?php foreach ($salles as $salle): ?>

            <option
                value="<?= (int) $salle->id ?>"
                <?= (
                    $salleFilter !== null
                    && (int) $salleFilter->id === (int) $salle->id
                ) ? 'selected' : '' ?>
            >
                <?= $escape($salle->nom) ?>
            </option>

        <?php endforeach; ?>

    </select>


    <button
        type="submit"
        class="btn btn--secondary btn--small"
    >
        Appliquer
    </button>


    <?php if ($salleFilter !== null): ?>

        <a
            href="/reservations"
            class="btn btn--ghost btn--small"
        >
            Effacer
        </a>

    <?php endif; ?>

</form>


<?php if (empty($reservations)): ?>

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

            <?php if ($salleFilter !== null): ?>

                Aucune réservation trouvée pour cette salle.

            <?php else: ?>

                Commencez par créer votre première réservation.

            <?php endif; ?>

        </div>


        <a
            href="/reservations/create"
            class="btn btn--primary"
        >
            Créer une réservation
        </a>

    </div>


<?php else: ?>

    <div class="table-container">

        <table class="table">

            <thead>

                <tr>
                    <th>Salle</th>
                    <th>Responsable</th>
                    <th>Période</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th style="width: 120px;">
                        Actions
                    </th>
                </tr>

            </thead>


            <tbody>

                <?php foreach ($reservations as $reservation): ?>

                    <tr
                        class="<?= $reservation->statut === 'annulée'
                            ? 'row--cancelled'
                            : '' ?>"
                    >

                        <td>

                            <?php if ($reservation->salle !== null): ?>

                                <a
                                    href="/salles/<?= (int) $reservation->salle->id ?>"
                                    class="table__link"
                                >
                                    <?= $escape($reservation->salle->nom) ?>
                                </a>

                                <div
                                    class="muted"
                                    style="font-size: 0.8rem;"
                                >
                                    <?= $escape($reservation->salle->batiment) ?>
                                </div>

                            <?php else: ?>

                                <span class="muted">
                                    Salle inconnue
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>

                            <a
                                href="/reservations/<?= (int) $reservation->id ?>"
                                class="table__link"
                            >
                                <?= $escape($reservation->responsable) ?>
                            </a>

                            <div
                                class="muted"
                                style="font-size: 0.8rem;"
                            >
                                <?= $escape($reservation->email) ?>
                            </div>

                        </td>


                        <td>

                            <div style="font-weight: 500;">

                                <?= $escape(
                                    $reservation->date_debut->format('d/m/Y')
                                ) ?>

                            </div>

                            <div
                                class="muted"
                                style="font-size: 0.8rem;"
                            >

                                <?= $escape(
                                    $reservation->date_debut->format('H:i')
                                ) ?>

                                →

                                <?= $escape(
                                    $reservation->date_fin->format('H:i')
                                ) ?>

                            </div>

                        </td>


                        <td class="muted">

                            <?= $escape(
                                mb_strimwidth(
                                    $reservation->motif,
                                    0,
                                    40,
                                    '...'
                                )
                            ) ?>

                        </td>


                        <td>

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

                        </td>


                        <td>

                            <?php if ($reservation->statut === 'confirmée'): ?>

                                <form
                                    method="POST"
                                    action="/reservations/<?= (int) $reservation->id ?>/cancel"
                                    style="display: inline;"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');"
                                >

                                    <button
                                        type="submit"
                                        class="btn btn--danger-outline btn--small"
                                        title="Annuler"
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
                                            <line
                                                x1="18"
                                                y1="6"
                                                x2="6"
                                                y2="18"
                                            ></line>

                                            <line
                                                x1="6"
                                                y1="6"
                                                x2="18"
                                                y2="18"
                                            ></line>
                                        </svg>

                                    </button>

                                </form>

                            <?php else: ?>

                                <span class="muted">
                                    —
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php endif; ?>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
?>
