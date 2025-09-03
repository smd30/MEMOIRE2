<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'MotDePasse' => 'required|string|min:8|confirmed',
            'Telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Créer l'utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'MotDePasse' => Hash::make($request->MotDePasse),
                'Telephone' => $request->Telephone,
                'adresse' => $request->adresse,
                'role' => 'client',
                'statut' => 'actif',
            ]);

            // Créer le profil client avec des valeurs par défaut
            ClientProfile::create([
                'user_id' => $user->id,
                'address' => $request->adresse,
                'city' => $request->city ?? 'À compléter',
                'postal_code' => $request->postal_code ?? '00000',
                'country' => $request->country ?? 'France',
                'birth_date' => $request->birth_date ?? '1990-01-01',
                'driving_license_number' => $request->driving_license_number ?? 'À compléter',
                'driving_license_date' => $request->driving_license_date ?? '2020-01-01',
                'driving_experience_years' => $request->driving_experience_years ?? 0,
                'has_garage' => $request->has_garage ?? false,
            ]);

            // Créer le token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => [
                    'user' => $user->load('clientProfile'),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Connexion de l'utilisateur
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->MotDePasse)) {
                throw ValidationException::withMessages([
                    'email' => ['Les identifiants fournis sont incorrects.'],
                ]);
            }

            if ($user->statut !== 'actif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Compte désactivé'
                ], 403);
            }

            // Mettre à jour la dernière connexion
            $user->update(['last_login_at' => now()]);

            // Supprimer les anciens tokens
            $user->tokens()->delete();

            // Créer un nouveau token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'user' => $user->load(['clientProfile']),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Supprimer le token actuel
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Obtenir les informations de l'utilisateur connecté
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user()->load(['clientProfile']);

            return response()->json([
                'success' => true,
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des informations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            // Vérifier l'ancien mot de passe
            if (!Hash::check($request->current_password, $user->MotDePasse)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mot de passe actuel incorrect'
                ], 422);
            }

            // Mettre à jour le mot de passe
            $user->update([
                'MotDePasse' => Hash::make($request->new_password)
            ]);

            // Supprimer tous les tokens (forcer la reconnexion)
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mot de passe modifié avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de mot de passe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'Telephone' => 'sometimes|string|max:20',
            'adresse' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:10',
            'country' => 'sometimes|string|max:100',
            'has_garage' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            // Mettre à jour les informations utilisateur
            $userData = $request->only(['nom', 'prenom', 'Telephone', 'adresse']);
            if (!empty($userData)) {
                $user->update($userData);
            }

            // Mettre à jour le profil client
            if ($user->clientProfile) {
                $profileData = $request->only([
                    'city', 'postal_code', 'country', 'has_garage'
                ]);

                if (!empty($profileData)) {
                    $user->clientProfile->update($profileData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'data' => $user->load('clientProfile')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rafraîchir le token d'authentification
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Supprimer l'ancien token
            $user->tokens()->delete();
            
            // Créer un nouveau token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token rafraîchi avec succès',
                'data' => [
                    'user' => $user->load('clientProfile'),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement du token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les données utilisateur
     */
    public function getUserData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Données par défaut si pas encore créées
            $userData = [
                'preferences' => [
                    'theme' => 'light',
                    'language' => 'fr',
                    'notifications' => [
                        'email' => true,
                        'push' => false,
                        'sms' => false
                    ],
                    'dashboard' => [
                        'showStats' => true,
                        'showRecentActivity' => true,
                        'layout' => 'grid'
                    ]
                ],
                'activities' => [],
                'lastSync' => now()->toISOString()
            ];

            // Si l'utilisateur a des données stockées, les récupérer
            if ($user->user_data) {
                $userData = array_merge($userData, json_decode($user->user_data, true));
            }

            return response()->json([
                'success' => true,
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour les données utilisateur
     */
    public function updateUserData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $userData = $request->all();
            $userData['lastSync'] = now()->toISOString();
            
            // Sauvegarder dans la base de données
            $user->update([
                'user_data' => json_encode($userData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Données mises à jour avec succès',
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des données',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Synchroniser les données utilisateur
     */
    public function syncUserData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Récupérer les données actuelles
            $currentData = $user->user_data ? json_decode($user->user_data, true) : [];
            
            // Mettre à jour le timestamp de synchronisation
            $currentData['lastSync'] = now()->toISOString();
            
            // Sauvegarder
            $user->update([
                'user_data' => json_encode($currentData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Données synchronisées avec succès',
                'data' => $currentData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la synchronisation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les données utilisateur
     */
    public function exportUserData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $exportData = [
                'user' => [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ],
                'profile' => $user->clientProfile,
                'user_data' => $user->user_data ? json_decode($user->user_data, true) : null,
                'export_date' => now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'data' => $exportData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Importer les données utilisateur
     */
    public function importUserData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $importData = $request->all();
            
            // Mettre à jour les données utilisateur
            if (isset($importData['user_data'])) {
                $user->update([
                    'user_data' => json_encode($importData['user_data'])
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Données importées avec succès',
                'data' => $importData['user_data'] ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques utilisateur
     */
    public function getUserStats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $stats = [
                'total_contracts' => $user->contracts()->count(),
                'total_vehicles' => $user->vehicles()->count(),
                'total_sinistres' => $user->sinistres()->count(),
                'total_payments' => $user->payments()->count(),
                'last_login' => $user->last_login_at,
                'account_age_days' => $user->created_at->diffInDays(now()),
                'profile_completion' => $this->calculateProfileCompletion($user)
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer le pourcentage de complétion du profil
     */
    private function calculateProfileCompletion($user): int
    {
        $fields = [
            'nom', 'prenom', 'email', 'Telephone', 'adresse'
        ];
        
        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }
}
