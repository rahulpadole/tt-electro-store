# TT Electro Store

A full-featured e-commerce platform for electronics makers and hobbyists.

## Tech Stack
- **Backend:** PHP 8.2 with custom MVC architecture
- **Database:** MySQL 8.0 (local, socket-based via `/tmp/mysql.sock`)
- **Frontend:** Server-side rendered PHP templates, vanilla CSS/JS, Tailwind CDN
- **Auth:** Custom session-based auth with optional Google OAuth
- **Payments:** Razorpay (optional, requires API keys)

## Running the App
The app starts via `bash start.sh` which:
1. Initializes MySQL data directory (first run)
2. Starts MySQL on port 3306
3. Runs migrations (`database/migrate.php`)
4. Starts PHP dev server on port 5000

## Environment Variables
Set in Replit's shared env:
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` — MySQL connection
- `APP_ENV`, `APP_DEBUG` — App mode
- `JWT_SECRET` — Session/JWT signing key
- `SITE_NAME`, `WHATSAPP_NUMBER` — Store config

## Optional Secrets (set in Replit Secrets)
- `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` — Google OAuth login
- `RAZORPAY_KEY_ID` / `RAZORPAY_KEY_SECRET` — Razorpay payment gateway
- `FAST2SMS_KEY` — SMS OTP delivery

## Structure
- `index.php` — Front controller / router
- `bootstrap.php` — App bootstrap (env, config, models)
- `api/` — JSON API endpoints
- `models/` — PDO-based model classes
- `views/` — PHP templates
- `config/` — Database, auth, app config
- `helpers/` — Utility functions
- `database/` — Schema, seed, migrations
- `storage/uploads/` — User-uploaded files

## User Preferences
- Keep the existing PHP MVC structure
- Preserve all existing routes and API endpoints
