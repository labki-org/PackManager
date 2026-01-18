<?php
// Server Config
$wgServer = 'http://localhost:8891';

// Cache directory - required by LabkiPackManager for Git repo storage
$wgCacheDirectory = "$IP/cache";

// Load LabkiPackManager (platform extensions like Mermaid are auto-loaded)
wfLoadExtension( 'LabkiPackManager', '/mw-user-extensions/LabkiPackManager/extension.json' );

// Enable DB Viewer for debugging
$wgLabkiEnableDBViewer = true;

// Configuration
$wgShowExceptionDetails = true;
$wgDebugDumpSql = false;

// Job queue - disable execution on web requests (jobs run via jobrunner service)
$wgJobRunRate = 0;

// Skins
wfLoadSkin( 'Vector' );
$wgDefaultSkin = 'vector';
