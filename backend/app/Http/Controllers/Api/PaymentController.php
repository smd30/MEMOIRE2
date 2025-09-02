<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Afficher tous les paiements de l'utilisateur
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $payments = Payment::where('user_id', $user->id)
                ->with(['contract'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des paiements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un paiement spécifique
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $payment = Payment::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['contract'])
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $payment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau paiement
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:card,bank_transfer,cash',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
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
            
            // Vérifier que le contrat appartient à l'utilisateur
            $contract = Contract::where('id', $request->contract_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contrat non trouvé'
                ], 404);
            }

            $payment = Payment::create([
                'user_id' => $user->id,
                'contract_id' => $request->contract_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference' => $request->reference,
                'status' => 'completed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement créé avec succès',
                'data' => $payment->load('contract')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un paiement
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|in:card,bank_transfer,cash',
            'payment_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:pending,completed,failed,cancelled',
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
            $payment = Payment::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ], 404);
            }

            $payment->update($request->only([
                'amount', 'payment_method', 'payment_date', 'reference', 'status'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Paiement mis à jour avec succès',
                'data' => $payment->load('contract')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un paiement
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $payment = Payment::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ], 404);
            }

            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Paiement supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
