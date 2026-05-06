<?php
namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index()
    {
        return response()->json(Platform::with('equipe', 'documents')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string',
            'equipe_id'   => 'required|exists:equipes,id',
            'description' => 'nullable|string',
            'url'         => 'nullable|string',
        ]);

        $platform = Platform::create($request->only(['nom', 'equipe_id', 'description', 'url']));
        return response()->json($platform, 201);
    }

    public function show($id)
    {
        return response()->json(Platform::with('equipe', 'documents')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $platform = Platform::findOrFail($id);
        $platform->update($request->only(['nom', 'description', 'url']));
        return response()->json($platform);
    }

    public function destroy($id)
    {
        Platform::findOrFail($id)->delete();
        return response()->json(['message' => 'Platform supprimée']);
    }
}