# 📋 Documentation du Système de Calcul des Devis d'Assurance Automobile

## 🎯 **Vue d'ensemble**

Ce système permet de calculer automatiquement les devis d'assurance automobile au Sénégal selon le barème officiel DIOTALI. Il prend en compte les catégories de véhicules, les garanties optionnelles et applique les taxes en vigueur.

## 🔧 **Architecture**

### Services
- **`DevisService`** : Service principal pour le calcul des devis
- **`DevisController`** : Contrôleur API pour gérer les requêtes

### Tarifs Intégrés
- **Prime RC** : Selon le barème DIOTALI officiel
- **Garanties optionnelles** : Tarifs fixes par garantie
- **Accessoires de police** : Frais de gestion, carte brune CEDEAO, vignette
- **Taxes TUCA** : 19% sur la prime nette

## 📊 **Catégories de Véhicules Supportées**

### C1 - Véhicules de tourisme (voitures particulières)
- **2 CV** → 37 601 FCFA
- **3 à 6 CV** → 45 181 FCFA
- **7 à 10 CV** → 51 078 FCFA
- **11 à 14 CV** → 65 677 FCFA
- **15 à 23 CV** → 86 456 FCFA
- **24 CV et +** → 104 143 FCFA

### C2 - Véhicules de commerce (utilitaires)
- Tarification selon puissance, tonnage et carburant
- Exemple : 7-10 CV essence, ≤ 3T5 → 78 974 FCFA
- Exemple : 7-10 CV essence, > 3T5 → 130 415 FCFA

### C3 - Transports publics de marchandises
- Tarification selon puissance, tonnage et carburant
- Exemple : 11-14 CV essence, ≤ 3T5 → 222 270 FCFA
- Exemple : 11-14 CV essence, > 3T5 → 224 650 FCFA

### C5 - Véhicules motorisés 2 ou 3 roues
- **Cyclomoteurs** → 18 780 FCFA
- **Scooters ≤ 125 cm³** → 29 448 FCFA
- **Motos > 125 cm³** → 34 021 FCFA
- **Side-cars** → 40 880 FCFA

## 🛡️ **Garanties Optionnelles**

| Garantie | Tarif (FCFA) |
|----------|--------------|
| Vol | 15 000 |
| Incendie | 12 000 |
| Bris de glace | 10 000 |
| Défense et recours | 5 000 |
| Individuelle conducteur | 8 000 |
| Dommages tous accidents (DTA) | 25 000 |

## 💰 **Accessoires de Police**

| Accessoire | Tarif (FCFA) |
|------------|--------------|
| Frais de gestion | 5 000 |
| Carte brune CEDEAO | 3 000 |
| Vignette | 2 000 |
| **Total** | **10 000** |

## 🧮 **Formule de Calcul**

```
Prime Nette = Prime RC + Garanties Optionnelles + Accessoires de Police
Taxes TUCA = Prime Nette × 19%
Prime TTC = Prime Nette + Taxes TUCA
```

## 🔌 **API Endpoints**

### 1. Obtenir les catégories
```http
GET /api/devis/categories
```

**Réponse :**
```json
{
  "success": true,
  "data": {
    "C1": "Véhicules de tourisme (voitures particulières)",
    "C2": "Véhicules de commerce (utilitaires)",
    "C3": "Transports publics de marchandises",
    "C5": "Véhicules motorisés 2 ou 3 roues"
  }
}
```

### 2. Obtenir les garanties
```http
GET /api/devis/garanties
```

**Réponse :**
```json
{
  "success": true,
  "data": {
    "vol": "Vol",
    "incendie": "Incendie",
    "bris_glace": "Bris de glace",
    "defense_recours": "Défense et recours",
    "individuelle_conducteur": "Individuelle conducteur",
    "dommages_tous_accidents": "Dommages tous accidents (DTA)"
  }
}
```

### 3. Calculer un devis
```http
POST /api/devis/calculer
```

**Corps de la requête :**
```json
{
  "categorie": "C1",
  "caracteristiques": {
    "puissance": 7,
    "tonnage": 0,
    "carburant": "essence",
    "type": null
  },
  "garanties": {
    "vol": true,
    "incendie": true,
    "bris_glace": true,
    "defense_recours": true,
    "individuelle_conducteur": false,
    "dommages_tous_accidents": false
  },
  "accessoires": {}
}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Devis calculé avec succès",
  "data": {
    "prime_rc": 51078,
    "garanties_optionnelles": 42000,
    "accessoires_police": 10000,
    "prime_nette": 103078,
    "taxes_tuca": 19585,
    "prime_ttc": 122663,
    "details": {
      "categorie": "C1",
      "caracteristiques": {
        "puissance": 7
      },
      "garanties_choisies": ["vol", "incendie", "bris_glace", "defense_recours"],
      "taux_taxe": "19%"
    }
  }
}
```

### 4. Exemple de devis
```http
GET /api/devis/exemple
```

## 📝 **Exemples d'Utilisation**

### Exemple 1 : Voiture particulière 7 CV
```json
{
  "categorie": "C1",
  "caracteristiques": {
    "puissance": 7
  },
  "garanties": {
    "vol": true,
    "incendie": true,
    "bris_glace": true,
    "defense_recours": true
  }
}
```

**Résultat :** 122 663 FCFA TTC

### Exemple 2 : Véhicule de commerce diesel 5 CV, 4 tonnes
```json
{
  "categorie": "C2",
  "caracteristiques": {
    "puissance": 5,
    "tonnage": 4,
    "carburant": "diesel"
  },
  "garanties": {
    "vol": true,
    "incendie": true,
    "defense_recours": true,
    "individuelle_conducteur": true
  }
}
```

**Résultat :** 214 694 FCFA TTC

### Exemple 3 : Transport de marchandises essence 12 CV, 2 tonnes
```json
{
  "categorie": "C3",
  "caracteristiques": {
    "puissance": 12,
    "tonnage": 2,
    "carburant": "essence"
  },
  "garanties": {
    "vol": true,
    "incendie": true,
    "bris_glace": true,
    "defense_recours": true,
    "individuelle_conducteur": true,
    "dommages_tous_accidents": true
  }
}
```

**Résultat :** 365 651 FCFA TTC

### Exemple 4 : Moto > 125 cm³
```json
{
  "categorie": "C5",
  "caracteristiques": {
    "type": "motos_125+"
  },
  "garanties": {
    "vol": true,
    "incendie": true
  }
}
```

**Résultat :** 84 515 FCFA TTC

## ⚠️ **Notes Importantes**

1. **Catégorie 4 exclue** : Les véhicules de transport public de voyageurs ne sont pas gérés
2. **Validation** : Toutes les données sont validées côté serveur
3. **Taxes** : Le taux TUCA de 19% est appliqué automatiquement
4. **Accessoires** : Les accessoires de police sont inclus par défaut
5. **Garanties** : Seules les garanties sélectionnées sont facturées

## 🔧 **Installation et Configuration**

1. **Dépendances** : Le service utilise Laravel et PHP 8+
2. **Routes** : Les routes sont définies dans `routes/api.php`
3. **Service** : Le service principal est dans `app/Services/DevisService.php`
4. **Contrôleur** : Le contrôleur est dans `app/Http/Controllers/Api/DevisController.php`

## 🧪 **Tests**

Des scripts de test sont disponibles :
- `test-devis.php` : Test du service de calcul
- `test-api-devis.php` : Test des endpoints API

## 📞 **Support**

Pour toute question ou problème :
1. Vérifiez la documentation
2. Consultez les logs d'erreur
3. Testez avec les exemples fournis
4. Contactez l'équipe de développement

---

**✅ Système opérationnel et conforme au barème DIOTALI officiel du Sénégal**

