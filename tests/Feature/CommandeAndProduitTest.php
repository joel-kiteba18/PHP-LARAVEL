<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commandes;
use App\Models\Produits;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommandeAndProduitTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_products_can_be_created_and_listed(): void
    {
        $response = $this->post(route('produits.store'), [
            'nom' => 'Cahier de test',
            'description' => 'Papier quadrillé',
            'prix' => '2.50',
            'stock' => 12,
        ]);

        $produit = Produits::query()->where('nom', 'Cahier de test')->firstOrFail();

        $response->assertRedirect(route('produits.index'));
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'prix' => '2.50',
            'stock' => 12,
        ]);

        $this->get(route('produits.index'))
            ->assertOk()
            ->assertSee('Cahier de test')
            ->assertSee('12 unités');
    }

    public function test_creating_a_commande_records_the_stock_exit_and_printable_bon(): void
    {
        $client = Client::query()->create([
            'name' => 'Client commande',
            'phone' => '0600000000',
        ]);
        $produit = Produits::query()->create([
            'nom' => 'Produit commande',
            'prix' => '12.50',
            'stock' => 8,
        ]);

        $response = $this->post(route('commandes.store'), [
            'client_id' => $client->id,
            'produit_id' => $produit->id,
            'quantite' => 3,
        ]);

        $commande = Commandes::query()->where('client_id', $client->id)->firstOrFail();

        $response->assertRedirect(route('commandes.show', $commande));
        $this->assertDatabaseHas('commandes', [
            'id' => $commande->id,
            'prix_unitaire' => '12.50',
            'quantite' => 3,
            'total' => '37.50',
        ]);
        $this->assertSame(5, $produit->fresh()->stock);

        $this->get(route('commandes.show', $commande))
            ->assertOk()
            ->assertSee('Bon de commande')
            ->assertSee('37,50');

        $this->get(route('commandes.sorties'))
            ->assertOk()
            ->assertSee('3')
            ->assertSee('Produit commande');
    }

    public function test_a_commande_cannot_be_created_when_stock_is_insufficient(): void
    {
        $client = Client::query()->create([
            'name' => 'Client stock',
            'phone' => '0600000001',
        ]);
        $produit = Produits::query()->create([
            'nom' => 'Produit limité',
            'prix' => '5.00',
            'stock' => 2,
        ]);

        $this->from(route('commandes.create'))
            ->post(route('commandes.store'), [
                'client_id' => $client->id,
                'produit_id' => $produit->id,
                'quantite' => 3,
            ])
            ->assertRedirect(route('commandes.create'))
            ->assertSessionHasErrors('quantite');

        $this->assertSame(2, $produit->fresh()->stock);
        $this->assertDatabaseMissing('commandes', [
            'client_id' => $client->id,
            'produit_id' => $produit->id,
        ]);
    }

    public function test_products_associated_with_orders_cannot_be_deleted(): void
    {
        $client = Client::query()->create([
            'name' => 'Client historique',
            'phone' => '0600000002',
        ]);
        $produit = Produits::query()->create([
            'nom' => 'Produit historique',
            'prix' => '8.00',
            'stock' => 0,
        ]);
        Commandes::query()->create([
            'client_id' => $client->id,
            'produit_id' => $produit->id,
            'quantite' => 1,
            'prix_unitaire' => '8.00',
            'total' => '8.00',
        ]);

        $this->delete(route('produits.destroy', $produit))
            ->assertRedirect(route('produits.index'))
            ->assertSessionHas('error');

        $this->assertModelExists($produit);
    }
}
