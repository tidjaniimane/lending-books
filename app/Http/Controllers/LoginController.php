<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecteur;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle lecteur login
    public function lecteurLogin(Request $request)
    {
        $request->validate([
            'num_inscription' => 'required',
            'full_name' => 'required|string',
        ]);

        $lecteur = Lecteur::where('lec_id', $request->num_inscription)
            ->whereRaw("CONCAT(lec_nom, ' ', lec_prenom) = ?", [$request->full_name])
            ->first();

        if ($lecteur) {
            Auth::login($lecteur);
            return redirect()->route('lecteurs.dashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid numéro d\'inscription or name.',
        ])->withInput();
    }
public function adminLogin(Request $request)
{
    // Validate inputs
    $request->validate([
        'full_name' => 'required|string',
        'admin_password' => 'required|string',
    ]);

    // Split full name into nom and prénom
    $names = explode(' ', trim($request->full_name), 2);
    $nom = $names[0] ?? '';
    $prenom = $names[1] ?? '';

    // Find the admin user with matching name and role
    $admin = Lecteur::where('is_admin', 1)
        ->where('lec_nom', $nom)
        ->where('lec_prenom', $prenom)
        ->first();

    if (!$admin) {
        return back()->withErrors(['full_name' => 'Admin not found with provided name.'])->withInput();
    }

    // Verify password
    if (!Hash::check($request->admin_password, $admin->lec_password)) {
        return back()->withErrors(['admin_password' => 'Incorrect admin password.'])->withInput();
    }

    // Login admin
    Auth::login($admin);

    return redirect()->route('dashboard.index');
}


public function employerLogin(Request $request)
{
    // Validate input
    $request->validate([
        'full_name' => 'required|string',
        'password' => 'required|string',
    ]);

    // Split full name into first and last name (assuming format: "First Last")
    $names = explode(' ', trim($request->full_name), 2);
    $nom = $names[0] ?? '';
    $prenom = $names[1] ?? '';

    // Find employer user with is_admin = 2
    $employer = Lecteur::where('is_admin', 2)
        ->where('lec_nom', $nom)
        ->where('lec_prenom', $prenom)
        ->first();

    if ($employer && Hash::check($request->password, $employer->lec_password)) {
        Auth::login($employer);
        return redirect()->route('dashboard.index');
    }

    return back()->withErrors([
        'password' => 'Identifiants incorrects.',
    ])->withInput($request->only('full_name'));
}



    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}