# Dossier Data

Ce dossier contient les fichiers de données nécessaires au projet.

## 📊 Grille Tarifaire

### Import automatique
Placez votre fichier Excel de grille tarifaire dans ce dossier et utilisez la commande :

```bash
docker-compose exec backend php artisan tarifs:import /data/votre_fichier.xlsx
```

### Format attendu
Le fichier Excel doit contenir les colonnes suivantes :

| Colonne | Description | Exemple |
|---------|-------------|---------|
| categorie | Catégorie de véhicule | Citadine, SUV, Berline |
| sous_categorie | Sous-catégorie | Compacte, Familiale, Sport |
| puissance_fiscale | Puissance fiscale | 4, 6, 8, 10 |
| tarif_mensuel | Tarif de base par mois | 45.50, 67.80 |
| coefficient_vol | Coefficient vol | 1.2, 1.5 |
| coefficient_incendie | Coefficient incendie | 0.8, 1.1 |
| coefficient_bris | Coefficient bris de glace | 1.0, 1.3 |
| coefficient_defense | Coefficient défense | 1.1, 1.4 |
| conditions | Conditions particulières | "Véhicule < 5 ans", "Garage fermé" |

### Fichier d'exemple
Si vous n'avez pas de fichier Excel, le seeder créera automatiquement des tarifs d'exemple.

## 📁 Structure des fichiers
```
data/
├── README.md                    # Ce fichier
├── grille_tarifaire.xlsx       # Votre fichier Excel (optionnel)
└── attestations/               # Dossier pour les attestations générées
```

## 🔄 Mise à jour des tarifs
Pour mettre à jour les tarifs existants :

```bash
# Supprimer tous les tarifs et réimporter
docker-compose exec backend php artisan tarifs:clear
docker-compose exec backend php artisan tarifs:import /data/nouveau_fichier.xlsx

# Ou mettre à jour via l'interface admin
# Aller sur http://localhost:4200/admin/tarifs
```

