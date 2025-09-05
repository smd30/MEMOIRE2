<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class SimpleTestController extends Controller
{
    public function test(Request $request)
    {
        try {
            // Test très simple - juste retourner les données reçues
            return response()->json([
                'success' => true,
                'message' => 'Test simple réussi',
                'data' => $request->all()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}