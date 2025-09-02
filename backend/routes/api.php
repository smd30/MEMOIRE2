<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\SinistreController;
use App\Http\Controllers\Api\GestionnaireController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes publiques
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Routes pour devis (publiques)
Route::get('/devis/garanties', [DevisController::class, 'getGaranties']);
Route::get('/devis/compagnies', [DevisController::class, 'getCompagnies']);
Route::post('/devis/tarifs', [DevisController::class, 'calculateTarif']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Véhicules
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy']);

    // Contrats
    Route::get('/contracts', [ContractController::class, 'index']);
    Route::post('/contracts', [ContractController::class, 'store']);
    Route::get('/contracts/{contract}', [ContractController::class, 'show']);
    Route::put('/contracts/{contract}', [ContractController::class, 'update']);
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy']);

    // Sinistres
    Route::get('/sinistres', [SinistreController::class, 'index']);
    Route::post('/sinistres', [SinistreController::class, 'store']);
    Route::get('/sinistres/{sinistre}', [SinistreController::class, 'show']);
    Route::put('/sinistres/{sinistre}', [SinistreController::class, 'update']);
    Route::delete('/sinistres/{sinistre}', [SinistreController::class, 'destroy']);

    // Paiements
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::put('/payments/{payment}', [PaymentController::class, 'update']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
    Route::post('/payments/simulate', [PaymentController::class, 'simulate']);

    // Routes Gestionnaire
    Route::prefix('gestionnaires')->group(function () {
        Route::get('/dashboard', [GestionnaireController::class, 'getDashboardStats']);
        Route::get('/contracts', [GestionnaireController::class, 'getAllContracts']);
        Route::get('/contracts/{contract}', [GestionnaireController::class, 'getContract']);
        Route::put('/contracts/{contract}/cancel', [GestionnaireController::class, 'cancelContract']);
        Route::get('/sinistres', [GestionnaireController::class, 'getAllSinistres']);
        Route::get('/sinistres/{sinistre}', [GestionnaireController::class, 'getSinistre']);
        Route::put('/sinistres/{sinistre}/assign', [GestionnaireController::class, 'assignSinistre']);
        Route::put('/sinistres/{sinistre}/update-status', [GestionnaireController::class, 'updateSinistreStatus']);
        Route::get('/users', [GestionnaireController::class, 'getAllUsers']);
        Route::get('/users/{user}', [GestionnaireController::class, 'getUser']);
        Route::put('/users/{user}/status', [GestionnaireController::class, 'updateUserStatus']);
        Route::get('/notifications', [GestionnaireController::class, 'getNotifications']);
        Route::post('/notifications/mark-read', [GestionnaireController::class, 'markNotificationsAsRead']);
        Route::get('/reports/contracts', [GestionnaireController::class, 'getContractReports']);
        Route::get('/reports/sinistres', [GestionnaireController::class, 'getSinistreReports']);
        Route::get('/reports/revenue', [GestionnaireController::class, 'getRevenueReports']);
    });

    // Routes Admin
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'getDashboardStats']);
        
        // Gestion des utilisateurs
        Route::get('/users', [AdminController::class, 'getAllUsers']);
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::get('/users/{id}', [AdminController::class, 'getUserById']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::put('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus']);
        
        // Logs système
        Route::get('/system/logs', [AdminController::class, 'getSystemLogs']);
        Route::delete('/system/logs', [AdminController::class, 'clearSystemLogs']);
        
        // Statistiques système
        Route::get('/system/stats', [AdminController::class, 'getSystemStats']);
        
        // Sauvegardes
        Route::get('/system/backups', [AdminController::class, 'getBackups']);
        Route::post('/system/backups', [AdminController::class, 'createBackup']);
        Route::post('/system/backups/{backupId}/restore', [AdminController::class, 'restoreBackup']);
        
        // Configuration système
        Route::get('/system/config', [AdminController::class, 'getSystemConfig']);
        Route::put('/system/config', [AdminController::class, 'updateSystemConfig']);
        Route::post('/system/maintenance', [AdminController::class, 'toggleMaintenanceMode']);
        Route::post('/system/cache/clear', [AdminController::class, 'clearCache']);
    });
});

// Routes utilitaires
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Routes pour la Company API (webhooks)
Route::post('/webhooks/company-api', function (Request $request) {
    // Traitement des webhooks de la Company API
    return response()->json(['status' => 'received']);
});

// Routes pour Stripe (webhooks)
Route::post('/webhooks/stripe', function (Request $request) {
    // Traitement des webhooks Stripe
    return response()->json(['status' => 'received']);
});

// Fallback pour routes non trouvées
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route non trouvée'
    ], 404);
});
