<?php

namespace VankoSoft\Cassandra\DBAL\Driver\DataStax;

use VankoSoft\Cassandra\DBAL\AdapterConfigInterface;

class Driver
{	
	public static function connect( AdapterConfigInterface $config )
	{
		$cluster		= \Cassandra::cluster()
							->withContactPoints( join( ',', $config->getContactPoints() ) )
							->withPort( $config->getPort() )
							->build();
		
		return $cluster->connect( $config->getKeyspace() );
	}
}
