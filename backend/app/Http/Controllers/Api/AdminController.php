<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Obtenir tous les utilisateurs avec filtres
     */
    public function getAllUsers(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            $users = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Utilisateurs récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des utilisateurs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs'
            ], 500);
        }
    }

    /**
     * Créer un nouvel utilisateur (gestionnaire ou admin uniquement)
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'MotDePasse' => 'required|string|min:6',
                'role' => 'required|in:gestionnaire,admin',
                'statut' => 'nullable|in:actif,bloque'
            ]);

            // Hash du mot de passe
            $validated['MotDePasse'] = Hash::make($validated['MotDePasse']);
            
            // Statut par défaut
            if (!isset($validated['statut'])) {
                $validated['statut'] = 'actif';
            }

            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Utilisateur créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un utilisateur (actif/bloque)
     */
    public function toggleUserStatus(int $id, Request $request): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $newStatus = $request->input('status', 'bloque');
            
            if (!in_array($newStatus, ['actif', 'bloque'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Statut invalide'
                ], 400);
            }
            
            $user->statut = $newStatus;
            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Statut utilisateur mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }
}
