# Railway Environment Variables Guide

## ✅ Your Current Configuration

Your environment variables are correctly set! Here's what you have:

```
APP_NAME="HR Management System"
APP_ENV="production"
APP_KEY="base64:ZWa8FZIdyXxgMnFPpfqckhwOpym0ghqDQsJuPMf4F5w="
APP_DEBUG="false"
APP_URL="https://essu-human-resource.up.railway.app"
ASSET_URL="https://essu-human-resource.up.railway.app"
DB_CONNECTION="mysql"
DB_HOST="mysql.railway.internal"
DB_PORT="3306"
DB_DATABASE="railway"
DB_USERNAME="root"
DB_PASSWORD="xGrZvPAXmxXFwenXYWrDbGqqpMXDiwSE"
SESSION_DRIVER="database"
CACHE_STORE="database"
QUEUE_CONNECTION="database"
```

## 🚨 Fixing the 500 Error

The 500 error is most likely because **migrations haven't run yet**. Your app needs these tables:
- `sessions` (for SESSION_DRIVER=database)
- `cache` and `cache_locks` (for CACHE_STORE=database)
- `jobs`, `job_batches`, `failed_jobs` (for QUEUE_CONNECTION=database)
- All your application tables

### Solution: Run Migrations on Railway

**Option 1: Using Railway CLI (Recommended)**

1. Install Railway CLI: `npm i -g @railway/cli`
2. Login: `railway login`
3. Link your project: `railway link`
4. Run migrations: `railway run php artisan migrate --force`

**Option 2: Using Railway Dashboard**

1. Go to your Railway project
2. Click on your service
3. Go to **Settings** → **Deploy**
4. Add a **Deploy Command**: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`

**Option 3: Using nixpacks.toml (Already Created)**

I've created a `nixpacks.toml` file that will automatically run migrations on each deploy. Railway should detect it automatically.

## ⚠️ Optional Variables (Can Ignore Warnings)

These warnings are safe to ignore if you're not using these services:
- `DB_CACHE_*` - Only if using database caching
- `MEMCACHED_*` - Only if using Memcached
- `AWS_*` - Only if using AWS services
- `REDIS_*` - Only if using Redis
- `MAIL_*` - Only if sending emails (defaults to 'log')
- `PAPERTRAIL_*` - Only if using Papertrail logging
- `PASSPORT_*` - Only if using Laravel Passport OAuth

## 🔧 Additional Setup Steps

After migrations run successfully, you may also need to:

1. **Create storage link** (if using public storage):
   ```bash
   railway run php artisan storage:link
   ```

2. **Clear and cache config** (already in nixpacks.toml):
   ```bash
   railway run php artisan config:cache
   railway run php artisan route:cache
   railway run php artisan view:cache
   ```

3. **Run seeders** (if needed):
   ```bash
   railway run php artisan db:seed --force
   ```

## 🔒 Fixing Mixed Content (HTTPS) Errors

If you see "Mixed Content" errors (HTTPS page loading HTTP assets), the code has been updated to:
- Trust Railway's proxy headers (TrustProxies middleware)
- Force HTTPS URLs in production (AppServiceProvider)

**The nixpacks.toml now automatically clears and rebuilds cache on each deploy.**

If you still see errors after deployment, manually clear the cache:
```bash
railway run php artisan config:clear
railway run php artisan route:clear
railway run php artisan view:clear
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
```

**Important:** 
1. Make sure your `APP_URL` in Railway is set to `https://essu-human-resource.up.railway.app` (with HTTPS, not HTTP).
2. **Add `ASSET_URL` variable** in Railway set to the same HTTPS URL: `https://essu-human-resource.up.railway.app`
   - This ensures Vite assets are generated with HTTPS URLs
   - Go to Railway Dashboard → Variables → Add `ASSET_URL` with value `https://essu-human-resource.up.railway.app`

## 🐛 Troubleshooting

If you still get a 500 error after running migrations:

1. **Check Railway logs** - Look for the actual PHP error message
2. **Verify database connection** - Test with: `railway run php artisan tinker` then `DB::connection()->getPdo();`
3. **Check storage permissions** - May need to ensure storage directories are writable
4. **Clear all caches**:
   ```bash
   railway run php artisan cache:clear
   railway run php artisan config:clear
   railway run php artisan route:clear
   railway run php artisan view:clear
   ```

