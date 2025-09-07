#!/bin/bash

# 🚀 Script de déploiement KDS Assurance
# Ce script automatise le déploiement complet de l'application

set -e

# Couleurs pour les logs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Variables
APP_NAME="kds-assurance"
NAMESPACE="kds-assurance"
IMAGE_TAG="latest"
REGISTRY="ghcr.io"
REPO_NAME="votre-username/kds-assurance"

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

# Vérification des prérequis
check_prerequisites() {
    log_info "Vérification des prérequis..."
    
    # Vérifier Docker
    if ! command -v docker &> /dev/null; then
        log_error "Docker n'est pas installé"
        exit 1
    fi
    
    # Vérifier kubectl
    if ! command -v kubectl &> /dev/null; then
        log_error "kubectl n'est pas installé"
        exit 1
    fi
    
    # Vérifier la connexion Kubernetes
    if ! kubectl cluster-info &> /dev/null; then
        log_error "Impossible de se connecter au cluster Kubernetes"
        exit 1
    fi
    
    log_success "Tous les prérequis sont satisfaits"
}

# Build et push des images Docker
build_and_push_images() {
    log_info "Construction et push des images Docker..."
    
    # Build backend
    log_info "Construction de l'image backend..."
    docker build -t ${REGISTRY}/${REPO_NAME}:${IMAGE_TAG} ./backend
    docker push ${REGISTRY}/${REPO_NAME}:${IMAGE_TAG}
    
    # Build frontend
    log_info "Construction de l'image frontend..."
    docker build -t ${REGISTRY}/${REPO_NAME}-frontend:${IMAGE_TAG} ./frontend
    docker push ${REGISTRY}/${REPO_NAME}-frontend:${IMAGE_TAG}
    
    log_success "Images Docker construites et poussées avec succès"
}

# Déploiement sur Kubernetes
deploy_to_kubernetes() {
    log_info "Déploiement sur Kubernetes..."
    
    # Créer les namespaces
    kubectl apply -f kubernetes/namespace.yaml
    
    # Appliquer les configurations
    kubectl apply -f kubernetes/configmap.yaml
    kubectl apply -f kubernetes/secrets.yaml
    
    # Déployer les services de base
    kubectl apply -f kubernetes/mysql.yaml
    kubectl apply -f kubernetes/redis.yaml
    
    # Attendre que les services soient prêts
    log_info "Attente de la disponibilité des services..."
    kubectl wait --for=condition=ready pod -l app=mysql -n ${NAMESPACE} --timeout=300s
    kubectl wait --for=condition=ready pod -l app=redis -n ${NAMESPACE} --timeout=300s
    
    # Déployer les applications
    kubectl apply -f kubernetes/laravel-deployment.yaml
    kubectl apply -f kubernetes/angular-deployment.yaml
    
    # Appliquer l'ingress
    kubectl apply -f kubernetes/ingress.yaml
    
    # Mettre à jour les images
    kubectl set image deployment/laravel-app laravel-app=${REGISTRY}/${REPO_NAME}:${IMAGE_TAG} -n ${NAMESPACE}
    kubectl set image deployment/angular-app angular-app=${REGISTRY}/${REPO_NAME}-frontend:${IMAGE_TAG} -n ${NAMESPACE}
    
    # Attendre le déploiement
    log_info "Attente du déploiement..."
    kubectl rollout status deployment/laravel-app -n ${NAMESPACE} --timeout=300s
    kubectl rollout status deployment/angular-app -n ${NAMESPACE} --timeout=300s
    
    log_success "Déploiement Kubernetes terminé"
}

# Déploiement du monitoring
deploy_monitoring() {
    log_info "Déploiement du monitoring..."
    
    # Déployer ELK Stack
    kubectl apply -f monitoring/elk-stack.yaml
    
    # Déployer Prometheus & Grafana
    kubectl apply -f monitoring/prometheus-grafana.yaml
    
    # Attendre que les services soient prêts
    log_info "Attente de la disponibilité des services de monitoring..."
    kubectl wait --for=condition=ready pod -l app=elasticsearch -n monitoring --timeout=300s
    kubectl wait --for=condition=ready pod -l app=kibana -n monitoring --timeout=300s
    kubectl wait --for=condition=ready pod -l app=prometheus -n monitoring --timeout=300s
    kubectl wait --for=condition=ready pod -l app=grafana -n monitoring --timeout=300s
    
    log_success "Monitoring déployé avec succès"
}

# Tests de santé
health_check() {
    log_info "Vérification de la santé des services..."
    
    # Vérifier les pods
    kubectl get pods -n ${NAMESPACE}
    kubectl get pods -n monitoring
    
    # Vérifier les services
    kubectl get services -n ${NAMESPACE}
    kubectl get services -n monitoring
    
    # Test de connectivité
    log_info "Test de connectivité..."
    kubectl run test-pod --image=busybox --rm -it --restart=Never -- nslookup laravel-service.${NAMESPACE}.svc.cluster.local
    
    log_success "Vérification de santé terminée"
}

# Nettoyage
cleanup() {
    log_info "Nettoyage des ressources temporaires..."
    
    # Supprimer les pods de test
    kubectl delete pod test-pod --ignore-not-found=true
    
    log_success "Nettoyage terminé"
}

# Fonction principale
main() {
    log_info "🚀 Début du déploiement KDS Assurance"
    
    # Vérifier les arguments
    if [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
        echo "Usage: $0 [OPTIONS]"
        echo "Options:"
        echo "  --help, -h     Afficher cette aide"
        echo "  --build-only   Construire seulement les images"
        echo "  --k8s-only     Déployer seulement sur Kubernetes"
        echo "  --monitoring   Déployer avec monitoring"
        echo "  --cleanup      Nettoyer les ressources"
        exit 0
    fi
    
    # Exécuter selon les options
    case "$1" in
        "--build-only")
            check_prerequisites
            build_and_push_images
            ;;
        "--k8s-only")
            check_prerequisites
            deploy_to_kubernetes
            health_check
            ;;
        "--monitoring")
            check_prerequisites
            build_and_push_images
            deploy_to_kubernetes
            deploy_monitoring
            health_check
            ;;
        "--cleanup")
            cleanup
            ;;
        *)
            check_prerequisites
            build_and_push_images
            deploy_to_kubernetes
            deploy_monitoring
            health_check
            cleanup
            ;;
    esac
    
    log_success "🎉 Déploiement terminé avec succès!"
    log_info "📊 Accès aux services:"
    log_info "  - Application: https://kds-assurance.com"
    log_info "  - Kibana: http://kibana-service.monitoring.svc.cluster.local:5601"
    log_info "  - Grafana: http://grafana-service.monitoring.svc.cluster.local:3000"
    log_info "  - Prometheus: http://prometheus-service.monitoring.svc.cluster.local:9090"
}

# Exécuter le script
main "$@"
