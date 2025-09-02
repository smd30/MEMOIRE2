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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?? 'Non renseigné',
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            // Créer le profil client avec des valeurs par défaut
            ClientProfile::create([
                'user_id' => $user->id,
                'address' => $request->address ?? 'À compléter',
                'city' => $request->city ?? 'À compléter',
                'postal_code' => $request->postal_code ?? '00000',
                'country' => $request->country ?? 'France',
                'birth_date' => $request->birth_date ?? '1990-01-01',
                'driving_license_number' => $request->driving_license_number ?? 'À compléter',
                'driving_license_date' => $request->driving_license_date ?? '2020-01-01',
                'driving_experience_years' => $request->driving_experience_years ?? 0,
                'has_garage' => $request->has_garage ?? false,
            ]);

            // Assigner le rôle client par défaut
            $clientRole = Role::where('name', 'client')->first();
            if ($clientRole) {
                $user->roles()->attach($clientRole->id);
            }

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

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Les identifiants fournis sont incorrects.'],
                ]);
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Compte désactivé'
                ], 403);
            }

            // Mettre à jour la date de dernière connexion
            $user->updateLastLogin();

            // Supprimer les anciens tokens
            $user->tokens()->delete();

            // Créer un nouveau token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'user' => $user->load(['clientProfile', 'roles']),
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
     * Rafraîchir le token
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Supprimer l'ancien token
            $request->user()->currentAccessToken()->delete();

            // Créer un nouveau token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token rafraîchi avec succès',
                'data' => [
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
     * Obtenir les informations de l'utilisateur connecté
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user()->load(['clientProfile', 'roles']);

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
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mot de passe actuel incorrect'
                ], 422);
            }

            // Mettre à jour le mot de passe
            $user->update([
                'password' => Hash::make($request->new_password)
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
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
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
            if ($request->has('name')) {
                $user->update(['name' => $request->name]);
            }

            if ($request->has('phone')) {
                $user->update(['phone' => $request->phone]);
            }

            // Mettre à jour le profil client
            if ($user->clientProfile) {
                $profileData = $request->only([
                    'address', 'city', 'postal_code', 'country', 'has_garage'
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
}
