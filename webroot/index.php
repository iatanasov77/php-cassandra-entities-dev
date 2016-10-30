<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once  __DIR__ . '/../vendor/autoload.php';

use VankoSoft\LibraryDev\ServiceContainer;

$sc = new ServiceContainer();

$blogRepo = $sc->get( 'entity_manager' )->get( 'Main::Blog' );
$blog     = $blogRepo->findOne( 'id' );

