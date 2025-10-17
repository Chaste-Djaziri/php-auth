# PHP Web Application Setup Guide

This PHP web application uses Supabase for authentication and includes login, signup, and dashboard functionality.

## Prerequisites

- PHP 7.4 or higher
- A Supabase account and project

## Quick Start

### 1. Configure Environment Variables

1. Copy the example environment file:
   ```bash
   cp config.env.example config.env
   ```

2. Edit `config.env` with your Supabase credentials:
   ```env
   SUPABASE_URL=https://your-project-id.supabase.co
   SUPABASE_ANON_KEY=your-anon-key-here
   ```

### 2. Get Supabase Credentials

1. Go to [Supabase Dashboard](https://supabase.com/dashboard)
2. Select your project
3. Go to Settings → API
4. Copy your Project URL and anon/public key
5. Paste them into your `config.env` file

### 3. Start the Development Server

**Option 1: Using the startup script**
```bash
php start_server.php
```

**Option 2: Using PHP built-in server directly**
```bash
php -S localhost:8000
```

### 4. Access the Application

Open your browser and go to:
- **Login**: http://localhost:8000/login.html
- **Signup**: http://localhost:8000/signup.html
- **Dashboard**: http://localhost:8000/dashboard.html

## File Structure

```
├── auth.php              # Authentication API endpoints
├── login.html            # Login page
├── signup.html           # Signup page
├── dashboard.html        # Dashboard page
├── load_env.php          # Environment variable loader
├── start_server.php      # Development server startup script
├── config.env            # Environment variables (create from example)
├── config.env.example    # Environment variables template
├── .gitignore           # Git ignore file
└── SETUP.md             # This setup guide
```

## Environment Variables

| Variable | Description | Required |
|----------|-------------|----------|
| `SUPABASE_URL` | Your Supabase project URL | Yes |
| `SUPABASE_ANON_KEY` | Your Supabase anonymous key | Yes |
| `APP_NAME` | Application name | No |
| `APP_ENV` | Environment (development/production) | No |
| `APP_DEBUG` | Enable debug mode | No |

## API Endpoints

The `auth.php` file provides the following endpoints:

- `GET auth.php?action=check` - Check if user is authenticated
- `POST auth.php?action=login` - User login
- `POST auth.php?action=signup` - User registration
- `POST auth.php?action=logout` - User logout

## Troubleshooting

### Common Issues

1. **"Missing Supabase configuration" error**
   - Make sure `config.env` exists and contains valid Supabase credentials
   - Check that the environment variables are properly formatted

2. **Authentication not working**
   - Verify your Supabase URL and API key are correct
   - Check Supabase project settings for authentication configuration

3. **Server won't start**
   - Ensure PHP is installed and accessible from command line
   - Check that port 8000 is not already in use

### Getting Help

- Check the browser console for JavaScript errors
- Check the PHP error logs
- Verify Supabase project is active and properly configured

## Security Notes

- Never commit `config.env` to version control
- Use different Supabase projects for development and production
- Keep your Supabase API keys secure
- The `.gitignore` file is configured to exclude sensitive files
