<?php

namespace VankoSoft\Alexandra\DBAL\Driver\DataStax;

use VankoSoft\Alexandra\DBAL\AdapterInterface;

class Adapter implements AdapterInterface
{
	/**
	 * @var	\Alexandra\Session $db
	 */
	protected $db;
	
	public function __construct( array $config )
	{
		$cluster	= \Cassandra::cluster()	->withContactPoints( join( ',', $config['contact_points'] ) )
											->withPort( $config['port'] )
											->build();
		
		$this->db	= $cluster->connect( $config['keyspace'] );
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function close()
	{
		$this->db->close();
	}
	
	public function query( $cql, array $params = array(), array $options = array() )
	{
		$statement	= new \Cassandra\SimpleStatement( $cql );
		
		return $this->db->execute( $statement );
	}
	
	public function schema()
	{
		return $this->db->schema()->keyspace("vs_dev");
	}
}
