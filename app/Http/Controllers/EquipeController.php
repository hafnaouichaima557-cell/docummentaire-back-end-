<?php
namespace App\Http\Controllers;

use App\Models\Equipe;
use Illuminate\Http\Request;

class EquipeController extends Controller
{
    public function index()
    {
        return response()->json(Equipe::with('utilisateurs', 'platforms')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string',
            'description' => 'nullable|string',
        ]);

        $equipe = Equipe::create($request->only(['nom', 'description']));
        return response()->json($equipe, 201);
    }

    public function show($id)
    {
        return response()->json(Equipe::with('utilisateurs', 'platforms')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $equipe = Equipe::findOrFail($id);
        $equipe->update($request->only(['nom', 'description']));
        return response()->json($equipe);
    }

    public function destroy($id)
    {
        Equipe::findOrFail($id)->delete();
        return response()->json(['message' => 'Equipe supprimée']);
    }
}