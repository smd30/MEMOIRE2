<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * Endpoint de santé pour Render
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now(),
            'service' => 'MEMOIRE2 Backend',
            'version' => '1.0.0',
            'environment' => app()->environment(),
        ]);
    }

    /**
     * Endpoint de vérification de la base de données
     */
    public function database(): JsonResponse
    {
        try {
            \DB::connection()->getPdo();
            return response()->json([
                'status' => 'connected',
                'database' => 'MySQL',
                'connection' => 'OK'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'database' => 'MySQL',
                'connection' => 'FAILED',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
