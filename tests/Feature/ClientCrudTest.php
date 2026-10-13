<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_a_client_can_be_created_viewed_updated_and_deleted(): void
    {
        Storage::fake('public');

        $this->get('/')
            ->assertRedirect(route('clients.index'));

        $this->get(route('clients.index'))
            ->assertOk();

        $this->get(route('clients.create'))
            ->assertOk()
            ->assertSee('Créer le client');

        $name = fake()->unique()->name();
        $phone = fake()->unique()->numerify('+336########');

        $this->post(route('clients.store'), [
            'name' => $name,
            'phone' => $phone,
            'photo' => UploadedFile::fake()->image('aicha.jpg'),
        ])->assertRedirect();

        $client = Client::query()->where('phone', $phone)->firstOrFail();
        $this->assertTrue(Storage::disk('public')->exists($client->photo));

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => $name,
            'phone' => $phone,
        ]);

        $this->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($name)
            ->assertSee($phone);

        $this->get(route('clients.edit', $client))
            ->assertOk()
            ->assertSee('Enregistrer les modifications');

        $oldPhoto = $client->photo;

        $this->put(route('clients.update', $client), [
            'name' => $name,
            'phone' => '+33 6 98 76 54 32',
            'photo' => UploadedFile::fake()->image('aicha-new.jpg'),
        ])->assertRedirect(route('clients.show', $client));

        $client->refresh();
        $this->assertFalse(Storage::disk('public')->exists($oldPhoto));
        $this->assertTrue(Storage::disk('public')->exists($client->photo));
        $this->assertSame('+33 6 98 76 54 32', $client->phone);

        $this->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
        $this->assertFalse(Storage::disk('public')->exists($client->photo));
    }
}
