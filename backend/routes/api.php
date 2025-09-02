<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SinistreController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\GestionnaireController;
use App\Http\Controllers\Api\AdminController;

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

// Routes publiques pour les devis
Route::get('/devis/garanties', [DevisController::class, 'getGaranties']);
Route::get('/devis/compagnies', [DevisController::class, 'getCompagnies']);
Route::get('/devis/tarifs', [DevisController::class, 'getTarifs']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentification
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Devis (calcul public, création avec authentification)
    Route::post('/devis/calculate', [DevisController::class, 'calculate']);
    Route::post('/devis', [DevisController::class, 'store']);
    Route::get('/devis', [DevisController::class, 'index']);
    Route::get('/devis/{devis}', [DevisController::class, 'show']);

    // Véhicules
    Route::apiResource('vehicles', VehicleController::class);

    // Contrats
    Route::apiResource('contracts', ContractController::class);
    Route::post('/contracts/{contract}/renew', [ContractController::class, 'renew']);
    Route::get('/contracts/{contract}/attestation', [ContractController::class, 'downloadAttestation']);

    // Paiements
    Route::apiResource('payments', PaymentController::class);
    Route::post('/payments/simulate', [PaymentController::class, 'simulate']);

    // Sinistres
    Route::apiResource('sinistres', SinistreController::class);
    Route::get('/sinistres/stats', [SinistreController::class, 'getStats']);

    // Notifications
    Route::apiResource('notifications', NotificationController::class);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);

    // Routes pour gestionnaires
    Route::prefix('gestionnaires')->group(function () {
        // Dashboard
        Route::get('/dashboard', [GestionnaireController::class, 'getDashboardStats']);
        
        // Contrats
        Route::get('/contracts', [GestionnaireController::class, 'getAllContracts']);
        Route::get('/contracts/{contract}', [GestionnaireController::class, 'getContractById']);
        Route::post('/contracts/{contract}/cancel', [GestionnaireController::class, 'cancelContract']);
        
        // Sinistres
        Route::get('/sinistres', [GestionnaireController::class, 'getAllSinistres']);
        Route::get('/sinistres/{sinistre}', [GestionnaireController::class, 'getSinistreById']);
        Route::put('/sinistres/{sinistre}', [GestionnaireController::class, 'updateSinistre']);
        Route::post('/sinistres/{sinistre}/assign-expert', [GestionnaireController::class, 'assignExpert']);
        
        // Utilisateurs
        Route::get('/users', [GestionnaireController::class, 'getAllUsers']);
        Route::get('/users/{user}', [GestionnaireController::class, 'getUserById']);
        
        // Notifications et rapports
        Route::post('/notifications/send', [GestionnaireController::class, 'sendNotification']);
        Route::get('/reports/{type}', [GestionnaireController::class, 'generateReport']);
    });

    // Routes pour administrateurs
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'getDashboardStats']);
        
        // Utilisateurs
        Route::get('/users', [AdminController::class, 'getAllUsers']);
        Route::get('/users/{user}', [AdminController::class, 'getUserById']);
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::put('/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
        Route::put('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);
        
        // Rôles et permissions
        Route::get('/roles', [AdminController::class, 'getRoles']);
        Route::get('/permissions', [AdminController::class, 'getPermissions']);
        Route::post('/users/{user}/assign-role', [AdminController::class, 'assignRole']);
        
        // Logs système
        Route::get('/logs', [AdminController::class, 'getSystemLogs']);
        Route::delete('/logs', [AdminController::class, 'clearLogs']);
        
        // Statistiques système
        Route::get('/system-stats', [AdminController::class, 'getSystemStats']);
        
        // Sauvegardes
        Route::post('/backup', [AdminController::class, 'createBackup']);
        Route::get('/backups', [AdminController::class, 'getBackups']);
        Route::post('/backups/{backupId}/restore', [AdminController::class, 'restoreBackup']);
        
        // Configuration
        Route::get('/config', [AdminController::class, 'getSystemConfig']);
        Route::put('/config', [AdminController::class, 'updateSystemConfig']);
        
        // Maintenance
        Route::post('/maintenance', [AdminController::class, 'putSystemInMaintenance']);
        Route::post('/cache/clear', [AdminController::class, 'clearCache']);
        
        // Rapports
        Route::get('/reports/{type}', [AdminController::class, 'generateReport']);
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
