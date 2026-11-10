<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|string|lowercase|email|max:255|unique:' . User::class,
            'phone'    => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $phone = PhoneNumber::tryNormalize($request->phone);
        if ($phone && User::where('phone', '0' . substr($phone, 3))->exists()) { throw ValidationException::withMessages(['phone' => 'Nomor HP sudah terdaftar.']); }
        $user = new User([
            'name' => $request->name,
            'email' => $request->email ?: 'phone-' . Str::lower((string) Str::ulid()) . '@pelanggan.laundrycoin.invalid',
            'phone' => $phone ? '0' . substr($phone, 3) : null, 'password' => Hash::make($request->password),
        ]);
        // Role selalu dikunci sebagai pelanggan di sisi server
        $user->role = UserRole::PELANGGAN;
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
