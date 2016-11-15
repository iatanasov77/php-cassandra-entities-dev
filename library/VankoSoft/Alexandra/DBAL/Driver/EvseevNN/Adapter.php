<?php

namespace VankoSoft\Alexandra\DBAL\Driver\EvseevNN;

use evseevnn\Cassandra\Database;

use VankoSoft\Alexandra\DBAL\AdapterInterface;

class Adapter implements AdapterInterface
{
	/**
	 * @var	\evseevnn\Cassandra\Database $db
	 */
	protected $db;
	
	public function __construct( array $config )
	{
		$this->db	= new Database( $config['contact_points'], $config['keyspace'] );
		$this->db->connect();
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function close()
	{
		$this->db->disconnect();
	}
	
	public function query( $cql, array $params = array(), array $options = array() )
	{
		return $this->db->query( $cql, $params );
	}
	
	public function schema()
	{
		return $this->db->schema()->keyspace("vs_dev");
	}
}
