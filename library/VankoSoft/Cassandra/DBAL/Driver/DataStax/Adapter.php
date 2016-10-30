<?php

namespace VankoSoft\Cassandra\DBAL\Driver\DataStax;

use VankoSoft\Cassandra\DBAL\AdapterInterface;
use VankoSoft\Cassandra\DBAL\AdapterConfigInterface;

class Adapter implements AdapterInterface
{
	/**
	 * @var	\Cassandra\Session $session
	 */
	protected $session;
	
	public function __construct( AdapterConfigInterface $config )
	{
		$this->session	= Driver::connect( $config );
	}
	
	public function __destruct()
	{
		$this->session->close();
	}
	
	public function query( $cql, array $params = array(), array $options = array() )
	{
		$result	= $this->session->exec( $cql );
		
		return new Statement( $result );
	}
}
