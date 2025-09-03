# Gestion des Sessions Utilisateurs - KDS Assurance

## 📋 Vue d'ensemble

Ce document décrit la gestion complète des sessions utilisateurs et le stockage des données par utilisateur dans l'application KDS Assurance. Le système implémente une gestion sécurisée des sessions avec surveillance de l'activité, renouvellement automatique des tokens et stockage local des préférences.

## 🔐 Architecture des Sessions

### Structure de Session
```typescript
interface UserSession {
  user: User;
  token: string;
  expiresAt: number;      // Timestamp d'expiration
  lastActivity: number;    // Timestamp de dernière activité
}
```

### Gestion des Tokens
- **Type** : JWT (JSON Web Tokens)
- **Durée de vie** : 24 heures
- **Renouvellement** : Automatique avant expiration
- **Stockage** : localStorage sécurisé

## 🎯 Fonctionnalités Implémentées

### ✅ Authentification et Sessions

1. **Connexion sécurisée**
   - Validation des identifiants
   - Génération de token JWT
   - Création de session utilisateur
   - Redirection selon le rôle

2. **Gestion de session**
   - Session de 24 heures
   - Surveillance de l'activité utilisateur
   - Expiration automatique
   - Déconnexion forcée en cas d'inactivité

3. **Renouvellement automatique**
   - Détection de l'expiration proche
   - Renouvellement transparent
   - Gestion des erreurs de renouvellement

### ✅ Stockage des Données Utilisateur

1. **Préférences utilisateur**
   ```typescript
   interface UserPreferences {
     theme: 'light' | 'dark';
     language: 'fr' | 'en';
     notifications: {
       email: boolean;
       push: boolean;
       sms: boolean;
     };
     dashboard: {
       showStats: boolean;
       showRecentActivity: boolean;
       layout: 'grid' | 'list';
     };
   }
   ```

2. **Historique des activités**
   ```typescript
   interface UserActivity {
     id: number;
     action: string;
     description: string;
     timestamp: string;
     ip_address?: string;
     user_agent?: string;
   }
   ```

3. **Synchronisation**
   - Sauvegarde locale des données
   - Synchronisation avec le serveur
   - Gestion des conflits
   - Export/Import des données

## 🛡️ Sécurité

### Mesures de Protection

1. **Authentification**
   - Tokens JWT sécurisés
   - Validation côté serveur
   - Protection contre les attaques CSRF

2. **Surveillance d'activité**
   - Détection des événements utilisateur
   - Mise à jour automatique de lastActivity
   - Déconnexion en cas d'inactivité

3. **Stockage sécurisé**
   - Chiffrement des données sensibles
   - Nettoyage automatique des sessions expirées
   - Protection contre les attaques XSS

### Intercepteur HTTP
```typescript
@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  // Ajout automatique du token aux requêtes
  // Gestion des erreurs 401
  // Renouvellement automatique des tokens
}
```

## 📊 Données Stockées par Utilisateur

### Données de Session
- **Token d'authentification** : JWT avec expiration
- **Informations utilisateur** : Profil complet
- **Métadonnées de session** : Timestamps et activité

### Préférences Personnalisées
- **Interface** : Thème, langue, layout
- **Notifications** : Préférences de communication
- **Dashboard** : Configuration de l'affichage

### Historique et Activités
- **Actions utilisateur** : Log des activités
- **Statistiques** : Métriques d'utilisation
- **Données de navigation** : Pages visitées, temps passé

## 🔧 Services Implémentés

### AuthService
```typescript
class AuthService {
  // Gestion des sessions
  login(credentials): Observable<AuthResponse>
  logout(): Observable<any>
  refreshSession(): Observable<AuthResponse>
  
  // Vérification et validation
  isAuthenticated(): boolean
  getSessionStats(): SessionStats
  hasRole(role: string): boolean
  
  // Gestion des erreurs
  handleError(error: HttpErrorResponse): Observable<never>
  forceLogout(): void
}
```

### UserDataService
```typescript
class UserDataService {
  // Gestion des données utilisateur
  saveUserData(data): Observable<UserData>
  updatePreferences(preferences): Observable<UserData>
  addActivity(activity): Observable<UserData>
  
  // Synchronisation
  syncUserData(): Observable<UserData>
  exportUserData(): Observable<Blob>
  importUserData(file): Observable<UserData>
  
  // Utilitaires
  getPreferences(): UserPreferences
  getRecentActivities(limit): UserActivity[]
  resetPreferences(): Observable<UserData>
}
```

## 🎨 Interface Utilisateur

### Composant SessionInfo
- **Affichage des informations de session**
- **Statistiques de temps restant**
- **Actions de gestion (rafraîchir, synchroniser)**
- **Interface responsive et moderne**

### Intégration dans le Header
- **Bouton d'information de session**
- **Affichage conditionnel selon l'authentification**
- **Navigation contextuelle**

## 🔄 Workflow de Session

### 1. Connexion
```
Utilisateur → Saisie identifiants → Validation → Création session → Redirection
```

### 2. Utilisation
```
Surveillance activité → Mise à jour lastActivity → Vérification expiration → Renouvellement si nécessaire
```

### 3. Déconnexion
```
Action utilisateur → Nettoyage session → Suppression données locales → Redirection
```

### 4. Expiration
```
Détection expiration → Déconnexion forcée → Nettoyage → Redirection login
```

## 📱 Stockage Local

### Structure localStorage
```javascript
{
  "user_session": {
    "user": { /* données utilisateur */ },
    "token": "jwt_token",
    "expiresAt": 1234567890,
    "lastActivity": 1234567890
  },
  "user_data_123": {
    "preferences": { /* préférences */ },
    "activities": [ /* historique */ ],
    "lastSync": "2024-03-20T10:30:00Z"
  }
}
```

### Nettoyage Automatique
- **Sessions expirées** : Suppression automatique
- **Données obsolètes** : Nettoyage périodique
- **Gestion des erreurs** : Récupération depuis le serveur

## 🧪 Tests et Validation

### Tests de Session
1. **Création de session** : Vérifier la génération correcte
2. **Renouvellement** : Tester le renouvellement automatique
3. **Expiration** : Vérifier la déconnexion automatique
4. **Activité** : Tester la surveillance d'activité
5. **Synchronisation** : Vérifier la cohérence des données

### Tests de Sécurité
1. **Tokens invalides** : Rejet des tokens expirés
2. **Attaques CSRF** : Protection contre les attaques
3. **Injection** : Validation des données d'entrée
4. **XSS** : Protection contre les scripts malveillants

## 🚀 Utilisation

### Démarrage
```powershell
# Tester la gestion des sessions
.\test-sessions.ps1
```

### Accès
- **Application** : http://localhost:4200
- **API Backend** : http://localhost:8000
- **Documentation** : README-SESSIONS.md

### Fonctionnalités Testées
- ✅ Authentification avec tokens
- ✅ Gestion des sessions
- ✅ Surveillance d'activité
- ✅ Renouvellement automatique
- ✅ Stockage des préférences
- ✅ Synchronisation des données
- ✅ Protection de sécurité

## 📈 Métriques et Monitoring

### Statistiques de Session
- **Durée moyenne des sessions**
- **Taux de renouvellement**
- **Activité utilisateur**
- **Erreurs d'authentification**

### Alertes
- **Sessions expirées**
- **Tentatives d'accès non autorisées**
- **Erreurs de synchronisation**
- **Problèmes de performance**

## 🔮 Évolutions Futures

### Améliorations Planifiées
- **Sessions multiples** : Support de plusieurs appareils
- **Authentification 2FA** : Double authentification
- **Sessions persistantes** : "Se souvenir de moi"
- **Analytics avancés** : Métriques détaillées

### Optimisations Techniques
- **Cache intelligent** : Mise en cache des données
- **Compression** : Réduction de la taille des données
- **Chiffrement** : Chiffrement des données sensibles
- **Performance** : Optimisation des requêtes

---

**Version** : 1.0.0  
**Dernière mise à jour** : Mars 2024  
**Conformité** : ✅ Complète
