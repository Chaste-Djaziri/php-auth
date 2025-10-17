#!/bin/bash

# Railway Deployment Script
# This script helps prepare and deploy your PHP app to Railway

echo "🚀 Railway Deployment Script"
echo "=============================="

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "⚠️  Git repository not found. Initializing..."
    git init
    git add .
    git commit -m "Initial commit for Railway deployment"
fi

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "📦 Installing Railway CLI..."
    npm install -g @railway/cli
fi

# Check if user is logged in to Railway
if ! railway whoami &> /dev/null; then
    echo "🔐 Please login to Railway:"
    railway login
fi

echo "🚀 Deploying to Railway..."
railway up

echo "✅ Deployment initiated!"
echo ""
echo "📋 Next steps:"
echo "1. Go to your Railway dashboard"
echo "2. Set environment variables:"
echo "   - SUPABASE_URL"
echo "   - SUPABASE_ANON_KEY"
echo "   - APP_ENV=production"
echo "   - APP_DEBUG=false"
echo "3. Check deployment logs"
echo "4. Test your application"
echo ""
echo "📖 For detailed instructions, see RAILWAY_DEPLOYMENT.md"
