#!/bin/bash
set -e

# Add MySQL 8.0 binaries to PATH
MYSQL_NIX_PATH="$(ls -d /nix/store/*-mysql-8.0.* 2>/dev/null | head -1)"
if [ -n "$MYSQL_NIX_PATH" ] && [ -d "$MYSQL_NIX_PATH/bin" ]; then
    export PATH="$MYSQL_NIX_PATH/bin:$PATH"
    echo "[start.sh] MySQL found at $MYSQL_NIX_PATH"
else
    echo "[start.sh] WARNING: MySQL nix path not found, trying system PATH"
fi

# Export local DB credentials — these take priority over .env
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_NAME=tt_electro_store
export DB_USER=root
export DB_PASS=

# Use workspace-relative paths so data persists across container restarts
WORKSPACE_DIR="$(cd "$(dirname "$0")" && pwd)"
MYSQL_DATADIR="$WORKSPACE_DIR/mysql-data"
MYSQL_SOCKET="/tmp/mysql.sock"
DB_NAME="tt_electro_store"
DB_INITIALIZED_FLAG="$MYSQL_DATADIR/.db_initialized"

# ── Initialize MySQL data directory if needed ───────────────────────────────
if [ ! -d "$MYSQL_DATADIR/mysql" ]; then
    echo "[start.sh] Initializing MySQL data directory..."
    mkdir -p "$MYSQL_DATADIR"
    mysqld --initialize-insecure --user=runner --datadir="$MYSQL_DATADIR" 2>&1
fi

# ── Kill any stale mysqld process / socket ───────────────────────────────────
pkill -f "mysqld.*$MYSQL_DATADIR" 2>/dev/null || true
rm -f "$MYSQL_SOCKET" "${MYSQL_SOCKET}.lock"
sleep 1

# ── Start MySQL in background ────────────────────────────────────────────────
echo "[start.sh] Starting MySQL..."
mysqld --user=runner \
       --datadir="$MYSQL_DATADIR" \
       --socket="$MYSQL_SOCKET" \
       --port=3306 \
       --daemonize \
       --mysqlx=OFF 2>&1

# ── Wait for MySQL to be ready ───────────────────────────────────────────────
echo "[start.sh] Waiting for MySQL..."
for i in $(seq 1 30); do
    if mysql -u root --socket="$MYSQL_SOCKET" -e "SELECT 1;" > /dev/null 2>&1; then
        echo "[start.sh] MySQL is ready."
        break
    fi
    sleep 1
done

# ── Create database + schema + seed (first run only) ─────────────────────────
if [ ! -f "$DB_INITIALIZED_FLAG" ]; then
    echo "[start.sh] Setting up database for the first time..."
    mysql -u root --socket="$MYSQL_SOCKET" -e \
        "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    mysql -u root --socket="$MYSQL_SOCKET" "$DB_NAME" < "$WORKSPACE_DIR/database/schema.sql"
    mysql -u root --socket="$MYSQL_SOCKET" "$DB_NAME" < "$WORKSPACE_DIR/database/seed.sql"
    touch "$DB_INITIALIZED_FLAG"
    echo "[start.sh] Database initialized."
fi

# ── Always run migrations (idempotent) ───────────────────────────────────────
echo "[start.sh] Running migrations..."
php "$WORKSPACE_DIR/database/migrate.php" 2>&1 \
    && echo "[start.sh] Migrations OK." \
    || echo "[start.sh] Migration script had warnings."

# ── Start PHP server ──────────────────────────────────────────────────────────
echo "[start.sh] Starting PHP server on port 5000..."
exec php -S 0.0.0.0:5000 -t "$WORKSPACE_DIR" "$WORKSPACE_DIR/router.php"
