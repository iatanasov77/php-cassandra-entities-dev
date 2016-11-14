<?php

namespace VankoSoft\Alexandra\DBAL\Adapter;

use VankoSoft\Common\Service\ServiceFactoryInterface;

use VankoSoft\Alexandra\DBAL\Adapter\DataStax\Adapter as DataStaxAdapter;
use VankoSoft\Alexandra\DBAL\Adapter\PDO\Adapter as PdoAdapter;

class AdapterFactory implements ServiceFactoryInterface
{
	public static function create( array $params )
	{
		$config	= new AdapterConfig( $params );
		
		switch ( $config->driver() )
		{
			case 'datastax':
				return new DataStaxAdapter( $config );
			case 'pdo':
				return new PdoAdapter( $config );
			default:
				throw new \Exception( 'Unkonwn cassandra driver.' );
		}
	}
}