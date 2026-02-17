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
echo "📥 [1/6] Pulling latest code dari Git..."
git pull origin main

# 2. Install/update PHP dependencies
echo ""
echo "📦 [2/6] Updating PHP dependencies (Composer)..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Install/update Node dependencies & build frontend
echo ""
echo "🔧 [3/6] Installing Node dependencies..."
npm ci --production=false

echo ""
echo "🏗️  [4/6] Building frontend assets..."
npm run build

# 4. Run database migrations
echo ""
echo "🗄️  [5/6] Running database migrations..."
php artisan migrate --force

# 5. Clear & optimize caches
echo ""
echo "⚡ [6/6] Optimizing application..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue workers (jika ada)
# php artisan queue:restart

echo ""
echo "================================================"
echo "✅ Deployment selesai!"
echo "================================================"
