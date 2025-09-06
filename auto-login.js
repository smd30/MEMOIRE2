// Script pour forcer la connexion automatique
console.log('=== Connexion Automatique au Module Devis ===');

// Fonction pour se connecter automatiquement
async function autoLogin() {
    try {
        console.log('1. Tentative de connexion automatique...');
        
        const loginData = {
            email: 'client@test.com',
            password: 'password123'
        };

        const response = await fetch('http://localhost:8000/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(loginData)
        });

        if (response.ok) {
            const data = await response.json();
            console.log('✅ Connexion réussie!');
            
            // Stocker les données de session
            const sessionData = {
                user: data.data.user,
                token: data.data.token,
                expiresAt: Date.now() + (24 * 60 * 60 * 1000), // 24 heures
                lastActivity: Date.now()
            };
            
            localStorage.setItem('user_session', JSON.stringify(sessionData));
            console.log('✅ Session stockée dans localStorage');
            
            // Recharger la page pour appliquer la connexion
            console.log('🔄 Rechargement de la page...');
            window.location.reload();
            
        } else {
            console.error('❌ Erreur de connexion:', response.status);
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
    }
}

// Exécuter la connexion automatique
autoLogin();

// Instructions pour l'utilisateur
console.log('\n📋 Instructions:');
console.log('1. Ouvrez la console (F12)');
console.log('2. Copiez et collez ce script');
console.log('3. Ou utilisez les identifiants manuellement:');
console.log('   • Email: client@test.com');
console.log('   • Mot de passe: password123');





