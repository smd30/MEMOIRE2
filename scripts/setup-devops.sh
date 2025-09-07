#!/bin/bash

# 🛠️ Script de configuration DevOps pour KDS Assurance
# Ce script configure l'environnement DevOps complet

set -e

# Couleurs pour les logs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Variables
PROJECT_NAME="kds-assurance"
GITHUB_USERNAME="votre-username"
SONAR_ORG="votre-org"

# Fonctions utilitaires
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Installation des outils requis
install_tools() {
    log_info "Installation des outils DevOps..."
    
    # Vérifier si on est sur Ubuntu/Debian
    if command -v apt &> /dev/null; then
        # Mettre à jour les paquets
        sudo apt update
        
        # Installer Docker
        if ! command -v docker &> /dev/null; then
            log_info "Installation de Docker..."
            curl -fsSL https://get.docker.com -o get-docker.sh
            sudo sh get-docker.sh
            sudo usermod -aG docker $USER
            rm get-docker.sh
        fi
        
        # Installer kubectl
        if ! command -v kubectl &> /dev/null; then
            log_info "Installation de kubectl..."
            curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
            sudo install -o root -g root -m 0755 kubectl /usr/local/bin/kubectl
            rm kubectl
        fi
        
        # Installer Helm
        if ! command -v helm &> /dev/null; then
            log_info "Installation de Helm..."
            curl https://raw.githubusercontent.com/helm/helm/main/scripts/get-helm-3 | bash
        fi
        
        # Installer Ansible
        if ! command -v ansible &> /dev/null; then
            log_info "Installation d'Ansible..."
            sudo apt install -y ansible
        fi
        
        # Installer Terraform
        if ! command -v terraform &> /dev/null; then
            log_info "Installation de Terraform..."
            wget -O- https://apt.releases.hashicorp.com/gpg | sudo gpg --dearmor -o /usr/share/keyrings/hashicorp-archive-keyring.gpg
            echo "deb [signed-by=/usr/share/keyrings/hashicorp-archive-keyring.gpg] https://apt.releases.hashicorp.com $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/hashicorp.list
            sudo apt update && sudo apt install terraform
        fi
        
    else
        log_warning "Ce script est optimisé pour Ubuntu/Debian. Veuillez installer manuellement:"
        log_warning "- Docker"
        log_warning "- kubectl"
        log_warning "- Helm"
        log_warning "- Ansible"
        log_warning "- Terraform"
    fi
    
    log_success "Outils DevOps installés"
}

# Configuration de GitHub
setup_github() {
    log_info "Configuration de GitHub..."
    
    # Vérifier si gh CLI est installé
    if ! command -v gh &> /dev/null; then
        log_info "Installation de GitHub CLI..."
        curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg | sudo dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg
        echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null
        sudo apt update
        sudo apt install gh
    fi
    
    # Authentifier avec GitHub
    log_info "Authentification GitHub..."
    gh auth login
    
    # Créer le repository
    log_info "Création du repository GitHub..."
    gh repo create ${PROJECT_NAME} --public --description "Système de gestion d'assurance automobile"
    
    log_success "GitHub configuré"
}

# Configuration de SonarCloud
setup_sonarcloud() {
    log_info "Configuration de SonarCloud..."
    
    log_info "1. Allez sur https://sonarcloud.io"
    log_info "2. Connectez-vous avec votre compte GitHub"
    log_info "3. Créez une nouvelle organisation"
    log_info "4. Importez le repository ${PROJECT_NAME}"
    log_info "5. Copiez le token SonarCloud"
    
    read -p "Entrez votre token SonarCloud: " SONAR_TOKEN
    
    # Ajouter le token aux secrets GitHub
    gh secret set SONAR_TOKEN --body "${SONAR_TOKEN}"
    
    log_success "SonarCloud configuré"
}

# Configuration de Kubernetes
setup_kubernetes() {
    log_info "Configuration de Kubernetes..."
    
    log_info "Options de cluster Kubernetes:"
    log_info "1. Minikube (local)"
    log_info "2. Kind (local)"
    log_info "3. GKE (Google Cloud)"
    log_info "4. EKS (AWS)"
    log_info "5. AKS (Azure)"
    
    read -p "Choisissez votre option (1-5): " K8S_OPTION
    
    case $K8S_OPTION in
        1)
            # Minikube
            log_info "Installation de Minikube..."
            curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
            sudo install minikube-linux-amd64 /usr/local/bin/minikube
            minikube start
            ;;
        2)
            # Kind
            log_info "Installation de Kind..."
            [ $(uname -m) = x86_64 ] && curl -Lo ./kind https://kind.sigs.k8s.io/dl/v0.20.0/kind-linux-amd64
            chmod +x ./kind
            sudo mv ./kind /usr/local/bin/kind
            kind create cluster --name ${PROJECT_NAME}
            ;;
        3)
            log_info "Configuration GKE..."
            log_info "1. Installez gcloud CLI"
            log_info "2. Configurez l'authentification"
            log_info "3. Créez un cluster GKE"
            ;;
        4)
            log_info "Configuration EKS..."
            log_info "1. Installez AWS CLI"
            log_info "2. Configurez les credentials"
            log_info "3. Créez un cluster EKS"
            ;;
        5)
            log_info "Configuration AKS..."
            log_info "1. Installez Azure CLI"
            log_info "2. Configurez l'authentification"
            log_info "3. Créez un cluster AKS"
            ;;
    esac
    
    # Obtenir la configuration kubeconfig
    log_info "Configuration du kubeconfig..."
    kubectl config view --raw --minify > kubeconfig.yaml
    KUBE_CONFIG=$(cat kubeconfig.yaml | base64 -w 0)
    gh secret set KUBE_CONFIG --body "${KUBE_CONFIG}"
    rm kubeconfig.yaml
    
    log_success "Kubernetes configuré"
}

# Configuration de Render
setup_render() {
    log_info "Configuration de Render..."
    
    log_info "1. Allez sur https://render.com"
    log_info "2. Connectez votre compte GitHub"
    log_info "3. Créez un nouveau service Web"
    log_info "4. Sélectionnez le repository ${PROJECT_NAME}"
    log_info "5. Configurez les variables d'environnement"
    log_info "6. Copiez le Service ID et l'API Key"
    
    read -p "Entrez le Service ID Render: " RENDER_SERVICE_ID
    read -p "Entrez l'API Key Render: " RENDER_API_KEY
    
    # Ajouter les secrets GitHub
    gh secret set RENDER_SERVICE_ID --body "${RENDER_SERVICE_ID}"
    gh secret set RENDER_API_KEY --body "${RENDER_API_KEY}"
    
    log_success "Render configuré"
}

# Configuration des alertes
setup_alerts() {
    log_info "Configuration des alertes..."
    
    log_info "Voulez-vous configurer les alertes Slack? (y/n)"
    read -p "Réponse: " SETUP_SLACK
    
    if [ "$SETUP_SLACK" = "y" ] || [ "$SETUP_SLACK" = "Y" ]; then
        log_info "1. Créez une application Slack"
        log_info "2. Activez les webhooks entrants"
        log_info "3. Copiez l'URL du webhook"
        
        read -p "Entrez l'URL du webhook Slack: " SLACK_WEBHOOK
        gh secret set SLACK_WEBHOOK --body "${SLACK_WEBHOOK}"
        
        log_success "Alertes Slack configurées"
    fi
}

# Test de la configuration
test_configuration() {
    log_info "Test de la configuration..."
    
    # Test Docker
    if docker --version &> /dev/null; then
        log_success "Docker fonctionne"
    else
        log_error "Docker ne fonctionne pas"
    fi
    
    # Test kubectl
    if kubectl cluster-info &> /dev/null; then
        log_success "kubectl fonctionne"
    else
        log_error "kubectl ne fonctionne pas"
    fi
    
    # Test GitHub CLI
    if gh auth status &> /dev/null; then
        log_success "GitHub CLI fonctionne"
    else
        log_error "GitHub CLI ne fonctionne pas"
    fi
    
    log_success "Configuration testée"
}

# Fonction principale
main() {
    log_info "🛠️ Configuration DevOps pour KDS Assurance"
    
    # Vérifier les arguments
    if [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
        echo "Usage: $0 [OPTIONS]"
        echo "Options:"
        echo "  --help, -h     Afficher cette aide"
        echo "  --tools-only   Installer seulement les outils"
        echo "  --github-only  Configurer seulement GitHub"
        echo "  --k8s-only     Configurer seulement Kubernetes"
        echo "  --test-only    Tester seulement la configuration"
        exit 0
    fi
    
    # Exécuter selon les options
    case "$1" in
        "--tools-only")
            install_tools
            ;;
        "--github-only")
            setup_github
            setup_sonarcloud
            setup_render
            setup_alerts
            ;;
        "--k8s-only")
            setup_kubernetes
            ;;
        "--test-only")
            test_configuration
            ;;
        *)
            install_tools
            setup_github
            setup_sonarcloud
            setup_kubernetes
            setup_render
            setup_alerts
            test_configuration
            ;;
    esac
    
    log_success "🎉 Configuration DevOps terminée!"
    log_info "📋 Prochaines étapes:"
    log_info "1. Poussez votre code: git push origin main"
    log_info "2. Le pipeline CI/CD se déclenchera automatiquement"
    log_info "3. Surveillez les déploiements dans GitHub Actions"
    log_info "4. Accédez aux dashboards de monitoring"
}

# Exécuter le script
main "$@"
