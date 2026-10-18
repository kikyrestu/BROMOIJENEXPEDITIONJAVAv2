#!/bin/bash

# Bromo Ijen Package Import Script
# Run this inside bromoijen_db container, or via docker exec

set -e

DB_USER="${DB_USERNAME:-laravel}"
DB_PASS="${DB_PASSWORD:-secret}"
DB_NAME="${DB_DATABASE:-laravel}"
SQL_FILE="/var/www/html/database_export.sql"

if [ ! -f "$SQL_FILE" ]; then
  echo "❌ ERROR: SQL file not found at $SQL_FILE"
  echo "💡 Make sure you're running this from the Laravel app volume (mounted at /var/www/html)"
  exit 1
fi

echo "📦 Importing packages into database '$DB_NAME'..."

# Import with --force to skip errors (e.g., duplicate INSERTs)
mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE" --force

echo "✅ Success! Packages imported."
echo "💡 Tip: Run 'docker-compose up -d' first if containers aren't running."
