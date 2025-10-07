#!/bin/bash

# Script untuk generate APP_KEY Laravel
# Usage: ./generate-key.sh

set -e

echo "🔑 Generating Laravel APP_KEY..."
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Generate key
echo "Running: docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show"
echo ""

APP_KEY=$(docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show 2>/dev/null || echo "")

if [ -z "$APP_KEY" ]; then
    echo "❌ Failed to generate APP_KEY. Trying alternative method..."
    echo ""
    echo "Please run this command manually:"
    echo "docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show"
    exit 1
fi

echo "✅ APP_KEY generated successfully!"
echo ""
echo "=================================================="
echo "Copy this to your Portainer Environment Variables:"
echo "=================================================="
echo ""
echo "APP_KEY=$APP_KEY"
echo ""
echo "=================================================="
echo ""
echo "📝 Next steps:"
echo "1. Copy the APP_KEY above"
echo "2. Go to Portainer → Stacks → anggaran-desa"
echo "3. Click 'Editor'"
echo "4. Update APP_KEY in environment section"
echo "5. Click 'Update the stack'"
echo ""
