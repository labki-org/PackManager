#!/bin/bash
set -e

# Determine script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(dirname "$(dirname "$SCRIPT_DIR")")"

cd "$REPO_ROOT"

echo "==> Shutting down existing containers and removing volumes..."
docker compose down -v

echo "==> Starting new environment..."
docker compose up -d

echo "==> Waiting for MW to be ready (giving DB time to init)..."
sleep 20

echo "==> Environment ready!"
echo "Visit http://localhost:8891"
echo ""
echo "Useful commands:"
echo "  docker compose logs -f wiki          # Follow wiki logs"
echo "  docker compose logs -f jobrunner     # Follow jobrunner logs"
echo "  docker compose exec wiki bash        # Shell into wiki container"
