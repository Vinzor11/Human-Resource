# Running Migrations and Seeders on Railway

## ✅ Recommended: Using Railway Dashboard Terminal

1. Go to [Railway Dashboard](https://railway.app)
2. Select your project: **hr2**
3. Click on your service: **Human-Resource**
4. Go to the **Deployments** tab
5. Click on the latest deployment (or any running deployment)
6. Click **View Logs** or **Open Terminal**
7. In the terminal, run:
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=SuperAdminSeeder --force
   ```

## Alternative: Using Railway CLI (if shell works)

If `railway shell` works properly:

```bash
railway shell
# Then inside the shell:
php artisan migrate --force
php artisan db:seed --class=SuperAdminSeeder --force
exit
```

## Alternative: One-time Deploy Command

1. Go to Railway Dashboard → Your Service → **Settings** → **Deploy**
2. Temporarily change the **Start Command** to:
   ```
   php artisan migrate --force && php artisan db:seed --class=SuperAdminSeeder --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
3. Click **Redeploy**
4. After it completes, change it back to the original command

## SuperAdmin Account Details

After running the seeder, you'll have:

- **Email:** `superadmin@example.com`
- **Password:** `password`
- **Role:** Super Admin (with all permissions)

⚠️ **IMPORTANT:** Change the password immediately after first login!

## Option 2: Using Railway Dashboard

1. Go to your Railway project dashboard
2. Click on your service
3. Go to **Settings** → **Deploy**
4. Add a **Deploy Command**:
   ```
   php artisan migrate --force && php artisan db:seed --class=SuperAdminSeeder --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
5. Redeploy your service

## Option 3: One-time Command via Railway Dashboard

1. Go to your Railway project
2. Click on your service
3. Go to **Deployments** tab
4. Click **New Deployment** → **Run Command**
5. Enter: `php artisan migrate --force && php artisan db:seed --class=SuperAdminSeeder --force`

