<?php

namespace VankoSoft\Alexandra\DBAL\Driver\DataStax;

use VankoSoft\Alexandra\DBAL\AdapterInterface;

class Adapter implements AdapterInterface
{
	const BATCH_LOGGED		= 1;
	const BATCH_UNLOGGED	= 2;
	const BATCH_COUNTER		= 3;
	
	const DEFAULT_BATCH		= 'default';
	
	/**
	 * @var	\Cassandra\Session $db
	 */
	protected $db;
	
	/**
	 * @details	Named batch store
	 * 
	 * @var	array $batch;
	 */
	protected $batch;
	
	public function __construct( array $config )
	{
		$cluster		= \Cassandra::cluster()	->withContactPoints( join( ',', $config['contact_points'] ) )
												->withPort( $config['port'] )
												->build();
		
		$this->db		= $cluster->connect( $config['keyspace'] );
		
		$this->batch	= array();
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
		$statement	= $this->db->prepare( $cql );
		
		return $this->db->execute( $statement );
	}
	
	public function schema()
	{
		return $this->db->schema()->keyspace("vs_dev");
	}
	
	public function beginBatch( $batchType = self::BATCH_UNLOGGED, $batch = self::DEFAULT_BATCH )
	{	
		switch ( $batchType )
		{
			case self::BATCH_LOGGED:
				$this->batch[$batch]	= new \Cassandra\BatchStatement( \Cassandra::BATCH_LOGGED );
				break;
			case self::BATCH_UNLOGGED:
				$this->batch[$batch]	= new \Cassandra\BatchStatement( \Cassandra::BATCH_UNLOGGED );
				break;
			case self::BATCH_COUNTER:
				$this->batch[$batch]	= new \Cassandra\BatchStatement( \Cassandra::BATCH_COUNTER );
				break;
			default:
				throw new \Exception( 'Unknown batch type' );
		}
	}
	
	public function applyBatch( $batch = self::DEFAULT_BATCH )
	{
		$result	= $this->db->execute( $this->batch[$batch] );
		unset( $this->batch[$batch] );
		
		return $result;
	}
	
	public function queryBatch( $cql, array $params, $batch = self::DEFAULT_BATCH )
	{
		$statement	= $this->db->prepare( $cql );
		
		return $this->batch[$batch]->add( $statement, $params );
	}
}
