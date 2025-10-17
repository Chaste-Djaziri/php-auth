# Railway PHP Deployment Troubleshooting

## Issue: "php: command not found"

This error occurs when Railway doesn't properly detect or install PHP. Here's how to fix it:

### ✅ Solution Applied

I've updated your configuration with the following fixes:

1. **`nixpacks.toml`** - Explicitly configures PHP 8.2
2. **`composer.json`** - Helps Railway detect this as a PHP project
3. **`start.php`** - Smart startup script that finds PHP executable
4. **`.php-version`** - Specifies PHP version for Railway
5. **Updated `railway.json`** and **`Procfile`** - Use the smart startup script

### 🔄 Redeploy Steps

1. **Commit the new files:**
   ```bash
   git add .
   git commit -m "Fix PHP detection for Railway"
   git push
   ```

2. **Redeploy on Railway:**
   - Go to your Railway dashboard
   - Click "Redeploy" or the deployment will auto-trigger
   - Or use CLI: `railway up`

### 🔍 What Changed

#### New Files:
- `nixpacks.toml` - Explicit PHP 8.2 configuration
- `composer.json` - PHP project detection
- `start.php` - Smart PHP server startup
- `.php-version` - PHP version specification

#### Updated Files:
- `railway.json` - Uses `php start.php` instead of direct PHP command
- `Procfile` - Uses `php start.php` for better compatibility

### 🚀 How the Fix Works

1. **`nixpacks.toml`** tells Railway to install PHP 8.2 and Composer
2. **`composer.json`** helps Railway detect this as a PHP project
3. **`start.php`** intelligently finds the PHP executable path
4. **`.php-version`** specifies the exact PHP version needed

### 🔧 Manual Verification

If you want to test locally with the same setup:

```bash
# Test the startup script
php start.php

# Or test with a specific port
PORT=8000 php start.php
```

### 📋 Environment Variables

Make sure these are set in Railway:
- `SUPABASE_URL`
- `SUPABASE_ANON_KEY`
- `APP_ENV=production`
- `APP_DEBUG=false`

### 🆘 If Still Not Working

1. **Check Railway logs** for any build errors
2. **Verify environment variables** are set correctly
3. **Try manual PHP path** in railway.json:
   ```json
   "startCommand": "/usr/bin/php start.php"
   ```

### 📞 Support

- Railway Docs: [docs.railway.app](https://docs.railway.app)
- Railway Discord: [discord.gg/railway](https://discord.gg/railway)
- Check Railway logs for specific error messages
