<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    public function test(Request $request)
    {
        $results = [];
        
        // Test 1: Vérifier les modèles
        try {
            $compagnie = \App\Models\Compagnie::find(1);
            $results['compagnie'] = $compagnie ? 'OK' : 'NOT_FOUND';
        } catch (\Exception $e) {
            $results['compagnie'] = 'ERROR: ' . $e->getMessage();
        }
        
        // Test 2: Vérifier la structure de la table users
        try {
            $user = new \App\Models\User();
            $results['user_table'] = 'OK';
        } catch (\Exception $e) {
            $results['user_table'] = 'ERROR: ' . $e->getMessage();
        }
        
        // Test 3: Vérifier la structure de la table vehicules
        try {
            $vehicule = new \App\Models\Vehicule();
            $results['vehicule_table'] = 'OK';
        } catch (\Exception $e) {
            $results['vehicule_table'] = 'ERROR: ' . $e->getMessage();
        }
        
        // Test 4: Vérifier la structure de la table contrats
        try {
            $contrat = new \App\Models\Contrat();
            $results['contrat_table'] = 'OK';
        } catch (\Exception $e) {
            $results['contrat_table'] = 'ERROR: ' . $e->getMessage();
        }
        
        // Test 5: Vérifier Carbon
        try {
            $date = \Carbon\Carbon::now();
            $results['carbon'] = 'OK';
        } catch (\Exception $e) {
            $results['carbon'] = 'ERROR: ' . $e->getMessage();
        }
        
        // Test 6: Vérifier Mail
        try {
            $mailConfig = config('mail.from.address');
            $results['mail_config'] = $mailConfig ? 'OK' : 'NOT_CONFIGURED';
        } catch (\Exception $e) {
            $results['mail_config'] = 'ERROR: ' . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'diagnostic' => $results
        ]);
    }
}
