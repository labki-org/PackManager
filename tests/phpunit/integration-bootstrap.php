<?php

/**
 * PHPUnit integration bootstrap for LabkiPackManager.
 *
 * This file bootstraps the MediaWiki testing environment for integration tests.
 * It loads MediaWiki's test bootstrap which provides access to the full MW
 * environment including database and all services.
 *
 * Usage: Run from within Docker container (using labki-platform:latest-dev image):
 *   /var/www/html/vendor/bin/phpunit \
 *       --configuration /mw-user-extensions/LabkiPackManager/tests/phpunit/suite.xml
 */

// Load MediaWiki's test bootstrap
$mwTestBootstrap = '/var/www/html/tests/phpunit/bootstrap.php';

if ( !file_exists( $mwTestBootstrap ) ) {
	echo "ERROR: MediaWiki test bootstrap not found at: $mwTestBootstrap\n";
	echo "This bootstrap must be run from within the Docker container.\n";
	exit( 1 );
}

require_once $mwTestBootstrap;

// Define that we're in test mode
if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	define( 'MW_PHPUNIT_TEST', true );
}

// Ensure LabkiPackManager extension is loaded
if ( !class_exists( 'LabkiPackManager\Services\LabkiPackManager' ) ) {
	echo "WARNING: LabkiPackManager extension classes not loaded.\n";
	echo "Ensure the extension is properly registered in LocalSettings.php\n";
}
