<?php
namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'motdepasse' => 'required',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->motdepasse, $user->motdepasse)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        if ($user->statut === 'inactif') {
            return response()->json([
                'message' => 'Compte désactivé'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Log
        Historique::create([
            'utilisateur_id'  => $user->id,
            'action'          => 'login',
            'table_concernee' => 'utilisateurs',
            'valeur_avant'    => null,
            'valeur_apres'    => ['email' => $user->email],
        ]);

        return response()->json([
            'token' => $token,
            'user'  => $user->load('roles', 'equipe'),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }

    // Profile
    public function me(Request $request)
    {
        return response()->json(
            $request->user()->load('roles', 'equipe')
        );
    }
}