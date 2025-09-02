<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Récupérer tous les véhicules de l'utilisateur connecté
     */
    public function index(): JsonResponse
    {
        try {
            $vehicles = Vehicle::where('user_id', Auth::id())
                ->where('is_active', true)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Véhicules récupérés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des véhicules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer un véhicule spécifique
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est propriétaire du véhicule
            if ($vehicle->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $vehicle,
                'message' => 'Véhicule récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du véhicule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau véhicule
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'plate_number' => 'required|string|unique:vehicles,plate_number',
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'power_fiscal' => 'required|integer|min:1|max:50',
                'category' => 'required|string|max:255',
                'fuel_type' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'mileage' => 'required|integer|min:0',
                'additional_features' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vehicle = Vehicle::create([
                'user_id' => Auth::id(),
                'plate_number' => $request->plate_number,
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'power_fiscal' => $request->power_fiscal,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
                'fuel_type' => $request->fuel_type,
                'color' => $request->color,
                'mileage' => $request->mileage,
                'additional_features' => $request->additional_features,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'data' => $vehicle,
                'message' => 'Véhicule créé avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du véhicule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un véhicule
     */
    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est propriétaire du véhicule
            if ($vehicle->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'plate_number' => 'sometimes|required|string|unique:vehicles,plate_number,' . $vehicle->id,
                'brand' => 'sometimes|required|string|max:255',
                'model' => 'sometimes|required|string|max:255',
                'year' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
                'power_fiscal' => 'sometimes|required|integer|min:1|max:50',
                'category' => 'sometimes|required|string|max:255',
                'fuel_type' => 'sometimes|required|string|max:255',
                'color' => 'sometimes|required|string|max:255',
                'mileage' => 'sometimes|required|integer|min:0',
                'additional_features' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vehicle->update($request->only([
                'plate_number', 'brand', 'model', 'year', 'power_fiscal',
                'category', 'sub_category', 'fuel_type', 'color', 'mileage', 'additional_features'
            ]));

            return response()->json([
                'success' => true,
                'data' => $vehicle->fresh(),
                'message' => 'Véhicule mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du véhicule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un véhicule (soft delete)
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est propriétaire du véhicule
            if ($vehicle->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Soft delete en désactivant le véhicule
            $vehicle->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Véhicule supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du véhicule',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
