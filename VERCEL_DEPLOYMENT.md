# Vercel Deployment Guide

This guide will help you deploy your PHP authentication app to Vercel.

## Prerequisites

1. **Vercel Account**: Sign up at [vercel.com](https://vercel.com)
2. **GitHub Account**: Your code should be in a GitHub repository
3. **Supabase Project**: Make sure your Supabase project is set up

## Step 1: Prepare Your Repository

1. **Push your code to GitHub** (if not already done):
   ```bash
   git add .
   git commit -m "Prepare for Vercel deployment"
   git push origin main
   ```

## Step 2: Deploy to Vercel

### Option A: Deploy via Vercel Dashboard (Recommended)

1. **Go to [vercel.com](https://vercel.com)** and sign in
2. **Click "New Project"**
3. **Import your GitHub repository**
4. **Configure the project**:
   - Framework Preset: **Other**
   - Root Directory: **./** (leave as default)
   - Build Command: **Leave empty** (Vercel will auto-detect)
   - Output Directory: **Leave empty**

### Option B: Deploy via Vercel CLI

1. **Install Vercel CLI**:
   ```bash
   npm install -g vercel
   ```

2. **Login to Vercel**:
   ```bash
   vercel login
   ```

3. **Deploy**:
   ```bash
   vercel
   ```

## Step 3: Configure Environment Variables

1. **Go to your project dashboard** in Vercel
2. **Click on "Settings"** tab
3. **Click on "Environment Variables"**
4. **Add the following variables**:

   | Name | Value | Environment |
   |------|-------|-------------|
   | `SUPABASE_URL` | Your Supabase project URL | Production, Preview, Development |
   | `SUPABASE_ANON_KEY` | Your Supabase anon key | Production, Preview, Development |
   | `APP_DEBUG` | `false` | Production, Preview, Development |

5. **Click "Save"**

## Step 4: Redeploy

After adding environment variables, you need to redeploy:

1. **Go to "Deployments" tab**
2. **Click the three dots** on your latest deployment
3. **Click "Redeploy"**

## Step 5: Test Your Deployment

1. **Visit your Vercel URL** (e.g., `https://your-project.vercel.app`)
2. **Test the authentication flow**:
   - Try signing up with a new account
   - Try logging in
   - Check if the dashboard loads correctly

## Troubleshooting

### Common Issues:

1. **Environment Variables Not Working**:
   - Make sure you've added them in the Vercel dashboard
   - Redeploy after adding environment variables
   - Check that variable names match exactly

2. **PHP Errors**:
   - Check the Vercel function logs in the dashboard
   - Make sure all PHP files are in the root directory

3. **Supabase Connection Issues**:
   - Verify your Supabase URL and key are correct
   - Check Supabase project settings for CORS configuration

### Debug Mode:

To enable debug mode in production:
1. Set `APP_DEBUG` to `true` in Vercel environment variables
2. Redeploy
3. Check function logs for detailed error information

## File Structure

Your project should have this structure for Vercel:
```
/
├── vercel.json          # Vercel configuration
├── .vercelignore        # Files to ignore during deployment
├── index.html           # Main page
├── login.html           # Login page
├── signup.html          # Signup page
├── dashboard.html       # Dashboard page
├── auth.php             # Authentication API
├── load_env.php         # Environment loader
└── config.env.example   # Example environment file
```

## Custom Domain (Optional)

1. **Go to "Settings" → "Domains"**
2. **Add your custom domain**
3. **Follow DNS configuration instructions**

## Monitoring

- **Check deployment logs** in the Vercel dashboard
- **Monitor function performance** in the "Functions" tab
- **Set up alerts** for errors if needed

## Support

- **Vercel Documentation**: [vercel.com/docs](https://vercel.com/docs)
- **Vercel Community**: [github.com/vercel/vercel/discussions](https://github.com/vercel/vercel/discussions)
