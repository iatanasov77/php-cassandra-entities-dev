<?php

namespace VankoSoft\Cassandra\DBAL\Driver\DataStax;

use VankoSoft\Cassandra\DBAL\Driver\DriverConfig;

class Driver
{
	private $session;
	
	public function connect( DriverConfig $config )
	{
		$cluster		= \Cassandra::cluster()
							->withContactPoints( join( ',', $config->getContactPoints() ) )
							->withPort( $config->getPort() )
							->build();
		
		$this->session	= $cluster->connect( $config->getKeyspace() );
	}
	
}