
<?php

$title = 'Nouvelle réservation';
$currentPage = 'reservations';

$escape = static function (mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$data = $data ?? [];
$errors = $errors ?? [];
$globalError = $globalError ?? null;

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
            Retour
        </a>

        <h1 class="page-header__title">Nouvelle réservation</h1>
        <p class="page-header__subtitle">Réservez une salle pour un créneau donné</p>
    </div>
</div>

<?php if (!empty($globalError)): ?>
    <div class="alert alert--error">
        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>

        <div>
            <strong>Impossible de créer la réservation.</strong><br>
            <?= $escape($globalError) ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert--error">
        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>

        <div>
            <strong>Veuillez corriger les erreurs ci-dessous.</strong>
            <?= count($errors) ?> champ(s) contient(ent) des erreurs.
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="/reservations" class="form">

    <div class="form__group">
        <label for="salle_id">
            Salle <span class="required">*</span>
        </label>

        <select
            id="salle_id"
            name="salle_id"
            class="<?= isset($errors['salle_id']) ? 'input--error' : '' ?>"
            required
        >
            <option value="">-- Choisir une salle --</option>

            <?php foreach ($salles as $salle): ?>
                <option
                    value="<?= (int) $salle->id ?>"
                    <?= ((string) ($data['salle_id'] ?? '') === (string) $salle->id)
                        ? 'selected'
                        : '' ?>
                >
                    <?= $escape($salle->nom) ?>
                    — <?= $escape($salle->batiment) ?>
                    (<?= (int) $salle->capacite ?> places)
                </option>
            <?php endforeach; ?>
        </select>

        <?php foreach ($errors['salle_id'] ?? [] as $error): ?>
            <span class="field-error"><?= $escape($error) ?></span>
        <?php endforeach; ?>
    </div>

    <div class="form__row">

        <div class="form__group">
            <label for="responsable">
                Responsable <span class="required">*</span>
            </label>

            <input
                type="text"
                id="responsable"
                name="responsable"
                value="<?= $escape($data['responsable'] ?? '') ?>"
                placeholder="Ex : Awa Ndiaye"
                maxlength="100"
                class="<?= isset($errors['responsable']) ? 'input--error' : '' ?>"
                required
            >

            <?php foreach ($errors['responsable'] ?? [] as $error): ?>
                <span class="field-error"><?= $escape($error) ?></span>
            <?php endforeach; ?>
        </div>

        <div class="form__group">
            <label for="email">
                Email <span class="required">*</span>
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= $escape($data['email'] ?? '') ?>"
                placeholder="exemple@universite.sn"
                maxlength="255"
                class="<?= isset($errors['email']) ? 'input--error' : '' ?>"
                required
            >

            <?php foreach ($errors['email'] ?? [] as $error): ?>
                <span class="field-error"><?= $escape($error) ?></span>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="form__group">
        <label for="motif">
            Motif <span class="required">*</span>
        </label>

        <textarea
            id="motif"
            name="motif"
            rows="3"
            minlength="5"
            maxlength="255"
            placeholder="Décrivez la raison de la réservation"
            class="<?= isset($errors['motif']) ? 'input--error' : '' ?>"
            required
        ><?= $escape($data['motif'] ?? '') ?></textarea>

        <?php foreach ($errors['motif'] ?? [] as $error): ?>
            <span class="field-error"><?= $escape($error) ?></span>
        <?php endforeach; ?>
    </div>

    <div class="form__row">

        <div class="form__group">
            <label for="date_debut">
                Date de début <span class="required">*</span>
            </label>

            <input
                type="datetime-local"
                id="date_debut"
                name="date_debut"
                value="<?= $escape($data['date_debut'] ?? '') ?>"
                class="<?= isset($errors['date_debut']) ? 'input--error' : '' ?>"
                required
            >

            <?php foreach ($errors['date_debut'] ?? [] as $error): ?>
                <span class="field-error"><?= $escape($error) ?></span>
            <?php endforeach; ?>
        </div>

        <div class="form__group">
            <label for="date_fin">
                Date de fin <span class="required">*</span>
            </label>

            <input
                type="datetime-local"
                id="date_fin"
                name="date_fin"
                value="<?= $escape($data['date_fin'] ?? '') ?>"
                class="<?= isset($errors['date_fin']) ? 'input--error' : '' ?>"
                required
            >

            <?php foreach ($errors['date_fin'] ?? [] as $error): ?>
                <span class="field-error"><?= $escape($error) ?></span>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="alert alert--warning" style="margin-top: 1rem;">
        <svg class="alert__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>

        <div>
            <strong>Rappel :</strong>
            La durée maximale d'une réservation est de <strong>4 heures</strong>.
            La salle doit être disponible sur le créneau demandé.
        </div>
    </div>

    <div class="form__actions">
        <a href="/reservations" class="btn btn--ghost">
            Annuler
        </a>

        <button type="submit" class="btn btn--primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>

            Créer la réservation
        </button>
    </div>

</form>

<?php
$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
?>
