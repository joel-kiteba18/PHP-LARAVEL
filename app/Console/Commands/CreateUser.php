<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

#[Signature('app:create-user')]
#[Description('Créer un compte utilisateur pour accéder à l’application')]
class CreateUser extends Command
{
    public function handle(): int
    {
        $name = $this->ask('Nom');
        $email = $this->ask('Adresse e-mail');
        $password = $this->secret('Mot de passe (12 caractères minimum)');
        $passwordConfirmation = $this->secret('Confirmer le mot de passe');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(12)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $validated = $validator->validated();

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $this->components->info("Le compte {$email} a été créé.");

        return self::SUCCESS;
    }
}
