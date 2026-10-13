<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('commandes')) {
            $hasProduitForeignKey = collect(Schema::getForeignKeys('commandes'))
                ->contains(fn (array $foreignKey): bool => $foreignKey['columns'] === ['produit_id']
                    && $foreignKey['foreign_table'] === 'produits'
                    && $foreignKey['foreign_columns'] === ['id']);

            if (! $hasProduitForeignKey) {
                Schema::table('commandes', function (Blueprint $table): void {
                    $table->foreign('produit_id')->references('id')->on('produits');
                });
            }

            return;
        }

        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('produit_id')->constrained('produits');
            $table->unsignedInteger('quantite');
            $table->decimal('prix_unitaire', 10, 2)->unsigned();
            $table->decimal('total', 10, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
