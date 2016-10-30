<?php

namespace VankoSoft\Cassandra\DBAL\Driver\DataStax;

use VankoSoft\Cassandra\DBAL\Driver\ConnectionInterface;

class Adapter implements ConnectionInterface
{
	protected $driver;
	
	public function __construct( $driver )
	{
		$this->driver	= $driver;
	}
}
