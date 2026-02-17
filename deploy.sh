#!/bin/bash
# =============================================================
# Deploy Script - Aktivitas Santri
# Jalankan di server hosting: bash deploy.sh
# =============================================================

set -e

echo "🚀 Memulai deployment..."
echo "================================================"

# 1. Pull latest code
echo ""
echo "📥 [1/5] Pulling latest code dari Git..."
git pull origin main

# 2. Install/update PHP dependencies
echo ""
echo "📦 [2/5] Updating PHP dependencies (Composer)..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Build frontend (skip jika npm tidak tersedia)
if command -v npm &> /dev/null; then
    echo ""
    echo "🔧 [3/5] Installing Node dependencies & building..."
    npm ci --production=false
    npm run build
else
    echo ""
    echo "⏭️  [3/5] npm tidak tersedia, skip build frontend (sudah ter-commit)"
fi

# 4. Run database migrations
echo ""
echo "🗄️  [4/5] Running database migrations..."
php artisan migrate --force

# 5. Clear & optimize caches
echo ""
echo "⚡ [5/5] Optimizing application..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "================================================"
echo "✅ Deployment selesai!"
echo "================================================"
