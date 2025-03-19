<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        // Valideer het formulier
        // Elk veld is verplicht
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'Required|min:8|confirmed',
        ]);

        // Schrijf de aanmeld logica om in te loggen.
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Als je ingelogd bent stuur je de bezoeker door naar de intented "profile" route (zie hieronder)
            return redirect()->intended(route('profile'));
        }

        // Als je gegevens fout zijn stuur je terug naar het formulier met
        // een melding voor het email veld dat de gegevens niet correct zijn.

    }

    public function register()
    {
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        // Valideer het formulier.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Elk veld is verplicht / Wachtwoord en confirmatie moeten overeen komen / Email adres moet uniek zijn
        // Bewaar een nieuwe gebruiker in de databank met een beveiligd wachtwoord.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        // BONUS: Verstuur een email naar de gebruiker waarin staat dat er een nieuwe account geregistreerd is voor de gebruiker.

        return redirect()->route('login');
    }

    public function logout()
    {
        // Gebruiker moet uitloggen

        return back();
    }
}
