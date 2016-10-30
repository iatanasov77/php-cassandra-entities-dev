<?php

namespace VankoSoft\Cassandra\ODM;

interface EntityGatewayInterface
{
	/**
	 * @brief	Load entities from database.
	 * 
	 * @param	string $entityType
	 * @param	array $params
	 * @param	array $options
	 * 
	 * @return array
	 */
	public function load( $entityType, array $params, array $options );
	
	/**
	 * @brief	Persist entity
	 * 
	 * @details This persister method play role of a simple UnitOfWork module.
	 * 
	 * @param	\StdClass $entity
	 * @param	string $mode
	 * 
	 * @return	void
	 */
	public function persist( $entity, $mode );
}
