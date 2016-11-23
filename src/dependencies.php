<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c)
{
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) 
{
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['location']		= function ( $c )
{
	return new \VankoSoft\Common\Application\Location( dirname( dirname( __FILE__ ) ) );
};

$container['config']		= function ( $c )
{
	return new Noodlehaus\Config( $c['location']->getConfigPath() );
};

$container['repository']	= function ( $c )
{
	$config	= new Noodlehaus\Config( 
					$c['location']->getConfigPath() . 'vankosoft' . DIRECTORY_SEPARATOR . 'alexandra' 
				);
	
	return new VankoSoft\Alexandra\ODM\RepositoryContainer( $config );
};
