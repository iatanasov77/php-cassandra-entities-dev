<?php

namespace VankoSoft\Cassandra\ODM;

use Enigma\Library\Database\Cassandra\ResultSet\ExtractorResultSet;

use VankoSoft\Cassandra\ODM\EntityManager;

/**
 * @brief	EntityGateway class play a role of a database gateway.
 * 
 * @details	It cares about loading and persisting of the entities up/to database.
 */
class EntityGateway implements EntityGatewayInterface
{
	const PERSIST_MODE_INSERT	= 'INSERT';
	const PERSIST_MODE_UPDATE	= 'UPDATE';
	const PERSIST_MODE_DELETE	= 'DELETE';
	
	/**
	 *
	 * @var \VankoSoft\Cassandra\ODM\EntityManager $em
	 */
	private $em;
	
	/**
	 * @brief	Initialize Entity Gateway.
	 * 
	 * @param	\VankoSoft\Cassandra\ODM\EntityManager $em
	 * 
	 * @return	void
	 */
	public function __construct( EntityManager $em )
	{
		$this->em	= $em;
	}
	
	/**
	 * @copydoc \VankoSoft\Cassandra\ODM\EntityGatewayInterface::load()
	 */
	public function load( $entityType, array $params, array $options )
	{
		$db				= $this->em->getDbAdapter();
		$params			= $this->em->prepareParams( $entityType, $params );
		$metaConfig		= $this->em->getEntityMetaDataConfig();
		$primaryKeys	= array_keys( $metaConfig->getEntityPrimaryKeys( $entityType ) );
		$entityTables	= $metaConfig->getEntityTablesWithColumnsInfo( $entityType );
		$tableNames		= array_keys( $entityTables );
		$primaryTable	= array_shift( $tableNames );
		$primaryQuery	= $this->buildSelectQuery( $primaryTable, $entityTables[$primaryTable], array_keys( $params ) );
		
		$resultSet		= $db->query( $primaryQuery, $params, new ExtractorResultSet(), $options );
		$entities		= $this->em->fetchEntities(	$resultSet->toArray(), $entityType );
		foreach( $tableNames as $table )
		{
			foreach( $entities as $entity )
			{
				$query		= $this->buildSelectQuery( $table, $entityTables[$table], $primaryKeys );
				$resultSet	= $db->query( $query, $params, new ExtractorResultSet(), $options );
				$this->em->populateEntity( $entity, $resultSet->toArray()[0] );
			}
		}
		
		return $entities;
	}
	
	/**
	 * @copydoc \VankoSoft\Cassandra\ODM\EntityGatewayInterface::persist()
	 */
	public function persist( $entity, $mode )
	{
		$db				= $this->em->getDbAdapter();
		$metaConfig		= $this->em->getEntityMetaDataConfig();
		$entityType		= '\\' . get_class( $entity );
		$entityTables	= $metaConfig->getEntityTables( $entityType );
		
		$batch	= $db->createBatchStatement( BatchType::BATCH_COUNTER );
		foreach ( $entityTables as $tableName )
		{
			$dbData	= $this->em->extractEntity( $entity, $tableName );
			switch ( $mode )
			{
				case self::PERSIST_MODE_INSERT:
					$query	= $this->buildInsertQuery( $tableName, array_keys( $dbData ) );
					break;
				case self::PERSIST_MODE_UPDATE:
					$pkCols	= $metaConfig->getEntityPrimaryKeyColumns( $entityType );
					$query	= $this->buildUpdateQuery( $tableName, array_keys( $dbData ), array_keys( $pkCols ) );
					break;
				case self::PERSIST_MODE_DELETE:
					$pkCols	= $metaConfig->getEntityPrimaryKeyColumns( $entityType );
					$query = $this->buildDeleteQuery( $tableName, array_keys( $dbData ), array_keys( $pkCols ) );
					break;
				default:
					throw new OrmException( 'Invalid Persister Mode.');
			}
			
			$batch->add( $db->createSimpleStatement( $query ), $dbData );
		}
		
		$db->queryStatement( $batch );
	}
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @brief	Build a select query.
	 * 
	 * @param	string $tableName
	 * @param	array $columns
	 * @param	array $keys
	 * 
	 * @return	string
	 */
	protected function buildSelectQuery( $tableName, $columns, $keys = null )
	{
		$query	= sprintf( "SELECT %s FROM %s", implode( ', ', $columns ), $tableName );
		
		if ( is_array( $where ) && ! empty( $where ) )
		{
			$where	=  implode( ' AND ', array_map(
				function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
				$keys,
				$keys
			) );
				
			$query	.= ' WHERE ' .$where;
		}
			
		return $query;
	}

	/**
	 * @brief	Build an insert cql query;
	 * 
	 * @param	string $tableName
	 * @param	array $keys
	 * 
	 * @return	string
	 */
	protected function buildInsertQuery( $tableName, $keys )
	{
		$query	= sprintf( 'INSERT INTO  %s ( %s ) VALUES ( :%s )', 
			$tableName,
			implode( ', ', $keys ), 
			implode( ', :', $keys )
		);
		
		return $query;
	}
	
	/**
	 * @brief	Build an update cql query;
	 * 
	 * @param	string $tableName
	 * @param	array $keys
	 * @param	array $primaryKeys
	 * 
	 * @return	string
	 */
	protected function buildUpdateQuery( $tableName, $keys, $primaryKeys )
	{
		$setKeys		= array_diff( $keys, $primaryKeys );
		
		$setString		= implode(', ', array_map(
			function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
			$setKeys,
			$setKeys
		) );
			
		$whereString	= implode(' AND ', array_map(
			function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
			$primaryKeys,
			$primaryKeys
		) );
		
		$query			= sprintf( 'UPDATE %s SET %s WHERE %s', $tableName, $setString, $whereString );
		
		return $query;
	}
	
	/**
	 * @brief	Build an delete cql query;
	 * 
	 * @param	string $tableName
	 * @param	array $keys
	 * @param	array $primaryKeys
	 * 
	 * @return	string
	 */
	protected function buildDeleteQuery(  $tableName, $primaryKeys )
	{
		$whereString	= implode(' AND ', array_map(
			function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
			$primaryKeys,
			$primaryKeys
		) );
		
		$query			= sprintf( 'DELETE FROM %s WHERE %s', $tableName, $whereString );
		
		return $query;
	}
}
