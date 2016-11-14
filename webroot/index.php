<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once  __DIR__ . '/../vendor/autoload.php';

use VankoSoft\Common\Application\Kernel;
use VankoSoft\Alexandra\CONF\Config;
use VankoSoft\Alexandra\DBAL\Adapter\AdapterFactory;

$kernel = new Kernel( dirname( dirname( __FILE__ ) ) );

$config	= new Config( $kernel->getConfigPath() );
$db		= AdapterFactory::create( $config->get( 'cassandra.adapter' ) );


//echo "<pre>"; var_dump( $db->schema() ); die;
$db->query( "INSERT INTO products ( product_id, title, qty, price, categories ) VALUES ( 1, 'product1', 30, 10, { 1,3 } )" );
$db->query( "INSERT INTO products ( product_id, title, qty, price, categories ) VALUES ( 2, 'product2', 12, 10, { 1,3 } )" );
$db->query( "INSERT INTO products ( product_id, title, qty, price, categories ) VALUES ( 3, 'product3', 32, 10, { 1,3 } )" );
$db->query( "INSERT INTO products ( product_id, title, qty, price, categories ) VALUES ( 4, 'product4', 45, 10, { 1,3 } )" );

// Test Adapter
//echo "<pre>"; var_dump( $db->query( 'select * from products' ) ); die;

// Test Hydrator
$hydrator	= new DataStaxEntityHydrator();
$product	= new Product();
$result		= $db->query( 'select * from products' );
echo "<pre>"; var_dump( $hydrator->hydrate( $product, $result ) ); die;

$blogRepo = $kernel	->getServiceContainer()
					->get( 'entity_manager' )
					->getRepository( 'Main::Blog' );

$blog     = $blogRepo->findOne( 'id' );
