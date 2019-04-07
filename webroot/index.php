<?php
//phpinfo(); die;
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require __DIR__ . '/../vendor/autoload.php';

use Slim\App as SlimApp;
use Noodlehaus\Config as NoodlehausConfig;

use VankoSoft\Common\Application\Location;
use VankoSoft\Alexandra\DBAL\Adapter\Driver\DataStax\Schema;

session_start();


$app 		= new SlimApp();

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
//require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
