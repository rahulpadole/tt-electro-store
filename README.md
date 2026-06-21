# TT Electro Store — PHP + MySQL Edition

A complete e-commerce platform for electronics makers. Built in plain PHP with MySQL — no Node.js, no build step. Ready for Hostinger Premium shared hosting.

## Requirements

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.3+
- Apache with `mod_rewrite` enabled (default on Hostinger)

---

## Hostinger Deployment

### Step 1 — Create MySQL Database
In **Hostinger hPanel → MySQL Databases**:
- Create a database (e.g. `u123_ttelectro`)
- Create a user with a strong password
- Grant that user **all privileges** on the database

### Step 2 — Import the database
1. Open **phpMyAdmin** from hPanel
2. Select your database
3. Click **Import** → choose `database/schema.sql` → click **Go**
4. *(Optional)* Import `database/seed.sql` the same way to load 25 demo products, 3 DIY kits, 3 blog posts, and 3 offers so the store looks full from day one

### Step 3 — Configure environment
1. Rename `.env.example` to `.env`
2. Edit `.env` with your database credentials:
   ```
   DB_HOST=localhost
   DB_NAME=your_database_name
   DB_USER=your_db_username
   DB_PASS=your_db_password
   SITE_URL=https://yourdomain.com
   ```

### Step 4 — Upload files to `public_html/`
Upload all files from `php-app/` to your Hostinger `public_html/` via FTP or File Manager.

### Step 5 — Log in to the admin panel
A default admin account is included in `schema.sql`:
- **Email:** `admin@ttelectro.in`
- **Password:** `Admin@123`

Visit `https://yourdomain.com/admin` and log in. **Change your password immediately after first login.**

> Alternatively, you can create a fresh admin account by visiting `setup.php` — delete it from your server once done.

---

## Folder Structure

```
public_html/
├── index.php           — Front controller (all routing)
├── bootstrap.php       — Loads config, DB, helpers, models
├── setup.php           — One-time admin setup (delete after use)
├── .htaccess           — Clean URLs + compression + security headers
├── .env                — Your local config (never commit to git)
├── .env.example        — Template for .env
├── config/             — DB connection, auth helpers, app constants
├── models/             — 18 MySQL PDO model classes
├── api/                — ~40 JSON endpoint handlers (/api/...)
├── views/              — 25 PHP page templates
│   ├── layout/         — Shared header, navbar, footer, admin header
│   ├── admin/          — 14 admin panel views
│   └── partials/       — Reusable partials (product-card, etc.)
├── helpers/            — response, sanitize, validator, pagination
├── assets/             — Static CSS/JS/images
├── storage/
│   └── uploads/        — User-uploaded files (must be writable)
└── database/
    └── schema.sql      — Complete MySQL schema + seed data
```

---

## Included Features

- Full product catalog with categories, brands, images, specs, filters
- Shopping cart (guest + logged-in users)
- Wishlist
- Checkout with COD / UPI / Card / Net Banking
- Order tracking with status timeline
- User dashboard (orders, profile)
- Admin panel (products, orders, blogs, categories, banners, coupons, FAQs, etc.)
- Blog system with slug URLs and reading time
- DIY Kits section
- 3D Printing quote request form
- Coupon codes: `TTFIRST` (10% off), `MAKER20` (20% off), `FLAT150` (₹150 flat)
- Flash sale countdown timer
- Dark mode (default)
- WhatsApp float button
- CSRF protection, XSS sanitization, prepared statements
