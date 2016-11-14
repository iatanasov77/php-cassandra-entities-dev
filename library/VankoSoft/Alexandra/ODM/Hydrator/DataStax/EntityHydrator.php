<?php

namespace VankoSoft\Alexandra\ODM\Hydrator\DataStax;

use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;

class EntityHydrator implements HydratorInterface
{

	public function __construct( )
	{
		
	}
	
	public function extract( $entity)
	{
		
	}
	
	public function hydrate( &$entity, $rowData )
	{
		foreach ( $rowData as $key => $value )
		{
			switch ( true )
			{
				case ( $value instanceof \Cassandra\Set ):
					$entity->$key	= $value->value();
					
					break;
				case ( $value instanceof \Cassandra\Map ):
					$entity->$key	= $value->value();
						
					break;
				default:
					$entity->$key	= $value;
			}
		}
	}
}
