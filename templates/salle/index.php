
<?php

$title = 'Salles';
$currentPage = 'salles';

ob_start();
?>

<div class="page-header">

    <div>
        <h1 class="page-header__title">
            Salles
        </h1>

        <p class="page-header__subtitle">
            <?= count($salles) ?> salle(s) enregistrée(s)
        </p>
    </div>

    <div class="page-header__actions">

        <a href="/salles/create" class="btn btn--primary">

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

            Ajouter une salle

        </a>

    </div>

</div>

<?php if (empty($salles)): ?>

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
            <path d="M3 9l4-4 4 4"></path>
            <path d="M7 5v14"></path>
            <rect x="13" y="9" width="8" height="12" rx="1"></rect>
        </svg>

        <div class="empty-state__title">
            Aucune salle enregistrée
        </div>

        <div class="empty-state__description">
            Commencez par ajouter votre première salle
            pour pouvoir gérer les réservations.
        </div>

        <a href="/salles/create" class="btn btn--primary">
            Ajouter une salle
        </a>

    </div>

<?php else: ?>

    <div class="table-container">

        <table class="table">

            <thead>

                <tr>
                    <th>Nom</th>
                    <th>Bâtiment</th>
                    <th>Capacité</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th style="width: 100px;">Actions</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($salles as $salle): ?>

                    <tr>

                        <!-- Nom -->
                        <td>

                            <a
                                href="/salles/<?= (int) $salle->id ?>"
                                class="table__link"
                            >
                                <?= htmlspecialchars(
                                    $salle->nom,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </a>

                        </td>

                        <!-- Bâtiment -->
                        <td class="muted">

                            <?= htmlspecialchars(
                                $salle->batiment,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>

                        <!-- Capacité -->
                        <td>

                            <strong>
                                <?= (int) $salle->capacite ?>
                            </strong>

                            <span class="muted">
                                places
                            </span>

                        </td>

                        <!-- Type -->
                        <td>

                            <span
                                class="badge badge--<?= htmlspecialchars(
                                    $salle->type,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                <?= htmlspecialchars(
                                    $salle->type,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                        </td>

                        <!-- Statut -->
                        <td>

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

                        </td>

                        <!-- Actions -->
                        <td>

                            <a
                                href="/salles/<?= (int) $salle->id ?>/edit"
                                class="btn btn--ghost btn--small"
                                title="Modifier"
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
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>

                            </a>

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
