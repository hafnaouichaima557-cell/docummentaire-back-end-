<?php
namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Historique;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()
    {
        return response()->json(Utilisateur::with('roles', 'equipe')->get());
    }

    public function show($id)
    {
        return response()->json(Utilisateur::with('roles', 'equipe')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'        => 'required|string',
            'email'      => 'required|email|unique:utilisateurs',
            'motdepasse' => 'required|min:6',
            'equipe_id'  => 'required|exists:equipes,id',
            'role_id'    => 'required|exists:roles,id',
            'tel'        => 'nullable|string',
        ]);

        $utilisateur = Utilisateur::create([
            'nom'        => $request->nom,
            'email'      => $request->email,
            'motdepasse' => bcrypt($request->motdepasse),
            'equipe_id'  => $request->equipe_id,
            'tel'        => $request->tel,
            'statut'     => 'actif',
        ]);

        $utilisateur->roles()->attach($request->role_id);

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'action'          => 'creation_utilisateur',
            'table_concernee' => 'utilisateurs',
            'valeur_avant'    => null,
            'valeur_apres'    => $utilisateur->toArray(),
        ]);

        return response()->json($utilisateur->load('roles', 'equipe'), 201);
    }

    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $avant = $utilisateur->toArray();

        $utilisateur->update($request->only(['nom', 'email', 'tel', 'avatar', 'statut']));

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'action'          => 'modification',
            'table_concernee' => 'utilisateurs',
            'valeur_avant'    => $avant,
            'valeur_apres'    => $utilisateur->toArray(),
        ]);

        return response()->json($utilisateur);
    }

    public function destroy($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'action'          => 'suppression_utilisateur',
            'table_concernee' => 'utilisateurs',
            'valeur_avant'    => $utilisateur->toArray(),
            'valeur_apres'    => null,
        ]);

        $utilisateur->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    public function assignerRole(Request $request, $id)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);

        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->roles()->sync([$request->role_id]);

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'action'          => 'modification_role',
            'table_concernee' => 'utilisateur_role',
            'valeur_avant'    => null,
            'valeur_apres'    => ['role_id' => $request->role_id],
        ]);

        return response()->json(['message' => 'Rôle assigné avec succès']);
    }
}