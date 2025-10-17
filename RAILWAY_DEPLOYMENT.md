# Railway Deployment Guide

This guide will help you deploy your PHP authentication application to Railway.

## Prerequisites

1. **Railway Account**: Sign up at [railway.app](https://railway.app)
2. **Git Repository**: Your code should be in a Git repository (GitHub, GitLab, or Bitbucket)
3. **Supabase Project**: Your Supabase project should be set up and configured

## Deployment Steps

### 1. Prepare Your Repository

Make sure all files are committed to your Git repository:

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

### 2. Deploy to Railway

#### Option A: Deploy via Railway Dashboard

1. Go to [railway.app](https://railway.app) and sign in
2. Click "New Project"
3. Select "Deploy from GitHub repo" (or your Git provider)
4. Choose your repository
5. Railway will automatically detect it's a PHP project

#### Option B: Deploy via Railway CLI

1. Install Railway CLI:
   ```bash
   npm install -g @railway/cli
   ```

2. Login to Railway:
   ```bash
   railway login
   ```

3. Initialize and deploy:
   ```bash
   railway init
   railway up
   ```

### 3. Configure Environment Variables

In your Railway dashboard:

1. Go to your project
2. Click on "Variables" tab
3. Add the following environment variables:

```
SUPABASE_URL=https://kmpsnwindfvjtwstwqeq.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImttcHNud2luZGZ2anR3c3R3cWVxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA2NTM1MTcsImV4cCI6MjA3NjIyOTUxN30.b2Z9LNZ9zEhrdqOSvNAnH0hR_fzlnKRvnMr9XLuKuCA
APP_NAME=PHP Auth App
APP_ENV=production
APP_DEBUG=false
```

### 4. Custom Domain (Optional)

1. In Railway dashboard, go to "Settings"
2. Click "Domains"
3. Add your custom domain
4. Follow the DNS configuration instructions

## Configuration Files

The following files have been created for Railway deployment:

- `railway.json` - Railway configuration
- `Procfile` - Process definition
- `railway_env.php` - Railway-specific environment loader

## Environment Variables

| Variable | Description | Required |
|----------|-------------|----------|
| `SUPABASE_URL` | Your Supabase project URL | Yes |
| `SUPABASE_ANON_KEY` | Your Supabase anonymous key | Yes |
| `APP_NAME` | Application name | No (defaults to "PHP Auth App") |
| `APP_ENV` | Environment (production/development) | No (defaults to "production") |
| `APP_DEBUG` | Enable debug mode | No (defaults to "false") |

## Troubleshooting

### Common Issues

1. **Environment Variables Not Loading**
   - Check that all required variables are set in Railway dashboard
   - Verify variable names match exactly (case-sensitive)

2. **Application Not Starting**
   - Check Railway logs for PHP errors
   - Ensure all required PHP extensions are available

3. **Supabase Connection Issues**
   - Verify your Supabase URL and key are correct
   - Check Supabase project settings

### Viewing Logs

```bash
# Via Railway CLI
railway logs

# Or check the Railway dashboard logs section
```

## Local Testing

To test your application locally with Railway-like environment:

```bash
# Set environment variables
export SUPABASE_URL="your_supabase_url"
export SUPABASE_ANON_KEY="your_supabase_key"
export APP_ENV="production"
export APP_DEBUG="false"

# Start the server
php -S localhost:8000
```

## Post-Deployment

After successful deployment:

1. **Test the application** - Visit your Railway URL
2. **Check authentication** - Test login/signup functionality
3. **Monitor logs** - Watch for any errors in Railway dashboard
4. **Set up monitoring** - Consider adding error tracking

## Security Notes

- Never commit your `config.env` file to version control
- Use Railway's environment variables for sensitive data
- Enable HTTPS (Railway provides this automatically)
- Consider setting up proper CORS policies for production

## Support

- Railway Documentation: [docs.railway.app](https://docs.railway.app)
- Railway Discord: [discord.gg/railway](https://discord.gg/railway)
- This project's README.md for application-specific help
