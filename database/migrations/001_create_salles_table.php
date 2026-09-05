<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up(Capsule $capsule): void
    {
        $schema = $capsule->getConnection()->getSchemaBuilder();

        $schema->create('salles', function ($table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->enum('type', [
                'cours',
                'informatique',
                'laboratoire',
                'amphitheatre',
                'reunion',
            ]);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(Capsule $capsule): void
    {
        $schema = $capsule->getConnection()->getSchemaBuilder();

        $schema->dropIfExists('salles');
    }
};
