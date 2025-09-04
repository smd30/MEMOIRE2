<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use App\Models\Modele;
use Illuminate\Http\Request;

class MarqueController extends Controller
{
    /**
     * Obtenir toutes les marques
     */
    public function index()
    {
        $marques = Marque::active()
            ->orderBy('nom')
            ->get(['id', 'nom', 'pays_origine']);

        return response()->json([
            'success' => true,
            'data' => $marques
        ]);
    }

    /**
     * Obtenir les modèles d'une marque
     */
    public function getModeles($marqueId)
    {
        $modeles = Modele::active()
            ->byMarque($marqueId)
            ->orderBy('nom')
            ->get(['id', 'nom', 'categorie_vehicule']);

        return response()->json([
            'success' => true,
            'data' => $modeles
        ]);
    }

    /**
     * Rechercher des marques
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $marques = Marque::active()
            ->search($search)
            ->orderBy('nom')
            ->limit(10)
            ->get(['id', 'nom', 'pays_origine']);

        return response()->json([
            'success' => true,
            'data' => $marques
        ]);
    }

    /**
     * Obtenir toutes les marques avec leurs modèles
     */
    public function getAllWithModeles()
    {
        $marques = Marque::active()
            ->with(['modeles' => function($query) {
                $query->active()->orderBy('nom');
            }])
            ->orderBy('nom')
            ->get(['id', 'nom', 'pays_origine']);

        return response()->json([
            'success' => true,
            'data' => $marques
        ]);
    }
}
