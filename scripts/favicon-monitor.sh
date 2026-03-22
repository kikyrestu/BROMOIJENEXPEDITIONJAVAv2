#!/usr/bin/env bash
set -euo pipefail

DOMAIN="${1:-https://bromoijenexpeditionjava.com}"
FAVICON_PATH="${2:-/favicon-v2.ico}"
HOMEPAGE_PATH="${3:-/}"
LOG_FILE="${4:-docs/favicon-monitor.csv}"

mkdir -p "$(dirname "$LOG_FILE")"

timestamp="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"

ua="Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)"

favicon_headers="$(curl -sSI -A "$ua" "${DOMAIN}${FAVICON_PATH}" | tr -d '\r')"
home_headers="$(curl -sSI -A "$ua" "${DOMAIN}${HOMEPAGE_PATH}" | tr -d '\r')"
home_html="$(curl -sSL -A "$ua" "${DOMAIN}${HOMEPAGE_PATH}")"

favicon_status="$(printf "%s\n" "$favicon_headers" | awk 'NR==1{print $2}')"
favicon_type="$(printf "%s\n" "$favicon_headers" | awk -F': ' 'tolower($1)=="content-type" {print $2; exit}')"
favicon_cache="$(printf "%s\n" "$favicon_headers" | awk -F': ' 'tolower($1)=="cache-control" {print $2; exit}')"
favicon_lastmod="$(printf "%s\n" "$favicon_headers" | awk -F': ' 'tolower($1)=="last-modified" {print $2; exit}')"

home_status="$(printf "%s\n" "$home_headers" | awk 'NR==1{print $2}')"

icon_declared="no"
icon_regex="<link[^>]+rel=\"(icon|shortcut icon)\"[^>]+href=\"${FAVICON_PATH}\"|<link[^>]+href=\"${FAVICON_PATH}\"[^>]+rel=\"(icon|shortcut icon)\""
if grep -qiE "$icon_regex" <<< "$home_html"; then
  icon_declared="yes"
fi

csv_header="timestamp_utc,domain,favicon_path,favicon_http_status,favicon_content_type,favicon_cache_control,favicon_last_modified,home_http_status,favicon_declared_in_home"
if [[ ! -f "$LOG_FILE" ]]; then
  printf '%s\n' "$csv_header" > "$LOG_FILE"
fi

escape_csv() {
  local value="$1"
  value="${value//\"/\"\"}"
  printf '"%s"' "$value"
}

row="$(escape_csv "$timestamp"),$(escape_csv "$DOMAIN"),$(escape_csv "$FAVICON_PATH"),$(escape_csv "$favicon_status"),$(escape_csv "$favicon_type"),$(escape_csv "$favicon_cache"),$(escape_csv "$favicon_lastmod"),$(escape_csv "$home_status"),$(escape_csv "$icon_declared")"
printf '%s\n' "$row" >> "$LOG_FILE"

printf -- "Favicon monitor check complete\n"
printf -- "- Time (UTC): %s\n" "$timestamp"
printf -- "- Domain: %s\n" "$DOMAIN"
printf -- "- Favicon URL: %s%s\n" "$DOMAIN" "$FAVICON_PATH"
printf -- "- Favicon status/content-type: %s / %s\n" "$favicon_status" "$favicon_type"
printf -- "- Favicon declared in homepage head: %s\n" "$icon_declared"
printf -- "- Log appended to: %s\n" "$LOG_FILE"
