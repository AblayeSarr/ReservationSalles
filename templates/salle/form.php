
<?php

$isEdit = ($mode ?? 'create') === 'edit';

$title = $isEdit
    ? 'Modifier la salle'
    : 'Nouvelle salle';

$currentPage = 'salles';

$action = $isEdit
    ? '/salles/' . (int) $salle->id . '/edit'
    : '/salles';

$escape = static function (mixed $value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};

$typesAutorises = [
    'cours',
    'informatique',
    'laboratoire',
    'amphitheatre',
    'reunion',
];

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

            Retour
        </a>

        <h1 class="page-header__title">
            <?= $escape($title) ?>
        </h1>

        <p class="page-header__subtitle">
            <?= $isEdit
                ? 'Modifiez les informations de la salle'
                : 'Ajoutez une nouvelle salle à l\'université'
            ?>
        </p>

    </div>

</div>

<?php if (!empty($errors)): ?>

    <div class="alert alert--error">

        <svg
            class="alert__icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>

        <div>

            <strong>
                Veuillez corriger les erreurs ci-dessous.
            </strong>

            <?= count($errors) ?>
            champ(s) contient(ent) des erreurs.

        </div>

    </div>

<?php endif; ?>

<form
    method="POST"
    action="<?= $escape($action) ?>"
    class="form"
>

    <div class="form__group">

        <label for="nom">
            Nom de la salle
            <span class="required">*</span>
        </label>

        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= $escape($data['nom'] ?? '') ?>"
            placeholder="Ex: Amphithéâtre A"
            class="<?= isset($errors['nom'])
                ? 'input--error'
                : ''
            ?>"
        >

        <?php foreach ($errors['nom'] ?? [] as $err): ?>

            <span class="field-error">
                <?= $escape($err) ?>
            </span>

        <?php endforeach; ?>

    </div>

    <div class="form__group">

        <label for="batiment">
            Bâtiment
            <span class="required">*</span>
        </label>

        <input
            type="text"
            id="batiment"
            name="batiment"
            value="<?= $escape($data['batiment'] ?? '') ?>"
            placeholder="Ex: Bâtiment Principal"
            class="<?= isset($errors['batiment'])
                ? 'input--error'
                : ''
            ?>"
        >

        <?php foreach ($errors['batiment'] ?? [] as $err): ?>

            <span class="field-error">
                <?= $escape($err) ?>
            </span>

        <?php endforeach; ?>

    </div>

    <div class="form__row">

        <div class="form__group">

            <label for="capacite">
                Capacité
                <span class="required">*</span>
            </label>

            <input
                type="number"
                id="capacite"
                name="capacite"
                min="1"
                max="1000"
                value="<?= $escape($data['capacite'] ?? '') ?>"
                placeholder="Ex: 50"
                class="<?= isset($errors['capacite'])
                    ? 'input--error'
                    : ''
                ?>"
            >

            <?php foreach ($errors['capacite'] ?? [] as $err): ?>

                <span class="field-error">
                    <?= $escape($err) ?>
                </span>

            <?php endforeach; ?>

        </div>

        <div class="form__group">

            <label for="type">
                Type
                <span class="required">*</span>
            </label>

            <select
                id="type"
                name="type"
                class="<?= isset($errors['type'])
                    ? 'input--error'
                    : ''
                ?>"
            >

                <option value="">
                    -- Choisir un type --
                </option>

                <?php foreach ($typesAutorises as $type): ?>

                    <option
                        value="<?= $escape($type) ?>"
                        <?= (($data['type'] ?? '') === $type)
                            ? 'selected'
                            : ''
                        ?>
                    >
                        <?= $escape(ucfirst($type)) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <?php foreach ($errors['type'] ?? [] as $err): ?>

                <span class="field-error">
                    <?= $escape($err) ?>
                </span>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="form__group form__group--checkbox">

        <label>

            <input
                type="hidden"
                name="active"
                value="0"
            >

            <input
                type="checkbox"
                name="active"
                value="1"
                <?= (($data['active'] ?? true)
                    ? 'checked'
                    : ''
                ) ?>
            >

            <span>
                Salle active et disponible à la réservation
            </span>

        </label>

    </div>

    <div class="form__actions">

        <a
            href="/salles"
            class="btn btn--ghost"
        >
            Annuler
        </a>

        <button
            type="submit"
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
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>

            <?= $isEdit
                ? 'Mettre à jour'
                : 'Créer la salle'
            ?>

        </button>

    </div>

</form>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layout/base.php';

?>
