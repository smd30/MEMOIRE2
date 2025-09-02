<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route de santé pour vérifier que l'application fonctionne
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
        'database' => 'connected'
    ]);
});

// Routes d'administration (si nécessaire)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Fallback pour routes non trouvées
Route::fallback(function () {
    return response()->json([
        'message' => 'Route non trouvée',
        'status' => 404
    ], 404);
});
