<?php

namespace VankoSoft\Cassandra\ODM\Hydrator;

/**
 * @brief	Entity mapper is a Data Hydrator for our entities
 * @details This interface is made for the specific needs of the cassandra db,
 *			but i think that this can used and for an other db engine.
 */
interface EntityPersistedKeysHydratorInterface extends EntityHydratorInterface
{
	/**
	 * @brief	Create a persisted key from primary keys.
	 * 
	 * @details	Persisted key is used from the repository 
	 *			to knows which entities are persisted to the database.
	 *			This method can be called by 2 ways:
	 *			1. With only one parametter BaseEntity object , then it will get the data from entity.
	 *			2. With first parametter entity type string and second parametter array with row data.
	 *				Then will get key values from array according to specific entity type.  
	 * 
	 * @param	\VankoSoft\Cassandra\ODM\BaseEntity|string $entity
	 * @param	null|array $data
	 * 
	 * @return string
	 */
	public function getKeyPersisted( $entity, $data = null );
}
