<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Historique;
use App\Models\Notification;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // Liste tous les documents
    public function index()
    {
        $documents = Document::with('createur', 'platform')->get();
        return response()->json($documents);
    }

    // Créer un document
    public function store(Request $request)
    {
        $request->validate([
            'titre'       => 'required|string',
            'platform_id' => 'required|exists:platforms,id',
            'chemin'      => 'nullable|string',
        ]);

        $document = Document::create([
            'titre'       => $request->titre,
            'platform_id' => $request->platform_id,
            'cree_par'    => auth()->id(),
            'statut'      => 'brouillon',
            'version'     => '1.0',
            'chemin'      => $request->chemin,
        ]);

        // Log
        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'document_id'     => $document->id,
            'action'          => 'creation',
            'table_concernee' => 'documents',
            'valeur_avant'    => null,
            'valeur_apres'    => $document->toArray(),
        ]);

        return response()->json($document, 201);
    }

    // Afficher un document
    public function show($id)
    {
        $document = Document::with('createur', 'platform')->findOrFail($id);
        return response()->json($document);
    }

    // Modifier un document
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $avant = $document->toArray();

        $document->update($request->only(['titre', 'chemin', 'version']));

        // Log
        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'document_id'     => $document->id,
            'action'          => 'modification',
            'table_concernee' => 'documents',
            'valeur_avant'    => $avant,
            'valeur_apres'    => $document->toArray(),
        ]);

        return response()->json($document);
    }

    // Supprimer un document
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return response()->json(['message' => 'Document supprimé']);
    }

    // Soumettre pour relecture
    public function soumettre($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['statut' => 'en_relecture']);

        // Notification
        Notification::create([
            'utilisateur_id' => auth()->id(),
            'document_id'    => $document->id,
            'message'        => 'Document soumis pour relecture : ' . $document->titre,
            'type'           => 'soumission',
        ]);

        // Log
        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'document_id'     => $document->id,
            'action'          => 'soumission',
            'table_concernee' => 'documents',
            'valeur_avant'    => ['statut' => 'brouillon'],
            'valeur_apres'    => ['statut' => 'en_relecture'],
        ]);

        return response()->json(['message' => 'Document soumis pour relecture']);
    }

    // Valider un document (peer review)
    public function validerPeer($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['statut' => 'en_validation']);

        Notification::create([
            'utilisateur_id' => auth()->id(),
            'document_id'    => $document->id,
            'message'        => 'Document validé par peer review : ' . $document->titre,
            'type'           => 'validation_peer',
        ]);

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'document_id'     => $document->id,
            'action'          => 'validation_peer',
            'table_concernee' => 'documents',
            'valeur_avant'    => ['statut' => 'en_relecture'],
            'valeur_apres'    => ['statut' => 'en_validation'],
        ]);

        return response()->json(['message' => 'Document envoyé au responsable']);
    }

    // Rejeter un document
    public function rejeter(Request $request, $id)
    {
        $request->validate(['motif' => 'required|string']);

        $document = Document::findOrFail($id);
        $document->update(['statut' => 'rejete']);

        Notification::create([
            'utilisateur_id' => $document->cree_par,
            'document_id'    => $document->id,
            'message'        => 'Document rejeté : ' . $request->motif,
            'type'           => 'rejet',
        ]);

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'document_id'     => $document->id,
            'action'          => 'rejet',
            'table_concernee' => 'documents',
            'valeur_avant'    => ['statut' => $document->statut],
            'valeur_apres'    => ['statut' => 'rejete', 'motif' => $request->motif],
        ]);

        return response()->json(['message' => 'Document rejeté']);
    }

    // Publier un document (responsable)
    public function publier($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['statut' => 'publie']);

        Notification::create([
            'utilisateur_id' => $document->cree_par,
            'document_id'    => $document->id,
            'message'        => 'Document publié : ' . $document->titre,
            'type'           => 'publication',
        ]);

        Historique::create([
            'utilisateur_id'  => auth()->id(),
            'document_id'     => $document->id,
            'action'          => 'publication',
            'table_concernee' => 'documents',
            'valeur_avant'    => ['statut' => 'en_validation'],
            'valeur_apres'    => ['statut' => 'publie'],
        ]);

        return response()->json(['message' => 'Document publié avec succès']);
    }
}