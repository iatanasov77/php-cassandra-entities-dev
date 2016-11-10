<?php

namespace VankoSoft\Cassandra\ODM;

use Noodlehaus\Config as NoodlehausConfig;

class Config implements EntityMetaDataConfigInterface
{
	/**
	 * @var \Noodlehaus\Config $config
	 */
	protected $config;
	
	/**
	 * @brief	Class constructor
	 * 
	 * @param	\Noodlehaus\Config $config
	 * 
	 * @return	void
	 */
	public function __construct( NoodlehausConfig $config )
	{
		$this->config	= $config;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getOptions()
	 */
	public function getOptions()
	{
		return $this->config->get( 'options' );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getRepositoryType()
	 */
	public function getRepositoryType( $entityType )
	{
		$entities	= $this->config->get( 'entities' );
		
		switch( true )
		{
			case ( ! isset( $entities[$entityType] ) ):
				throw new OrmException( 'Undefined entity type into the meta data configuration.' );
			case ( ! isset( $entities[$entityType]['repository'] ) ):
				throw new OrmException( 'Missing repository into entity meta data!' );
		}
		
		return  $entities[$entityType]['repository'];
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getEntityTables()
	 */
	public function getEntityTables( $entityType )
	{
		$entities	= $this->config->get( 'entities' );
		
		switch ( true )
		{
			case ( ! is_array( $entities[$entityType] ) ):
				throw new OrmException( 'Undefined entity type into the meta data configuration.' );
			case ( ! is_array( $entities[$entityType]['tables'] ) ):
				throw new OrmException( 'Invalid Entity meta data configuration!' );
		}
		
		return $entities[$entityType]['tables'];
	}

	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getEntityColumns()
	 */
	public function getEntityColumns( $entityType )
	{
		$primaryKeyColumns	= $this->getEntityPrimaryKeyColumns( $entityType );
		$dataColumns		= $this->getAllEntityDataColumns( $entityType );
							
		return array_merge( $primaryKeyColumns, $dataColumns );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getEntityTableColumns()
	 */
	public function getEntityTableColumns(	$entityType, $dbTable )
	{
		$entityTables	= $this->getEntityTables( $entityType );
		$tables			= $this->config->get( 'tables' );
		
		switch ( true )
		{
			case ( ! array_search( $entityTables, $dbTable ) ):
				throw new OrmException( 'Table is not defined for this entity type.' );
			case ( ! isset( $tables[$dbTable] ) ):
				throw new OrmException( 'Missing information for this table!' );
		}
		
		return array_merge( $tables[$dbTable]['partition_key_columns'], $tables[$dbTable]['clustering_columns'], $tables[$dbTable]['data_columns']);
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getEntityPrimaryKeyColumns()
	 */
	public function getEntityPrimaryKeyColumns( $entityType )
	{
		$entities	= $this->config->get( 'entities' );
		$tables		= $this->config->get( 'tables' );
		
		// Check config
		switch ( true )
		{
			case ( ! isset( $entities[$entityType] ) ):
				throw new OrmException( 'There are not Meta Data for this Entity class!' );
			case ( ! isset( $entities[$entityType]['tables'] ) || empty( $entities[$entityType]['tables'] ) ):
				throw new OrmException( 'There is not at least a table defined for this Entity class!' );
			case ( ! array_key_exists( $entities[$entityType]['tables'][0], $tables ) ):
				throw new OrmException( 'Missing table info for this entity table!' );
		}
		
		$entityPrimaryTable	= $entities[$entityType]['tables'][0];
		$tableInfo			= $tables[$entityPrimaryTable];
	
		
		return array_merge( $tableInfo['partition_key_columns'], $tableInfo['clustering_columns'] );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getAllEntityDataColumns()
	 */
	public function getAllEntityDataColumns( $entityType )
	{
		$columns	= array();
		foreach ( $this->getEntityTables( $entityType )  as $table )
		{
			$columns = array_merge( $columns, $this->getEntityTableDataColumns( $entityType, $table ) );
		}
		
		return $columns;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getEntityTableDataColumns()
	 */
	public function getEntityTableDataColumns( $entityType, $dbTable )
	{
		$entityTables	= $this->getEntityTables( $entityType );
		$tables			= $this->config->get( 'tables' );

		// Check config
		switch ( true )
		{
			case ( array_search( $dbTable, $entityTables ) === false ):
				throw new OrmException( sprintf( 'No such table( %s ) defined for this Entity class ( %s )!', $dbTable, $entityType ) );
			case ( ! array_key_exists( $dbTable, $tables ) ):
				throw new OrmException( sprintf( 'Missing table info for this entity table ( %s )!', $dbTable ) );
		}
		
		return $tables[$dbTable]['data_columns'];
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface::getEntityTablesWithInfo()
	 */
	public function getEntityTablesWithColumnsInfo( $entityType )
	{
		$entityTables			= $this->getEntityTables( $entityType );
		
		$entityTablesWithInfo	= array();
		foreach ( $entityTables as $table )
		{
			$entityTablesWithInfo[$table]	= $this->getEntityTableColumns( $entityType, $table );
		}
		
		return $entityTablesWithInfo;
	}
}
