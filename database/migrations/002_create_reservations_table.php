<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up(Capsule $capsule): void
    {
        $schema = $capsule->getConnection()->getSchemaBuilder();

        $schema->create('reservations', function ($table) {
            $table->id();

            $table->foreignId('salle_id')
                ->constrained('salles')
                ->cascadeOnDelete();

            $table->string('responsable', 150);
            $table->string('email', 255);
            $table->string('motif', 255);

            $table->dateTime('date_debut');
            $table->dateTime('date_fin');

            $table->enum('statut', [
                'confirmée',
                'annulée',
            ])->default('confirmée');

            $table->timestamps();
        });
    }

    public function down(Capsule $capsule): void
    {
        $schema = $capsule->getConnection()->getSchemaBuilder();

        $schema->dropIfExists('reservations');
    }
};
