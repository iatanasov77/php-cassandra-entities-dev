<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once  __DIR__ . '/../vendor/autoload.php';

use VankoSoft\Common\Application\Kernel;
use VankoSoft\Alexandra\CONF\Config;
use VankoSoft\Alexandra\DBAL\Connection\Connection;
use VankoSoft\Alexandra\DBAL\TableGateway\TableGateway;
use VankoSoft\Alexandra\ODM\Hydrator\DataStax\EntityHydrator as DataStaxEntityHydrator;
use VankoSoft\AlexandraDev\Model\Entity\Product;

$kernel 	= new Kernel( dirname( dirname( __FILE__ ) ) );

$config		= new Config( $kernel->getConfigPath() . 'vankosoft/alexandra' );

// Instatiate connection factory and get default connection
$connection	= new Connection( $config->get( 'connection' ) );
$connection->setDefaultConnection( 'evseevnn' );

$db			= $connection->get();

//echo "<pre>"; var_dump( $db->schema() ); die;
$cql	= 'INSERT INTO products ( product_id, title, qty, price, categories ) VALUES ( :product_id, :title, :qty, :price, :categories )';
$db->query( $cql, array( 1, 'product1', 30, 10, array( 1,3 ) ) );
$db->query( $cql, array( 2, 'product2', 12, 10, array( 1,3 ) ) );
$db->query( $cql, array( 3, 'product3', 32, 10, array( 1,3 ) ) );
$db->query( $cql, array( 4, 'product4', 45, 10, array( 1,3 ) ) );

// Test Adapter
//echo "<pre>"; var_dump( $db->query( 'select * from products' ) ); die;


// Test Table Gateway
$gw	= new TableGateway( 'products', $config->get( 'schema.products' ), $db );
echo '<pre>'; var_dump( $gw->select() ); die;

// Test Hydrator
$hydrator	= new DataStaxEntityHydrator();

$result		= $db->query( 'select * from products' );

$products	= array();
foreach ( $result as $row )
{
	$product	= new Product();
	$hydrator->hydrate( $product, $row );
	$products[]	= $product;
}

echo "<pre>"; var_dump( $products ); die;

$blogRepo = $kernel	->getServiceContainer()
					->get( 'entity_manager' )
					->getRepository( 'Main::Blog' );

$blog     = $blogRepo->findOne( 'id' );
