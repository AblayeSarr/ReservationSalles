
<?php
$title = 'Tableau de bord';
$currentPage = 'home';
ob_start();
?>

<div class="hero">
    <div class="hero__content">
        <h1 class="hero__title">Bienvenue à la Gestion des réservations de salles universitaires</h1>
        <p class="hero__subtitle">
            Gérez facilement les réservations de salles de l'université.
            Consultez les disponibilités, créez des réservations et évitez les conflits d'horaires.
        </p>
        <div class="hero__actions">
            <a href="/reservations/create" class="hero__btn hero__btn--primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Nouvelle réservation
            </a>
            <a href="/salles" class="hero__btn hero__btn--secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Voir les salles
            </a>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l4-4 4 4"></path>
                <path d="M7 5v14"></path>
                <rect x="13" y="9" width="8" height="12" rx="1"></rect>
            </svg>
        </div>
        <div class="stat-card__content">
            <div class="stat-card__label">Salles disponibles</div>
            <div class="stat-card__value"><?= (int) ($stats['totalSalles'] ?? 0) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-card__content">
            <div class="stat-card__label">Réservations confirmées</div>
            <div class="stat-card__value"><?= (int) ($stats['reservationsConfirmees'] ?? 0) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-card__content">
            <div class="stat-card__label">Réservations aujourd'hui</div>
            <div class="stat-card__value"><?= (int) ($stats['reservationsAujourdhui'] ?? 0) ?></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-card__content">
            <div class="stat-card__label">Capacité totale</div>
            <div class="stat-card__value"><?= (int) ($stats['capaciteTotale'] ?? 0) ?></div>
        </div>
    </div>
</div>

<!-- Prochaines réservations -->
<div class="card">
    <div class="card__header">
        <h3 class="card__title">Prochaines réservations</h3>
        <a href="/reservations" class="btn btn--ghost btn--small">Voir tout →</a>
    </div>

    <?php if (empty($prochainesReservations)): ?>
        <div class="empty-state">
            <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <div class="empty-state__title">Aucune réservation à venir</div>
            <div class="empty-state__description">
                Commencez par créer votre première réservation pour voir les événements à venir.
            </div>
            <a href="/reservations/create" class="btn btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Créer une réservation
            </a>
        </div>
    <?php else: ?>
        <div class="table-container" style="border: none; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Salle</th>
                        <th>Responsable</th>
                        <th>Date</th>
                        <th>Motif</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prochainesReservations as $resa): ?>
                        <tr>
                            <td>
                                <a href="/salles/<?= (int) $resa->salle->id ?>" class="table__link">
                                    <?= $escape($resa->salle->nom) ?>
                                </a>
                            </td>
                            <td><?= $escape($resa->responsable) ?></td>
                            <td>
                                <div style="font-weight: 500;"><?= $escape($resa->date_debut->format('d/m/Y')) ?></div>
                                <div class="muted"><?= $escape($resa->date_debut->format('H:i')) ?> → <?= $escape($resa->date_fin->format('H:i')) ?></div>
                            </td>
                            <td class="muted"><?= $escape(mb_strimwidth($resa->motif, 0, 50, '...')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/layout/base.php'; ?>
