#!/usr/bin/env sh
set -eu

cd "$(dirname "$0")/.."

revision="${1:-origin/main}"
git fetch origin main
git checkout --detach "$revision"
docker compose --env-file .env.production -f compose.prod.yml up -d --build --remove-orphans
docker image prune -f
docker compose --env-file .env.production -f compose.prod.yml ps
