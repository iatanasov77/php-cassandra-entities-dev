<?php

namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\CamelCaseTrait;

class DataStaxHydrator implements HydratorInterface
{
	use CamelCaseTrait;
	
	private $tableMeta;
	
	public function __construct( $tableMeta )
	{
		$this->tableMeta	= $tableMeta;
	}
	
	/**
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\Entity\Entity $entity
	 * 
	 * @return	array
	 */
	public function extract( Entity $entity)
	{
		$row	= array();
		foreach ( $this->tableMeta['columns'] as $column => $meta )
		{
			$property	= lcfirst( $this->camelize( $column ) );
			switch ( $meta['type'] )
			{
				case 'float':
					$row[$column]	= new \Cassanra\Float( $entity->$property );
			
					break;
				case 'set':
					$row[$column]	= new \Cassanra\Set( $entity->$property );
						
					break;
				case 'map':
					$row[$column]	= new \Cassanra\Map( $entity->$property );
			
					break;
				default:
					$row[$column]	= $entity->$property;
			}
		}
		
		return $row;
	}
	
	/**
	 * 
	 * @param	\VankoSoft\Alexandra\ODM\Entity\Entity $entity
	 * @param	mixed $rowData
	 * 
	 * @return void
	 */
	public function hydrate( Entity &$entity, $rowData )
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
