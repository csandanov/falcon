<?php
if (PHP_SAPI == 'cli-server') {
  // To help the built-in PHP dev server, check if the request was actually for
  // something which should probably be served as a static file
  $url = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if (is_file($file)) {
    return FALSE;
  }
}

require __DIR__ . '/../vendor/autoload.php';

// UserFrosting provide good config system.
$config = new \UserFrosting\Config\Config();
$config->setPaths(['../src/config']);

// Load default config.
$config->loadConfigurationFiles();

// Load config file from env name.
if (isset($_ENV['PLATFORM_BRANCH'])) {
  // This will merge any file in the PLATFORM_BRANCH env var with the
  // default.php file and provide a single array.
  // This will not fail if it can't find the file.
  $config->loadConfigurationFiles($_ENV['PLATFORM_BRANCH']);
}
else {
  // Load local config if exists.
  $config->loadConfigurationFiles('local');
}

$app = new \Slim\App((array) $config->all());

// Set up dependencies.
require __DIR__ . '/../src/dependencies.php';

// Register routes.
require __DIR__ . '/../src/routes.php';

// Register middleware.
require __DIR__ . '/../src/middleware.php';

// Run app.
$app->run();
