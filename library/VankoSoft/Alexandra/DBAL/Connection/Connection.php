<?php

namespace VankoSoft\Alexandra\DBAL\Connection;

use VankoSoft\Alexandra\DBAL\ConnectionInterface;
use VankoSoft\Alexandra\DBAL\AdapterInterface;

use VankoSoft\Alexandra\DBAL\Driver\DataStax\Adapter as DataStaxAdapter;
use VankoSoft\Alexandra\DBAL\Driver\PDO\Adapter as PdoAdapter;
use VankoSoft\Alexandra\DBAL\Driver\EvseevNN\Adapter as EvseevAdapter;

use VankoSoft\Alexandra\DBAL\Exception\ConnectionException;

class Connection implements ConnectionInterface
{
	const DEFAULT_CONNECTION	= 'default';
	
	private $defaultConnection;
	
	private $connections;
	
	private $config;
	
	public function __construct( array $config )
	{
		$this->config				= $config;
		$this->connections			= array();
		$this->defaultConnection	= self::DEFAULT_CONNECTION;
	}
	
	public function __destruct()
	{
		foreach ( $this->connections as $conn )
		{
			$conn->close();
		}
	}
	
	public function setDefaultConnection( $connectionName )
	{
		$this->defaultConnection	= $connectionName;
	}
	
	public function close( $connectionName )
	{
		if ( $connectionName == $this->defaultConnection )
		{
			throw new ConnectionException( 'Cannot desconnect from default connection!' );
		}
		
		$this->connections[$connectionName]->close();
	}
	
	public function get( $connectionName = null )
	{
		$connectionName	= $connectionName ?: $this->defaultConnection;
		
		if ( ! isset( $this->connections[$connectionName] ) && isset( $this->config[$connectionName] ) )
		{
			$this->connections[$connectionName]	= $this->connect( $this->config[$connectionName] );
		}
		
		if ( ! isset( $this->connections[$connectionName] ) )
		{
			throw new ConnectionException( 'Cannot connect to database!' );
		}
		
		return $this->connections[$connectionName];
	}
	
	public function set( $connectionName, AdapterInterface $adapter )
	{
		$this->connections[$connectionName]	= $adapter;
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @brief	Create a driver connection interface and set connection into connections container
	 * 
	 * @param 	array $config
	 * 
	 * @throws 	\Exception
	 * 
	 * @return 	AdapterInterface
	 */
	protected function connect( array $config )
	{
		switch ( $config['driver'] )
		{
			case Driver::DATASTAX:
				$adapter	= new DataStaxAdapter( $config );
				
				break;
			case Driver::PDO:
				$adapter	= new PdoAdapter( $config );
				
				break;
			case Driver::EVSEEVNN:
				$adapter	= new EvseevAdapter( $config );
				
				break;
			default:
				throw new \Exception( 'Unkonwn cassandra driver.' );
		}
		
		if ( ! $adapter instanceof AdapterInterface )
		{
			throw new \Exception( 'Adapter should be an instance of "AdapterInterface".' );
		}
		
		return $adapter;
	}
}
