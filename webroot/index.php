<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once  __DIR__ . '/../vendor/autoload.php';

use VankoSoft\Common\Application\Kernel;

$kernel = new Kernel( dirname( dirname( __FILE__ ) ) );

$blogRepo = $kernel	->getServiceContainer()
					->get( 'entity_manager' )
					->getRepository( 'Main::Blog' );

$blog     = $blogRepo->findOne( 'id' );
