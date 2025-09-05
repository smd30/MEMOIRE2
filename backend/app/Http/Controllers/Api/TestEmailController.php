<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestEmailController extends Controller
{
    public function test(Request $request)
    {
        try {
            $data = $request->all();
            
            // Test 1: Vérifier que les modèles existent
            try {
                $compagnie = \App\Models\Compagnie::find(1);
                \Log::info('Test Compagnie:', ['exists' => $compagnie ? 'yes' : 'no']);
            } catch (\Exception $e) {
                \Log::error('Erreur Compagnie:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Compagnie model error: ' . $e->getMessage()], 500);
            }
            
            // Test 2: Vérifier la table users
            try {
                $userCount = \App\Models\User::count();
                \Log::info('Test User:', ['count' => $userCount]);
            } catch (\Exception $e) {
                \Log::error('Erreur User:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'User model error: ' . $e->getMessage()], 500);
            }
            
            // Test 3: Vérifier la table vehicules
            try {
                $vehiculeCount = \App\Models\Vehicule::count();
                \Log::info('Test Vehicule:', ['count' => $vehiculeCount]);
            } catch (\Exception $e) {
                \Log::error('Erreur Vehicule:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Vehicule model error: ' . $e->getMessage()], 500);
            }
            
            // Test 4: Vérifier la table contrats
            try {
                $contratCount = \App\Models\Contrat::count();
                \Log::info('Test Contrat:', ['count' => $contratCount]);
            } catch (\Exception $e) {
                \Log::error('Erreur Contrat:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Contrat model error: ' . $e->getMessage()], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tous les modèles fonctionnent',
                'data' => [
                    'compagnie_exists' => $compagnie ? true : false,
                    'user_count' => $userCount,
                    'vehicule_count' => $vehiculeCount,
                    'contrat_count' => $contratCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur générale:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}