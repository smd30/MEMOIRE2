<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\MarqueController;
use App\Http\Controllers\Api\SinistreController;
use App\Http\Controllers\Api\GestionnaireController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SouscriptionController;

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
Route::get('/devis/categories', [DevisController::class, 'getCategories']);
Route::get('/devis/garanties', [DevisController::class, 'getGaranties']);
Route::get('/devis/garanties/{compagnieId}', [DevisController::class, 'getGarantiesCompagnie']);
Route::get('/devis/informations', [DevisController::class, 'getInformationsDevis']);
Route::post('/devis/calculer', [DevisController::class, 'calculer']);
Route::get('/devis/exemple', [DevisController::class, 'exempleDevis']);

// Route temporairement publique pour les tests
Route::get('/devis', [DevisController::class, 'index']);

// Route temporairement publique pour les tests de souscription
Route::post('/souscription/test', [SouscriptionController::class, 'souscrire']);

// Route de test simple
Route::post('/test', [App\Http\Controllers\Api\TestController::class, 'test']);

// Route de test pour la souscription complète
Route::post('/test-souscription', [App\Http\Controllers\Api\TestSouscriptionController::class, 'test']);

// Route de test simple pour la souscription
Route::post('/simple-souscription', [App\Http\Controllers\Api\SimpleSouscriptionController::class, 'test']);

// Route de souscription qui fonctionne
Route::post('/working-souscription', [App\Http\Controllers\Api\WorkingSouscriptionController::class, 'souscrire']);

// Routes de test étape par étape
Route::post('/step1', [App\Http\Controllers\Api\StepByStepController::class, 'test']);
Route::post('/step2', [App\Http\Controllers\Api\StepByStepController::class, 'test2']);
Route::post('/step3', [App\Http\Controllers\Api\StepByStepController::class, 'test3']);

// Route de debug pour la souscription
Route::post('/debug-souscription', [App\Http\Controllers\Api\DebugSouscriptionController::class, 'test']);

// Route de souscription Angular
Route::post('/angular-souscription', [App\Http\Controllers\Api\AngularSouscriptionController::class, 'test']);

// Route de test simple
Route::post('/simple-test', [App\Http\Controllers\Api\SimpleTestController::class, 'test']);

// Route de souscription complète avec attestation et email
Route::post('/complete-souscription', [App\Http\Controllers\Api\CompleteSouscriptionController::class, 'souscrire']);

// Route de souscription finale (sans attestation pour l'instant)
Route::post('/final-souscription', [App\Http\Controllers\Api\FinalSouscriptionController::class, 'souscrire']);

// Route de souscription étape par étape
Route::post('/step-souscription', [App\Http\Controllers\Api\StepByStepSouscriptionController::class, 'test']);

// Route de test de souscription simple
Route::post('/test-souscription', [App\Http\Controllers\Api\TestSouscriptionController::class, 'test']);

// Route de souscription finale avec email
Route::post('/final-souscription', [App\Http\Controllers\Api\FinalSouscriptionController::class, 'souscrire']);

// Route de souscription finale qui fonctionne
Route::post('/working-final-souscription', [App\Http\Controllers\Api\WorkingFinalSouscriptionController::class, 'souscrire']);

// Routes de test d'email
Route::post('/test-email', [App\Http\Controllers\Api\TestEmailController::class, 'testEmail']);
Route::post('/test-attestation-email', [App\Http\Controllers\Api\TestEmailController::class, 'testAttestationEmail']);

// Route de test d'email simple
Route::post('/simple-email', [App\Http\Controllers\Api\SimpleEmailController::class, 'testEmail']);

// Route de souscription avec email
Route::post('/email-souscription', [App\Http\Controllers\Api\EmailSouscriptionController::class, 'souscrire']);

// Route de souscription simple qui fonctionne
Route::post('/simple-souscription', [App\Http\Controllers\Api\SimpleSouscriptionController::class, 'souscrire']);

// Route de souscription qui fonctionne vraiment
Route::post('/working-souscription', [App\Http\Controllers\Api\WorkingSouscriptionController::class, 'souscrire']);

// Route de souscription étape par étape
Route::post('/step-souscription', [App\Http\Controllers\Api\StepByStepSouscriptionController::class, 'test']);

// Route de souscription finale
Route::post('/final-souscription', [App\Http\Controllers\Api\FinalSouscriptionController::class, 'souscrire']);

// Route de souscription complète
Route::post('/complete-souscription', [App\Http\Controllers\Api\CompleteSouscriptionController::class, 'souscrire']);

// Route de souscription Angular
Route::post('/angular-souscription', [App\Http\Controllers\Api\AngularSouscriptionController::class, 'souscrire']);

// Route de souscription debug
Route::post('/debug-souscription', [App\Http\Controllers\Api\DebugSouscriptionController::class, 'souscrire']);

// Route de test simple
Route::post('/simple-test', [App\Http\Controllers\Api\SimpleTestController::class, 'test']);

// Route de souscription réelle
Route::post('/real-souscription', [App\Http\Controllers\Api\RealSouscriptionController::class, 'souscrire']);

// Route de test de souscription simple
Route::post('/test-souscription', [App\Http\Controllers\Api\TestSouscriptionController::class, 'test']);

// Route de souscription qui fonctionne
Route::post('/working-souscription', [App\Http\Controllers\Api\WorkingSouscriptionController::class, 'souscrire']);

// Route de souscription étape par étape
Route::post('/step-souscription', [App\Http\Controllers\Api\StepByStepSouscriptionController::class, 'test']);

// Route de souscription finale
Route::post('/final-souscription', [App\Http\Controllers\Api\FinalSouscriptionController::class, 'souscrire']);

// Route de souscription complète
Route::post('/complete-souscription', [App\Http\Controllers\Api\CompleteSouscriptionController::class, 'souscrire']);

// Route de souscription Angular
Route::post('/angular-souscription', [App\Http\Controllers\Api\AngularSouscriptionController::class, 'souscrire']);

// Route de souscription debug
Route::post('/debug-souscription', [App\Http\Controllers\Api\DebugSouscriptionController::class, 'souscrire']);

// Route de souscription finale qui fonctionne
Route::post('/working-final-souscription', [App\Http\Controllers\Api\WorkingFinalSouscriptionController::class, 'souscrire']);

// Route de souscription avec email
Route::post('/email-souscription', [App\Http\Controllers\Api\EmailSouscriptionController::class, 'souscrire']);

// Route de souscription simple
Route::post('/simple-souscription', [App\Http\Controllers\Api\SimpleSouscriptionController::class, 'souscrire']);

// Route de souscription qui fonctionne
Route::post('/working-souscription', [App\Http\Controllers\Api\WorkingSouscriptionController::class, 'souscrire']);

// Route de souscription étape par étape
Route::post('/step-souscription', [App\Http\Controllers\Api\StepByStepSouscriptionController::class, 'test']);

// Route de souscription finale
Route::post('/final-souscription', [App\Http\Controllers\Api\FinalSouscriptionController::class, 'souscrire']);

// Route de souscription complète
Route::post('/complete-souscription', [App\Http\Controllers\Api\CompleteSouscriptionController::class, 'souscrire']);

// Route de souscription Angular
Route::post('/angular-souscription', [App\Http\Controllers\Api\AngularSouscriptionController::class, 'souscrire']);

// Route de souscription debug
Route::post('/debug-souscription', [App\Http\Controllers\Api\DebugSouscriptionController::class, 'souscrire']);

// Route de test simple
Route::post('/simple-test', [App\Http\Controllers\Api\SimpleTestController::class, 'test']);

// Route de test de souscription
Route::post('/test-souscription', [App\Http\Controllers\Api\TestSouscriptionController::class, 'test']);

// Route de test des modèles
Route::post('/test-email', [App\Http\Controllers\Api\TestEmailController::class, 'test']);

// Route de test simple de création
Route::post('/simple-email', [App\Http\Controllers\Api\SimpleEmailController::class, 'test']);

// Route de souscription avec email (sans transaction)
Route::post('/email-souscription', [App\Http\Controllers\Api\EmailSouscriptionController::class, 'souscrire']);

// Route de souscription réelle
Route::post('/real-souscription', [App\Http\Controllers\Api\RealSouscriptionController::class, 'souscrire']);

// Route de diagnostic
Route::get('/diagnostic', [App\Http\Controllers\Api\DiagnosticController::class, 'test']);

// Route de test étape par étape
Route::post('/step-by-step', [App\Http\Controllers\Api\StepByStepController::class, 'test']);

// Route de test utilisateur
Route::post('/test-user', [App\Http\Controllers\Api\TestUserController::class, 'test']);

// Route de souscription finale qui fonctionne
Route::post('/final-working', [App\Http\Controllers\Api\FinalWorkingController::class, 'souscrire']);

// Route de test véhicule
Route::post('/test-vehicule', [App\Http\Controllers\Api\TestVehiculeController::class, 'test']);

// Route de souscription avec PDF et email
Route::post('/souscription-with-pdf', [App\Http\Controllers\Api\SouscriptionWithPDFController::class, 'souscrire']);

// Route de souscription avec PDF et email CORRIGÉE (envoi au propriétaire du véhicule)
Route::post('/souscription-correct', [App\Http\Controllers\Api\SouscriptionWithPDFCorrectController::class, 'souscrire']);

// Route de souscription SIMPLE (envoi au propriétaire du véhicule)
Route::post('/souscription-proprietaire', [App\Http\Controllers\Api\SouscriptionProprietaireController::class, 'souscrire']);

// Route de souscription SIMPLE FINALE (envoi au propriétaire du véhicule)
Route::post('/souscription-simple-proprietaire', [App\Http\Controllers\Api\SouscriptionSimpleProprietaireController::class, 'souscrire']);

// Route de souscription FINALE (envoi au propriétaire du véhicule)
Route::post('/souscription-final', [App\Http\Controllers\Api\SouscriptionFinalController::class, 'souscrire']);

// Route de souscription EMAIL PROPRIÉTAIRE (envoi au propriétaire du véhicule)
Route::post('/souscription-email-proprietaire', [App\Http\Controllers\Api\SouscriptionEmailProprietaireController::class, 'souscrire']);

// Route de souscription EMAIL PROPRIÉTAIRE SIMPLE (envoi au propriétaire du véhicule)
Route::post('/souscription-email-proprietaire-simple', [App\Http\Controllers\Api\SouscriptionEmailProprietaireController::class, 'souscrire']);

// Route de souscription FINALE QUI FONCTIONNE (envoi au propriétaire du véhicule)
Route::post('/souscription-final-working', [App\Http\Controllers\Api\SouscriptionFinalWorkingController::class, 'souscrire']);

// Route de souscription ULTRA SIMPLE QUI FONCTIONNE (envoi au propriétaire du véhicule)
Route::post('/souscription-ultra-simple', [App\Http\Controllers\Api\SouscriptionUltraSimpleController::class, 'souscrire']);

// Route de souscription PRINCIPALE QUI FONCTIONNE (envoi au propriétaire du véhicule)
Route::post('/souscription', [App\Http\Controllers\Api\SouscriptionController::class, 'souscrire']);
Route::post('/test-souscription', [App\Http\Controllers\Api\TestSouscriptionController::class, 'testSouscription']);
Route::post('/test-qr-code', [App\Http\Controllers\Api\SimpleTestController::class, 'testQRCode']);

// Routes de test
Route::post('/test-models', [App\Http\Controllers\Api\TestSouscriptionController::class, 'testModels']);

// Routes protégées par authentification
// Routes pour les marques et modèles (publiques)
Route::get('/marques', [MarqueController::class, 'index']);
Route::get('/marques/search', [MarqueController::class, 'search']);
Route::get('/marques/{marqueId}/modeles', [MarqueController::class, 'getModeles']);
Route::get('/marques-with-modeles', [MarqueController::class, 'getAllWithModeles']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    // Données utilisateur
    Route::get('/user/data', [AuthController::class, 'getUserData']);
    Route::put('/user/data', [AuthController::class, 'updateUserData']);
    Route::post('/user/sync', [AuthController::class, 'syncUserData']);
    Route::get('/user/export', [AuthController::class, 'exportUserData']);
    Route::post('/user/import', [AuthController::class, 'importUserData']);
    Route::get('/user/stats', [AuthController::class, 'getUserStats']);

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

    // Devis (sauf index qui est public pour les tests)
    Route::get('/devis/create', [DevisController::class, 'create']);
    Route::post('/devis', [DevisController::class, 'store']);
    Route::post('/devis/souscrire', [DevisController::class, 'souscrire']);
    Route::get('/devis/{devis}', [DevisController::class, 'show']);
    Route::post('/devis/{devis}/accepter', [DevisController::class, 'accepter']);
    Route::post('/devis/{devis}/rejeter', [DevisController::class, 'rejeter']);
    Route::delete('/devis/{devis}', [DevisController::class, 'destroy']);

    // Souscription
    Route::post('/souscription/souscrire', [SouscriptionController::class, 'souscrire']);

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
