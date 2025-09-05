<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestUserController extends Controller
{
    public function test(Request $request)
    {
        try {
            $data = $request->all();
            
            // Test de création d'utilisateur simple
            $user = \App\Models\User::create([
                'nom' => 'Test',
                'prenom' => 'User',
                'email' => 'test.' . time() . '@test.com',
                'Telephone' => '+221777777777',
                'adresse' => '123 Test',
                'role' => 'client',
                'statut' => 'actif',
                'MotDePasse' => bcrypt('password123'),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'user_id' => $user->id,
                'email' => $user->email
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
