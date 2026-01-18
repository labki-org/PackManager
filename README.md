LabkiPackManager
================

MediaWiki extension to import Labki content packs stored as `.wiki` page files from a Git repository.

- MediaWiki: 1.44+
- Content format: `.wiki` files (not XML)
- Deployment: Developed in this repo, cloned into your Docker-based MediaWiki platform

Installation
------------

1. Clone into MediaWiki `extensions/` (in Docker build or bind mount):

```bash
cd /var/www/html/extensions
git clone https://github.com/Aharoni-Lab/LabkiPackManager.git LabkiPackManager
```

2. Enable in `LocalSettings.php`:

```php
wfLoadExtension( 'LabkiPackManager' );
```

Quick setup/reset (Docker)
--------------------------

This extension uses docker-compose with the labki-platform image for local development:

```bash
# Start fresh environment (or reset existing one)
./tests/scripts/reinstall_test_env.sh
```

Then:

- Open http://localhost:8891 to use the wiki
- Login with Admin / dockerpass

Run tests:
```bash
# Integration tests (requires running environment)
./tests/scripts/run-integration-tests.sh

# Unit tests only
./tests/scripts/run-integration-tests.sh --testsuite unit
```

Useful commands:
```bash
docker compose logs -f wiki          # Follow wiki logs
docker compose logs -f jobrunner     # Follow jobrunner logs
docker compose exec wiki bash        # Shell into wiki container
docker compose down -v               # Stop and remove all data
```

3. Configure content sources (raw file hosts):

```php
$wgLabkiContentSources = [
    'https://raw.githubusercontent.com/Aharoni-Lab/labki-packs/main/manifest.yml',
    // Add more sources as needed
    'https://raw.githubusercontent.com/YourOrg/custom-packs/main/manifest.yml',
];
```

4. Ensure your admin role (`sysop`) has the `labkipackmanager-manage` right (default provided by the extension).

5. Install PHP dependencies (YAML parser):

```bash
cd extensions/LabkiPackManager
composer install --no-dev --prefer-dist --no-progress --no-interaction
```

Usage
-----

- Visit `Special:LabkiPackManager` as an admin
- The extension will fetch a YAML `manifest.yml` from the selected source, parse available packs, and list them for selection
- Each selected pack corresponds to a folder under `packs/<id>/` with its own `manifest.yml` and a `pages/` directory containing `.wiki` files whose names are the page titles

Mermaid graph requirement
-------------------------

This extension renders a small live dependency graph (Mermaid). To enable it:

1) Install and enable the MediaWiki Mermaid extension (recommended):

```php
wfLoadExtension( 'Mermaid' );
```

2) Alternatively (dev-only), the UI will lazy-load Mermaid from a CDN for the graph panel. For production wikis, prefer installing the Mermaid extension to avoid external requests and to align with CSP.

Configuration
-------------

Add or override options in LocalSettings.php:

```php
// Content sources: array of manifest URLs
$wgLabkiContentSources = [
    'https://raw.githubusercontent.com/Aharoni-Lab/labki-packs/main/manifest.yml',
    // ...
];

// Default branch/tag hint for sources that support branches
$wgLabkiDefaultBranch = 'main';

// Cache TTL (seconds) for fetched manifests
$wgLabkiCacheTTL = 300;

// Manifest schema index (for validation) and its cache TTL
$wgLabkiSchemaIndexUrl = 'https://raw.githubusercontent.com/Aharoni-Lab/labki-packs-tools/main/schema/index.json';
$wgLabkiSchemaCacheTTL = 300;

// Optional global prefix for collision avoidance during plan/rename
// If set, pages that would otherwise collide are renamed using this prefix
// Namespaced pages keep their namespace and get "Prefix/Subpage"; Main namespace uses
// a real namespace if the prefix matches one, otherwise "Prefix:Title"
$wgLabkiGlobalPrefix = '';
```

Notes:
- Namespaced content (Template:, Form:, Module:, etc.) keeps its namespace when applying global prefix (e.g., Template:PackX/Page).
- If you want all colliding pages moved into a dedicated namespace, create/register that namespace and set `$wgLabkiGlobalPrefix` to its canonical name.

 

This demo script renders a simple HTML preview of the packs list using internal logic. For full functionality, use `Special:LabkiPackManager` inside MediaWiki.

Development
-----------

- Namespace: `LabkiPackManager\`
- Special page: `Special:LabkiPackManager`
- Strings and aliases: `i18n/`

Testing
-------

Use the docker-compose environment (see Quick setup above):

```bash
# Run all tests (unit + integration)
./tests/scripts/run-integration-tests.sh

# Run only unit tests
./tests/scripts/run-integration-tests.sh --testsuite unit

# Run only integration tests
./tests/scripts/run-integration-tests.sh --testsuite integration
```

### Backend Development

For PHP development with full IDE support (autocomplete, type hints, etc.):

```bash
# Install all dependencies including MediaWiki core
composer install
```

**First-time setup:**

If Composer asks about plugins, allow them:

```bash
# Allow MediaWiki's composer merge plugin
composer config --no-plugins allow-plugins.wikimedia/composer-merge-plugin true

# Allow PHP CodeSniffer installer
composer config --no-plugins allow-plugins.dealerdirect/phpcodesniffer-composer-installer true

# Then retry installation
composer install
```

If you're missing PHP extensions (like `ext-dom`), you can ignore platform requirements since we're only using MediaWiki core for IDE support:

```bash
composer install --ignore-platform-reqs
```

**Development commands:**

```bash
# Lint PHP code
composer run lint

# Auto-fix PHP code style
composer run fix
```

**IDE Configuration:**

MediaWiki core is installed in `vendor/mediawiki/core/` as a dev dependency, giving your IDE complete understanding of all MediaWiki classes and functions (e.g., `Title`, `User`, `ApiBase`, `wfMessage()`, etc.).

This is better than manual stubs and is the recommended approach for MediaWiki extension development.

Labki content repo expectations
-------------------------------

- Root `manifest.yml` lists packs with `id`, `path`, `version`, `description`
- Each pack has `packs/<id>/manifest.yml` with `name`, `id`, `version`, `description`, `dependencies`, and `contents`
- Pages live under `packs/<id>/pages/` as `.wiki` files (e.g., `Template:Publication.wiki`, `Form:Publication.wiki`)

License
-------

 EUPL-1.2
