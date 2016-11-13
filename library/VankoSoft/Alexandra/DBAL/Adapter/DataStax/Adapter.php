<?php

namespace VankoSoft\Alexandra\DBAL\Driver\DataStax;

use VankoSoft\Alexandra\DBAL\AdapterInterface;
use VankoSoft\Alexandra\DBAL\AdapterConfigInterface;

class Adapter implements AdapterInterface
{
	/**
	 * @var	\Alexandra\Session $session
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
		$statement	= new \Cassandra\SimpleStatement( $cql );
		$rows		= $this->session->execute( $statement );
		foreach ( $rows as $row )
		{
			var_dump( $row );
		}
		return count( $rows );
		return new Statement( $rows );
	}
	
	public function schema()
	{
		return $this->session->schema()->keyspace("vs_dev");
	}
}
