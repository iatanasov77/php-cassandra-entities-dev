<?php

namespace VankoSoft\Alexandra\ODM\Hydrator\DataStax;

use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;
use VankoSoft\Alexandra\ODM\Entity\BaseEntity;
use VankoSoft\Alexandra\ODM\CamelCaseTrait;

class EntityHydrator implements HydratorInterface
{
	use CamelCaseTrait;
	
	
	public function __construct( )
	{
		
	}
	
	public function extract( BaseEntity $entity)
	{
		
	}
	
	/**
	 * 
	 * @param	BaseEntity $entity
	 * @param	mixed $rowData
	 */
	public function hydrate( BaseEntity &$entity, $rowData )
	{
		foreach ( $rowData as $key => $value )
		{
			$property	= lcfirst( $this->camelize( $key ) );
			switch ( true )
			{
				case ( $value instanceof \Cassandra\Float ):
					$entity->$property	= $value->value();
						
					break;
				case ( $value instanceof \Cassandra\Set ):
					$entity->$property	= $value->values();
					
					break;
				case ( $value instanceof \Cassandra\Map ):
					$entity->$property	= array_map( $value->keys(), $value->values() );
						
					break;
				default:
					$entity->$property	= $value;
			}
		}
	}
}
