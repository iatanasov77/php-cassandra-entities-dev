<?php

namespace VankoSoft\Alexandra\DBAL\Driver\EvseevNN;

use evseevnn\Cassandra\Database;

use VankoSoft\Alexandra\DBAL\AdapterInterface;

class Adapter implements AdapterInterface
{
	const BATCH_LOGGED		= 1;
	const BATCH_UNLOGGED	= 2;
	const BATCH_COUNTER		= 3;
	
	const DEFAULT_BATCH		= 'default';
	
	/**
	 * @var	\evseevnn\Cassandra\Database $db
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
	
	public function beginBatch( $batchType = self::BATCH_UNLOGGED, $batch = self::DEFAULT_BATCH )
	{
		$this->batch[$batch]	= clone $this->db;
		
		switch ( $batchType )
		{
			case self::BATCH_LOGGED:
				$this->batch[$batch]->beginBatch();
				break;
			case self::BATCH_UNLOGGED:
				$this->batch[$batch]->beginUnloggedBatch();
				break;
			case self::BATCH_COUNTER:
				$this->batch[$batch]->begnCounterBatch();
				break;
			default:
				unset( $this->batch[$batch] );
				throw new \Exception( 'Unknown batch type' );
		}
	}
	
	public function applyBatch( $batch = self::DEFAULT_BATCH )
	{
		$result	= $this->batch[$batch]->applyBatch();
		unset( $this->batch[$batch] );
		
		return $result;
	}
	
	public function queryBatch( $cql, array $params, $batch = self::DEFAULT_BATCH )
	{
		return $this->batch[$batch]->query( $cql, $params );
	}
}
