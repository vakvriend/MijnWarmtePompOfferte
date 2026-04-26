#!/usr/bin/env bash
set -euo pipefail

APP_DIR="$HOME/MijnWarmtePompOfferte"
CONTAINER="wordpress-eao5oq3va188f5po0d0aax79"
THEME_SLUG="vakvriend-warmtepomp-campagne-v2"
PLUGIN_SLUG="vakvriend-chatbot"
TS="$(date +%Y%m%d-%H%M%S)"

echo "== Pull GitHub =="
cd "$APP_DIR"
git fetch origin main
git checkout main
git pull --ff-only origin main
COMMIT="$(git rev-parse --short HEAD)"
echo "Deploying commit $COMMIT"

echo "== Backup current theme/plugin in container =="
sudo docker exec "$CONTAINER" sh -lc "mkdir -p /tmp/vakvriend-deploy-backups/$TS && \
  if [ -d /var/www/html/wp-content/themes/$THEME_SLUG ]; then cp -a /var/www/html/wp-content/themes/$THEME_SLUG /tmp/vakvriend-deploy-backups/$TS/$THEME_SLUG; fi && \
  if [ -d /var/www/html/wp-content/plugins/$PLUGIN_SLUG ]; then cp -a /var/www/html/wp-content/plugins/$PLUGIN_SLUG /tmp/vakvriend-deploy-backups/$TS/$PLUGIN_SLUG; fi"

echo "== Copy theme =="
sudo docker cp "$APP_DIR" "$CONTAINER:/tmp/$THEME_SLUG-new"
sudo docker exec "$CONTAINER" sh -lc "rm -rf /tmp/$THEME_SLUG-new/.git /tmp/$THEME_SLUG-new/.github /tmp/$THEME_SLUG-new/docs /tmp/$THEME_SLUG-new/log /tmp/$THEME_SLUG-new/plugins && \
  rm -rf /var/www/html/wp-content/themes/$THEME_SLUG && \
  mv /tmp/$THEME_SLUG-new /var/www/html/wp-content/themes/$THEME_SLUG"

echo "== Copy plugin =="
sudo docker cp "$APP_DIR/plugins/$PLUGIN_SLUG" "$CONTAINER:/tmp/$PLUGIN_SLUG-new"
sudo docker exec "$CONTAINER" sh -lc "rm -rf /var/www/html/wp-content/plugins/$PLUGIN_SLUG && \
  mv /tmp/$PLUGIN_SLUG-new /var/www/html/wp-content/plugins/$PLUGIN_SLUG"

echo "== Permissions and cache =="
sudo docker exec "$CONTAINER" sh -lc "chown -R www-data:www-data /var/www/html/wp-content/themes/$THEME_SLUG /var/www/html/wp-content/plugins/$PLUGIN_SLUG && \
  find /var/www/html/wp-content/themes/$THEME_SLUG /var/www/html/wp-content/plugins/$PLUGIN_SLUG -type d -exec chmod 755 {} + && \
  find /var/www/html/wp-content/themes/$THEME_SLUG /var/www/html/wp-content/plugins/$PLUGIN_SLUG -type f -exec chmod 644 {} + && \
  cd /var/www/html && php -r 'require \"wp-load.php\"; delete_site_transient(\"wc_github_theme_release\"); delete_site_transient(\"update_themes\"); if (function_exists(\"wp_cache_flush\")) { wp_cache_flush(); } echo \"cache-cleared\\n\";'"

echo "== Versions =="
sudo docker exec "$CONTAINER" sh -lc "grep -E 'Theme Name:|Version:' /var/www/html/wp-content/themes/$THEME_SLUG/style.css && grep -E 'Plugin Name:|Version:' /var/www/html/wp-content/plugins/$PLUGIN_SLUG/$PLUGIN_SLUG.php"
echo "Done. Backup: /tmp/vakvriend-deploy-backups/$TS inside $CONTAINER"
