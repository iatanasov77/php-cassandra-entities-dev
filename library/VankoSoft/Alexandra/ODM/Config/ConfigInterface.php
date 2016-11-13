<?php

namespace VankoSoft\Alexandra\ODM;

interface ConfigInterface
{
	/**
	 * @brief	Get option preferences from config.
	 * 
	 * @return	array
	 */
	public function getOptions();
	
	/**
	 * @brief	Get repository type by an entity type.
	 * 
	 * @param	string $entityType
	 * 
	 * @return	string
	 */
	public function getRepositoryType( $entityType );
	
	/**
	 * @brief	Get db tables list for an entity type
	 * 
	 * @param	string $entityType
	 * 
	 * @return	array
	 */
	public function getEntityTables( $entityType );

	/**
	 * @brief	Get all columns with info for an entity.
	 * 
	 * @param	string $entityType
	 * 
	 * @return	array
	 */
	public function getEntityColumns( $entityType );
	
	/**
	 * @brief	Get all columns with info about an entity for the concrete db table.
	 * 
	 * @param	string $entityType
	 * 
	 * @return	array
	 */
	public function getEntityTableColumns(	$entityType, $dbTable );
	
	/**
	 * @brief	Get primary key columns info by an entity type
	 * @details	If entity has more than one table, get primary keys 
	 *			from primary table (the first table from entity tables list ).
	 * 
	 * @note	Generally if entity has more than one table, must all entity tables 
	 *			has the same primary key columns. At this moment i can not think 
	 *			of any particular exception to this case.
	 * 
	 * @param	string $entityType
	 * 
	 * @return	array	Key/Value pairs $columnName	=> array $columnInfo
	 */
	public function getEntityPrimaryKeyColumns( $entityType );
	
	/**
	 * @brief	Get entity data columns merged from all tables.
	 * 
	 * @param	string $entityType
	 * 
	 * @return	array
	 */
	public function getAllEntityDataColumns( $entityType );
	
	/**
	 * @brief	Get entity data columns for specified table
	 * 
	 * @param	string $entityType
	 * @param	string $dbTable
	 * 
	 * @return	array
	 */
	public function getEntityTableDataColumns( $entityType, $dbTable );
	
	/**
	 * @brief	Get Entity tables with columns info
	 * 
	 * @param	string $entityType
	 * 
	 * @return	array	Key/value pairs - $tableName/$columnsInfo
	 */
	public function getEntityTablesWithColumnsInfo( $entityType );
}
