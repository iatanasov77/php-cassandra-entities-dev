<?php

namespace VankoSoft\Alexandra\DBAL\Adapter\DataStax;

use VankoSoft\Alexandra\DBAL\Adapter\AdapterConfigInterface;

class Driver
{	
	public static function connect( AdapterConfigInterface $config )
	{
		$cluster	= \Cassandra::cluster()
						->withContactPoints( join( ',', $config->contactPoints() ) )
						->withPort( $config->port() )
						->build();
		
		return $cluster->connect( $config->keyspace() );
	}
}
