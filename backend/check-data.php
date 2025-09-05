<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Compagnie;
use App\Models\User;
use App\Models\Vehicule;

echo "🔍 Vérification des données dans la base de données...\n\n";

// Vérifier les compagnies
echo "📋 Compagnies:\n";
$compagnies = Compagnie::all();
if ($compagnies->count() > 0) {
    foreach ($compagnies as $compagnie) {
        echo "- ID: {$compagnie->id}, Nom: {$compagnie->nom}\n";
    }
} else {
    echo "❌ Aucune compagnie trouvée\n";
}

echo "\n👥 Utilisateurs:\n";
$users = User::all();
if ($users->count() > 0) {
    foreach ($users as $user) {
        echo "- ID: {$user->id}, Nom: {$user->nom} {$user->prenom}, Email: {$user->email}\n";
    }
} else {
    echo "❌ Aucun utilisateur trouvé\n";
}

echo "\n🚗 Véhicules:\n";
$vehicules = Vehicule::all();
if ($vehicules->count() > 0) {
    foreach ($vehicules as $vehicule) {
        echo "- ID: {$vehicule->id}, Marque: {$vehicule->marque}, Modèle: {$vehicule->modele}, Immat: {$vehicule->immatriculation}\n";
    }
} else {
    echo "❌ Aucun véhicule trouvé\n";
}

echo "\n✅ Vérification terminée\n";
