<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Vérifier la structure de la table users
echo "Structure de la table users:\n";
$columns = DB::select('DESCRIBE users');
foreach($columns as $col) {
    echo $col->Field . ' - ' . $col->Type . ' - ' . $col->Null . "\n";
}


