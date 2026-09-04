#!/usr/bin/env bash
set -euo pipefail

# Deploy wordiva-blog-theme to WordPress server
# Usage: ./deploy/deploy.sh

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"

# Server configuration
SSH_USER="ubuntu"
SSH_HOST="18.207.51.120"
SSH_KEY="/Users/lijeesh/Documents/lijeesh/summit/projects/getevcars.com/evcar-aws-ed25519"
REMOTE_THEMES_DIR="/var/www/html/wordivablog/wp-content/themes"
THEME_NAME="wordiva-blog-theme"
REMOTE_THEME_PATH="${REMOTE_THEMES_DIR}/${THEME_NAME}"
LOCAL_THEME_PATH="${PROJECT_ROOT}/${THEME_NAME}"
SITE_URL="https://wordiva.ai/blog/"

SSH_OPTS=(
  -o IdentitiesOnly=yes
  -o StrictHostKeyChecking=no
  -i "${SSH_KEY}"
)

log() {
  printf '\n[%s] %s\n' "$(date '+%H:%M:%S')" "$*"
}

die() {
  printf 'Error: %s\n' "$*" >&2
  exit 1
}

preflight() {
  command -v rsync >/dev/null 2>&1 || die "rsync is required but not installed."
  command -v curl >/dev/null 2>&1 || die "curl is required but not installed."

  [[ -f "${SSH_KEY}" ]] || die "SSH key not found: ${SSH_KEY}"
  [[ -d "${LOCAL_THEME_PATH}" ]] || die "Theme directory not found: ${LOCAL_THEME_PATH}"
}

deploy_theme() {
  log "Deploying ${THEME_NAME} to ${SSH_USER}@${SSH_HOST}:${REMOTE_THEME_PATH}"

  ssh "${SSH_OPTS[@]}" "${SSH_USER}@${SSH_HOST}" "sudo mkdir -p '${REMOTE_THEME_PATH}' && sudo chown -R ${SSH_USER}:${SSH_USER} '${REMOTE_THEME_PATH}'"

  rsync -avz --delete \
    --exclude '.DS_Store' \
    --exclude '.git' \
    --exclude '.gitignore' \
    --exclude 'node_modules' \
    -e "ssh ${SSH_OPTS[*]}" \
    "${LOCAL_THEME_PATH}/" \
    "${SSH_USER}@${SSH_HOST}:${REMOTE_THEME_PATH}/"

  ssh "${SSH_OPTS[@]}" "${SSH_USER}@${SSH_HOST}" \
    "sudo chown -R www-data:www-data '${REMOTE_THEME_PATH}' && sudo find '${REMOTE_THEME_PATH}' -type d -exec chmod 755 {} \; && sudo find '${REMOTE_THEME_PATH}' -type f -exec chmod 644 {} \;"

  log "Deployment complete."
}

verify_site() {
  log "Checking ${SITE_URL}"

  local http_code
  http_code="$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 30 "${SITE_URL}")"

  if [[ "${http_code}" =~ ^2 ]]; then
    log "Site check passed (HTTP ${http_code})."
  else
    die "Site check failed (HTTP ${http_code}). Visit ${SITE_URL} manually."
  fi

  local body
  body="$(curl -sS -L --max-time 30 "${SITE_URL}")"

  if grep -Eqi 'wordiva|wordiva-blog-theme' <<< "${body}"; then
    log "Page content looks valid (theme/branding detected)."
  else
    die "Site returned HTTP ${http_code} but expected theme content was not found."
  fi
}

main() {
  preflight
  deploy_theme
  verify_site

  if [[ "${1:-}" == "--verify" ]]; then
    shift
    local phase="${1:-all}"
    log "Running SEO verification phase: ${phase}"
    "${SCRIPT_DIR}/verify-phase.sh" "${phase}"
  fi

  log "All done."
}

main "$@"
