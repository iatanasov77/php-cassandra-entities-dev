<?php

namespace VankoSoft\Cassandra\ODM\Hydrator;

use VankoSoft\Cassandra\ODM;

class EntityPersistedKeysHydrator extends ORM\EntityHydrator implements EntityPersistedKeysHydratorInterface
{
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::fetchEntities()
	 */
	public function fetchEntities( array $resultSet, $entityType )
	{
		$entities	= array();
		if ( ! is_array( $resultSet ) )
		{
			return $entities;
		}
		
		foreach ( $resultSet as $entityData )
		{
			$persistedKey				= $this->getKeyPersisted( $entityType, $entityData );
			$entities[$persistedKey]	= $this->populateEntity( new $entityType(), $entityData );
		}

		return $entities;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::getKeyPersisted()
	 */
	public function getKeyPersisted( $entity, $data = null )
	{
		$primaryKeys	= array();
		if ( $entity instanceof ORM\Entity\BaseEntity )
		{
			$primaryKeys	= $this->extractPrimaryKeys( $entity );
		}
		elseif ( is_array( $data ) && is_string( $entity ) )
		{
			$primaryKeys	= array_intersect_key( $data, array_flip( array_keys( $this->getPrimaryKeys( $entity ) ) ) );
		}
		
		return implode( '-', $primaryKeys );
	}
}