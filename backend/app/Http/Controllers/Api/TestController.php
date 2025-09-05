<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        try {
            // Test 1: Validation simple
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'vehicule' => 'required|array',
                'compagnie_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Test 2: Récupération des données
            $vehiculeData = $request->input('vehicule');
            $compagnieId = $request->input('compagnie_id');

            // Test 3: Vérification de la compagnie
            $compagnie = \App\Models\Compagnie::find($compagnieId);
            if (!$compagnie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Compagnie non trouvée'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Test réussi',
                'data' => [
                    'compagnie' => $compagnie->nom,
                    'vehicule' => $vehiculeData,
                    'compagnie_id' => $compagnieId
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
