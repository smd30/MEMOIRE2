# 🔄 Système de Renouvellement des Contrats - MEMOIRE2

## 📋 Vue d'ensemble

Le système de renouvellement des contrats permet aux clients de renouveler automatiquement leurs contrats d'assurance automobile avant leur expiration. Il inclut :

- ✅ **Détection automatique** des contrats éligibles (30 jours avant expiration)
- ✅ **Calcul intelligent** des primes de renouvellement
- ✅ **Gestion des garanties** et accessoires
- ✅ **Workflow d'approbation** pour les compagnies
- ✅ **Suivi complet** des renouvellements
- ✅ **Statistiques détaillées** et reporting

## 🏗️ Architecture du Système

### **Modèles**

#### **ContractRenewal**
```php
- id: Identifiant unique
- contrat_id: Référence au contrat original
- user_id: Propriétaire du contrat
- vehicule_id: Véhicule concerné
- compagnie_id: Compagnie d'assurance
- numero_police_nouveau: Nouveau numéro de police
- numero_attestation_nouveau: Nouvelle attestation
- cle_securite_nouveau: Nouvelle clé de sécurité
- date_debut_nouveau: Date de début du nouveau contrat
- date_fin_nouveau: Date de fin du nouveau contrat
- periode_police: Durée (1, 3, 6, 12 mois)
- garanties_selectionnees: Garanties choisies
- prime_rc: Prime responsabilité civile
- garanties_optionnelles: Prime garanties optionnelles
- accessoires_police: Prime accessoires
- prime_nette: Prime nette
- taxes_tuca: Taxes TUCA (10%)
- prime_ttc: Prime TTC finale
- statut: en_attente, approuve, rejete, renouvele, annule
- date_demande: Date de la demande
- date_renouvellement: Date d'approbation
- motif_renouvellement: Motif du renouvellement
- observations: Observations
- prime_precedente: Ancienne prime
- evolution_prime: Évolution en montant
- pourcentage_evolution: Évolution en pourcentage
```

### **Contrôleur**

#### **ContractRenewalController**
```php
- index(): Liste des renouvellements avec filtres
- store(): Créer une demande de renouvelement
- show(): Détails d'un renouvellement
- update(): Modifier un renouvellement en attente
- destroy(): Supprimer un renouvellement en attente
- approve(): Approuver un renouvellement (compagnies)
- reject(): Rejeter un renouvellement (compagnies)
- getEligibleContracts(): Contrats éligibles au renouvellement
- calculateRenewalPremium(): Calculer la prime de renouvellement
- getStatistics(): Statistiques des renouvellements
```

### **Service**

#### **ContractRenewalService**
```php
- peutEtreRenouvele(): Vérifier l'éligibilité
- peutEtreModifie(): Vérifier si modifiable
- peutEtreSupprime(): Vérifier si supprimable
- calculerDatesRenouvellement(): Calculer les dates
- calculerPrimeRenouvellement(): Calculer la prime complète
- calculerPrimeRC(): Prime RC selon puissance fiscale
- calculerGarantiesOptionnelles(): Prime garanties
- calculerAccessoires(): Prime accessoires
- calculerEvolutionPrime(): Évolution vs ancienne prime
- getContratsEligibles(): Contrats éligibles
- creerNouveauContrat(): Créer le nouveau contrat
- getStatistiques(): Statistiques détaillées
```

## 🛣️ Routes API

### **Endpoints Principaux**

```php
// Renouvellement des contrats
Route::prefix('contract-renewals')->group(function () {
    Route::get('/', [ContractRenewalController::class, 'index']);
    Route::post('/', [ContractRenewalController::class, 'store']);
    Route::get('/eligible-contracts', [ContractRenewalController::class, 'getEligibleContracts']);
    Route::post('/calculate-premium', [ContractRenewalController::class, 'calculateRenewalPremium']);
    Route::get('/statistics', [ContractRenewalController::class, 'getStatistics']);
    Route::get('/{id}', [ContractRenewalController::class, 'show']);
    Route::put('/{id}', [ContractRenewalController::class, 'update']);
    Route::delete('/{id}', [ContractRenewalController::class, 'destroy']);
    Route::post('/{id}/approve', [ContractRenewalController::class, 'approve']);
    Route::post('/{id}/reject', [ContractRenewalController::class, 'reject']);
});
```

## 🔄 Flux de Renouvellement

### **1. Détection des Contrats Éligibles**
```php
// Un contrat est éligible si :
- Statut = 'actif'
- Jours restants <= 30 et > 0
- Pas de renouvellement en cours
```

### **2. Demande de Renouvellement**
```php
POST /api/contract-renewals
{
    "contrat_id": 1,
    "periode_police": 12,
    "garanties_selectionnees": [1, 2, 3],
    "motif_renouvellement": "expiration_normale",
    "observations": "Renouvellement automatique"
}
```

### **3. Calcul de la Prime**
```php
POST /api/contract-renewals/calculate-premium
{
    "contrat_id": 1,
    "periode_police": 12,
    "garanties_selectionnees": [1, 2, 3]
}

// Réponse :
{
    "prime_rc": 250000,
    "garanties_optionnelles": 50000,
    "accessoires_police": 10000,
    "prime_nette": 310000,
    "taxes_tuca": 31000,
    "prime_ttc": 341000,
    "prime_precedente": 320000,
    "evolution_prime": 21000,
    "pourcentage_evolution": 6.56,
    "type_evolution": "augmentation"
}
```

### **4. Approbation par la Compagnie**
```php
POST /api/contract-renewals/{id}/approve
// Crée automatiquement le nouveau contrat
// Met à jour le statut de l'ancien contrat
```

### **5. Rejet par la Compagnie**
```php
POST /api/contract-renewals/{id}/reject
{
    "observations": "Motif du rejet"
}
```

## 💰 Calcul des Primes

### **Prime RC (Responsabilité Civile)**
```php
// Tarifs selon la puissance fiscale (par mois)
$tarifsRC = [
    1 => 15000,   // 1 CV
    2 => 18000,   // 2 CV
    3 => 22000,   // 3 CV
    4 => 25000,   // 4 CV
    5 => 28000,   // 5 CV
    6 => 32000,   // 6 CV
    7 => 35000,   // 7 CV
    8 => 38000,   // 8 CV
    9 => 42000,   // 9 CV
    10 => 45000,  // 10 CV
    11 => 48000,  // 11 CV
    12 => 52000,  // 12 CV
    13 => 55000,  // 13 CV
    14 => 58000,  // 14 CV
    15 => 62000,  // 15 CV
    16 => 65000,  // 16 CV
    17 => 68000,  // 17 CV
];

// Coefficient selon la période
$coefficientPeriode = [
    1 => 1.0,    // 1 mois
    3 => 2.8,    // 3 mois
    6 => 5.5,    // 6 mois
    12 => 10.0   // 12 mois
];
```

### **Garanties Optionnelles**
```php
// Calcul basé sur :
- Tarif de base de la garantie
- Coefficient selon la valeur du véhicule
- Coefficient selon l'âge du véhicule
- Coefficient selon la période
```

### **Accessoires**
```php
// Accessoires fixes selon la période
// Coefficient selon la période (1, 3, 6, 12 mois)
```

### **Taxes TUCA**
```php
// 10% de la prime nette
$taxesTUCA = $primeNette * 0.10;
```

## 📊 Statuts et Transitions

### **Statuts Possibles**
```php
'en_attente' => 'En attente d\'approbation'
'approuve' => 'Approuvé par la compagnie'
'rejete' => 'Rejeté par la compagnie'
'renouvele' => 'Renouvelé (nouveau contrat créé)'
'annule' => 'Annulé par le client'
```

### **Transitions Autorisées**
```php
en_attente → approuve (par compagnie)
en_attente → rejete (par compagnie)
en_attente → annule (par client)
approuve → renouvele (automatique lors de la création du nouveau contrat)
```

## 🎯 Motifs de Renouvellement

```php
'expiration_normale' => 'Expiration normale'
'changement_garanties' => 'Changement de garanties'
'changement_vehicule' => 'Changement de véhicule'
'changement_compagnie' => 'Changement de compagnie'
'negociation_prime' => 'Négociation de prime'
'autre' => 'Autre'
```

## 📈 Statistiques Disponibles

```php
{
    "total_renouvellements": 25,
    "en_attente": 3,
    "approuves": 15,
    "renouveles": 12,
    "rejetes": 2,
    "evolution_moyenne_prime": 5.2,
    "taux_approbation": 88.0
}
```

## 🔐 Sécurité et Permissions

### **Authentification**
- Toutes les routes nécessitent un Bearer Token
- Vérification de la propriété des contrats
- Permissions spécifiques pour les compagnies

### **Validation**
- Validation stricte des données d'entrée
- Vérification de l'éligibilité des contrats
- Contrôle des transitions de statut

## 🧪 Tests et Validation

### **Tests Unitaires**
```bash
php test-renewal-system.php
```

### **Tests API**
```bash
php test-renewal-api.php
```

### **Tests d'Intégration**
- Test du flux complet de renouvellement
- Test des calculs de primes
- Test des transitions de statut

## 🚀 Déploiement

### **Migration de Base de Données**
```bash
php artisan migrate
```

### **Vérification**
```bash
php artisan route:list | grep contract-renewals
```

## 📱 Intégration Frontend

### **Services Angular**
```typescript
// contract-renewal.service.ts
- getRenewals()
- createRenewal()
- getEligibleContracts()
- calculatePremium()
- approveRenewal()
- rejectRenewal()
- getStatistics()
```

### **Composants Angular**
```typescript
// contract-renewal-list.component.ts
// contract-renewal-form.component.ts
// contract-renewal-details.component.ts
// contract-renewal-statistics.component.ts
```

## 🎉 Avantages du Système

### **Pour les Clients**
- ✅ Renouvellement automatique et simplifié
- ✅ Calcul transparent des primes
- ✅ Suivi en temps réel des demandes
- ✅ Historique complet des renouvellements

### **Pour les Compagnies**
- ✅ Workflow d'approbation structuré
- ✅ Calculs automatiques des primes
- ✅ Statistiques détaillées
- ✅ Gestion centralisée des renouvellements

### **Pour le Système**
- ✅ Architecture modulaire et extensible
- ✅ Sécurité renforcée
- ✅ Performance optimisée
- ✅ Maintenance facilitée

---

**🎯 Le système de renouvellement des contrats est maintenant opérationnel et prêt pour la production !**
