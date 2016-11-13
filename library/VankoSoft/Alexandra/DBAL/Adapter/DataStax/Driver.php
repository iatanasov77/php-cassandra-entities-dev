<?php

namespace VankoSoft\Alexandra\DBAL\Driver\DataStax;

use VankoSoft\Alexandra\DBAL\AdapterConfigInterface;

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
