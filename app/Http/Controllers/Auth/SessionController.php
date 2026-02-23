<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\DeleteSessionAction;
use App\Actions\Auth\StoreSessionAction;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Auth\StoreSessionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final readonly class SessionController
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(StoreSessionRequest $request, StoreSessionAction $action): RedirectResponse
    {
        try {
            $action->execute($request->validated());

            return redirect()->to(route('dashboard'))->with('success', 'Welcome back!');
        } catch (InvalidCredentialsException) {
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ])->onlyInput('email');
        }
    }

    public function destroy(DeleteSessionAction $action): RedirectResponse
    {
        $action->execute();

        return redirect(route('login'))->with('success', 'Logged out successfully.');
    }
}
