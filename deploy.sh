#!/bin/bash

# Script helper untuk development dan deployment
# Usage: ./deploy.sh [command]

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Functions
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Commands
build_local() {
    log_info "Building Docker image locally..."
    docker build -t anggaran-desa:latest .
    log_info "Build completed!"
}

build_arm() {
    log_info "Building Docker image for ARM64..."
    docker buildx build --platform linux/arm64 -t anggaran-desa:latest .
    log_info "Build completed!"
}

run_local() {
    log_info "Running application locally..."
    docker-compose up -d
    log_info "Application is running at http://localhost"
}

stop_local() {
    log_info "Stopping application..."
    docker-compose down
    log_info "Application stopped!"
}

logs() {
    docker-compose logs -f
}

shell() {
    docker exec -it anggaran-desa-app sh
}

migrate() {
    log_info "Running migrations..."
    docker exec -it anggaran-desa-app php artisan migrate --force
    log_info "Migrations completed!"
}

seed() {
    log_info "Running seeders..."
    docker exec -it anggaran-desa-app php artisan db:seed --force
    log_info "Seeders completed!"
}

optimize() {
    log_info "Optimizing application..."
    docker exec -it anggaran-desa-app php artisan config:cache
    docker exec -it anggaran-desa-app php artisan route:cache
    docker exec -it anggaran-desa-app php artisan view:cache
    log_info "Optimization completed!"
}

clear_cache() {
    log_info "Clearing cache..."
    docker exec -it anggaran-desa-app php artisan cache:clear
    docker exec -it anggaran-desa-app php artisan config:clear
    docker exec -it anggaran-desa-app php artisan route:clear
    docker exec -it anggaran-desa-app php artisan view:clear
    log_info "Cache cleared!"
}

build_assets() {
    log_info "Building frontend assets..."
    npm install
    npm run build
    log_info "Assets built successfully!"
}

fix_permissions() {
    log_info "Fixing permissions..."
    docker exec -it anggaran-desa-app sh -c "chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache"
    docker exec -it anggaran-desa-app sh -c "chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"
    log_info "Permissions fixed!"
}

show_help() {
    echo "Anggaran Desa - Deployment Helper"
    echo ""
    echo "Usage: ./deploy.sh [command]"
    echo ""
    echo "Commands:"
    echo "  build-local      - Build Docker image locally (x86_64)"
    echo "  build-arm        - Build Docker image for ARM64 architecture"
    echo "  run              - Run application with docker-compose"
    echo "  stop             - Stop application"
    echo "  logs             - Show application logs"
    echo "  shell            - Open shell in container"
    echo "  migrate          - Run database migrations"
    echo "  seed             - Run database seeders"
    echo "  optimize         - Optimize Laravel application"
    echo "  clear            - Clear all caches"
    echo "  build-assets     - Build frontend assets"
    echo "  fix-permissions  - Fix storage and cache permissions"
    echo "  help             - Show this help message"
    echo ""
}

# Main
case "$1" in
    build-local)
        build_local
        ;;
    build-arm)
        build_arm
        ;;
    run)
        run_local
        ;;
    stop)
        stop_local
        ;;
    logs)
        logs
        ;;
    shell)
        shell
        ;;
    migrate)
        migrate
        ;;
    seed)
        seed
        ;;
    optimize)
        optimize
        ;;
    clear)
        clear_cache
        ;;
    build-assets)
        build_assets
        ;;
    fix-permissions)
        fix_permissions
        ;;
    help|*)
        show_help
        ;;
esac
