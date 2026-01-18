#!/bin/bash
set -e

# Determine script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(dirname "$(dirname "$SCRIPT_DIR")")"

cd "$REPO_ROOT"

# Check if Docker is running
if ! docker compose ps wiki 2>/dev/null | grep -q "Up"; then
    echo "ERROR: Docker wiki container is not running."
    echo "Run ./tests/scripts/reinstall_test_env.sh first."
    exit 1
fi

# Run PHPUnit inside container using vendor/bin/phpunit (available in dev image)
docker compose exec -T wiki /var/www/html/vendor/bin/phpunit \
    --configuration /mw-user-extensions/LabkiPackManager/tests/phpunit/suite.xml \
    "$@"
