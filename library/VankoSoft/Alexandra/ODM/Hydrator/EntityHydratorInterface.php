<?php

namespace VankoSoft\Alexandra\ODM;

use VankoSoft\Alexandra\ODM\Entity\BaseEntity;

/**
 * @brief	Entity mapper is a Data Hydrator for our entities
 * @details This interface is made for the specific needs of the cassandra db,
 *			but i think that this can used and for an other db engine.
 */
interface EntityHydratorInterface
{
	/**
	 * @brief	Fetch entities from db resultset array.
	 * 
	 * @param	array $resultSet
	 * @param	string $entityType
	 * 
	 * @return array
	 */
	public function fetchEntities( array $resultSet, $entityType );
	
	/**
	 * @brief	Populate an entity with the passed row data.
	 * @details The data value types are in format of the concrete database adapter.
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\BaseEntity $entity
	 * @param	array $data	Key/value pairs
	 * 
	 * @return	\VankoSoft\Alexandra\ODM\BaseEntity	Populated entity.
	 */
	public function populateEntity( BaseEntity $entity, array $data );
	
	/**
	 * @brief	Extract data from an entty.
	 * @details	Data must to be prepared according to the requirements of the concrete database adapter.
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\BaseEntity $entity
	 * @param	string $dbTable
	 * 
	 * @return	array	Array with data prepared for cassandra db
	 */
	public function extractEntity( BaseEntity $entity,	$dbTable );
	
	/**
	 * @brief	Extract entity primary keys only.
	 * @details	In the cassandra context, this are the row keys ( partition keys + clustering keys )
	 *  
	 * @param	\VankoSoft\Alexandra\ODM\BaseEntity $entity
	 * 
	 * @return	array
	 */
	public function extractPrimaryKeys( BaseEntity $entity );
	
	/**
	 * @brief	Prepare param types.
	 * @details	Prepare all types that must be converted to be sent to dbAdapter query method.
	 * 
	 * @param	string $entityType
	 * @param	array $params
	 * 
	 * @return	array
	 */
	public function prepareParams( $entityType, array $params );
}
