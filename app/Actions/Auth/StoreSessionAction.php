<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final readonly class StoreSessionAction
{
    public function execute(array $data): void
    {
        $user = User::query()
            ->whereEmail($data['email'])
            ->first();
        $this->validateUser($user, $data['password']);

        Auth::login($user);
        session()->regenerate();
    }

    private function validatePassword(string $plainPassword, string $hashedPassword): bool
    {
        return Hash::check($plainPassword, $hashedPassword);
    }

    private function validateUser(?User $user, string $plainPassword): void
    {
        throw_if(! $user || ! $this->validatePassword($plainPassword, $user->password), InvalidCredentialsException::class);
    }
}
