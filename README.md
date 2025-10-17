# PHP Authentication System with Supabase

This is a complete authentication system built with HTML, CSS, PHP, and Supabase.

## Features

- ✅ Glassmorphism design matching your reference image
- ✅ Login form with email/password
- ✅ Signup form with validation
- ✅ Welcome dashboard with user info
- ✅ Supabase authentication integration
- ✅ Session management
- ✅ Responsive design

## Setup Instructions

### 1. Supabase Setup

Your Supabase integration is already connected! The following environment variables are available:
- `SUPABASE_URL`
- `SUPABASE_ANON_KEY`

### 2. File Structure

```
/
├── login.html          # Login page
├── signup.html         # Signup page
├── dashboard.html      # Dashboard page
├── auth.php           # Authentication backend
└── README.md          # This file
```

### 3. Server Requirements

- PHP 7.4 or higher
- cURL extension enabled
- Session support

### 4. Deployment

You can deploy this to any PHP hosting service:

1. **Upload files** to your web server
2. **Configure environment variables** in your hosting panel:
   - `SUPABASE_URL`
   - `SUPABASE_ANON_KEY`
3. **Access** `login.html` to start using the app

### 5. Local Development

To test locally:

```bash
# Start PHP built-in server
php -S localhost:8000

# Visit http://localhost:8000/login.html