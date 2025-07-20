<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Afficher la liste des documents de l'utilisateur
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())->get();

        return view('documents.index', compact('documents'));
    }

    // Afficher le formulaire d’upload
    public function create()
    {
        return view('documents.create');
    }

    // Enregistrer un fichier uploadé
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'fichier' => 'required|file|max:10240', // max 10MB
        ]);

        // Stocker le fichier dans storage/app/private_docs
        $path = $request->file('fichier')->store('private_docs');

        Document::create([
            'user_id' => auth()->id(),
            'nom' => $request->nom,
            'type' => $request->type,
            'chemin_fichier' => $path, // chiffrement automatique dans le modèle
        ]);

        return redirect()->route('documents.index')->with('success', 'Document enregistré avec succès.');
    }

    // Afficher un document (détails)
    public function show($id)
    {
        $document = Document::findOrFail($id);

        // Vérifier l'accès : propriétaire ou admin
        if ($document->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        return view('documents.show', compact('document'));
    }

    // Télécharger le fichier chiffré
    public function download($id)
    {
        $document = Document::findOrFail($id);

        if ($document->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        $path = storage_path('app/' . $document->chemin_fichier); // déchiffré automatiquement dans le modèle

        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé.');
        }

        return response()->download($path, $document->nom);
    }

    // Supprimer un document et son fichier
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if ($document->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        // Supprimer le fichier physiquement
        Storage::delete($document->chemin_fichier);

        // Supprimer l'entrée en base
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document supprimé.');
    }
}
