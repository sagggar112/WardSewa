# 🚀 How to Host WardSewa on Render (Complete Guide)

This guide walks you through deploying the **WardSewa (वार्डसेवा)** platform to **Render** using Docker and Render's managed PostgreSQL database.

---

## 📋 Prerequisites
1. A **[Render.com](https://render.com)** account (free).
2. A **[GitHub](https://github.com)** account.
3. **Git** installed on your computer.

---

## ⚡ Step 1: Push Code to GitHub

Open a terminal in your `WardSewa` project directory and run:

```bash
# 1. Initialize git (if not already done)
git init

# 2. Add all files to git
git add .

# 3. Create your initial commit
git commit -m "feat: setup WardSewa with Docker and Render deployment configuration"

# 4. Rename main branch
git branch -M main

# 5. Connect to your GitHub repository (replace with your repo URL)
git remote add origin https://github.com/YOUR_GITHUB_USERNAME/WardSewa.git

# 6. Push to GitHub
git push -u origin main
```

---

## 🛠️ Step 2: Deploy to Render (Two Methods)

### Method A: 1-Click Blueprint Deployment (Recommended)
This repository includes a `render.yaml` file that automatically provisions both the **Web Service** and the **PostgreSQL Database** together.

1. Go to your **[Render Dashboard](https://dashboard.render.com)**.
2. Click **New +** at the top right and select **Blueprint**.
3. Connect your GitHub account and select your **`WardSewa`** repository.
4. Render will detect `render.yaml` and show:
   - **`wardsewa-web`** (Web Service — Docker)
   - **`wardsewa-db`** (Database — PostgreSQL)
5. Click **Apply**.
6. Render will automatically:
   - Provision the PostgreSQL database.
   - Build the Docker container (compiling Vite assets and installing PHP dependencies).
   - Wire database credentials automatically.
   - Run migrations and database seeders (`SEED_DATABASE=true`).
   - Start the Apache web server.

---

### Method B: Manual Setup via Render Dashboard
If you prefer creating services manually:

#### 1. Create PostgreSQL Database
1. Click **New +** &rarr; **PostgreSQL**.
2. Set Name: `wardsewa-db`.
3. Database Name: `wardsewa`.
4. User: `wardsewa_user`.
5. Region: Choose the region closest to you (e.g., `Singapore`).
6. Plan: `Free`.
7. Click **Create Database**.
8. Note the **Internal Database URL** from the database dashboard.

#### 2. Create Docker Web Service
1. Click **New +** &rarr; **Web Service**.
2. Select your `WardSewa` repository.
3. Choose **Docker** as the Runtime.
4. Set Name: `wardsewa-web`.
5. Region: Same as your database (`Singapore`).
6. Plan: `Free`.
7. Under **Environment Variables**, add the following:

| Key | Value | Notes |
| :--- | :--- | :--- |
| `APP_NAME` | `WardSewa` | Application name |
| `APP_ENV` | `production` | Production environment |
| `APP_DEBUG` | `false` | Security: turn off debug display |
| `APP_KEY` | `base64:v4d2yXmQkZ6tO7pA9sL3wR1eY8uI0oP5aB2cE4gH6jM=` | Or generate via `php artisan key:generate --show` |
| `APP_URL` | `https://your-service-name.onrender.com` | Your live Render service URL |
| `APP_TIMEZONE` | `Asia/Kathmandu` | Nepal Standard Time |
| `APP_LOCALE` | `ne` | Default language (Nepali) |
| `APP_FALLBACK_LOCALE` | `en` | Fallback language (English) |
| `DB_CONNECTION` | `pgsql` | PostgreSQL connection |
| `DB_HOST` | *(From your Render DB)* | Internal DB Host |
| `DB_PORT` | `5432` | PostgreSQL Port |
| `DB_DATABASE` | `wardsewa` | Database Name |
| `DB_USERNAME` | `wardsewa_user` | Database User |
| `DB_PASSWORD` | *(From your Render DB)* | Database Password |
| `RUN_MIGRATIONS` | `true` | Automatically runs `php artisan migrate --force` |
| `SEED_DATABASE` | `true` | Seeds Ward 32, demo accounts, and service types |
| `SESSION_DRIVER` | `database` | Stores user sessions in database |
| `QUEUE_CONNECTION`| `database` | Database queue worker |
| `CACHE_STORE` | `database` | Cache stored in DB |
| `FILESYSTEM_DISK` | `public` | Local public disk |
| `LOG_CHANNEL` | `stderr` | Logs sent directly to Render console |
| `LOG_LEVEL` | `error` | Log level |
| `KHALTI_PUBLIC_KEY` | `test_public_key_dc74e0fd57cb46cd93832aee0a505b8e` | Khalti Test Key |
| `KHALTI_SECRET_KEY` | `test_secret_key_f59e8b7d18b24b9993e168c81884c55b` | Khalti Secret Key |
| `KHALTI_BASE_URL` | `https://a.khalti.com/api/v2/` | Khalti v2 API |

8. Set **Health Check Path** to: `/up`
9. Click **Create Web Service**.

---

## 🔑 Step 3: Access Your Live Application

Once the deployment status turns green (**Live**):
1. Click the Render URL (e.g. `https://wardsewa-web.onrender.com`).
2. Update the `APP_URL` environment variable in your Render dashboard to match this exact URL.

---

## 👤 Test Accounts on Render

### Citizen Portal (`/citizen/login`)
- Enter any 10-digit mobile number (e.g. `9841234567`).
- In demo/sandbox mode, the 6-digit OTP code will display in the amber notification banner at the top of the screen.

### Staff Portal (`/staff/login`)
| Role | Email | Password |
| :--- | :--- | :--- |
| **Ward Chair (वडा अध्यक्ष)** | `chair@ward32.gov.np` | `password123` |
| **Ward Secretary (वडा सचिव)** | `secretary@ward32.gov.np` | `password123` |
| **Front Desk Clerk (वडा सहायक)** | `clerk@ward32.gov.np` | `password123` |
| **Palika Admin (पालिका आईटी)** | `admin@kathmandu.gov.np` | `password123` |

---

## 💡 Troubleshooting & Render Shell

If you need to run Artisan commands on your live Render service:
1. In your Render Dashboard, open **`wardsewa-web`**.
2. Click the **Shell** tab on the left.
3. You can execute any command directly:
   ```bash
   # Re-run seeders
   php artisan db:seed --force

   # Clear cache
   php artisan optimize:clear

   # View route list
   php artisan route:list
   ```
