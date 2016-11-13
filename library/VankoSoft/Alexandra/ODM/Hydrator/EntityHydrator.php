<?php

namespace VankoSoft\Cassandra\ODM;

use Enigma\Library\Database\Cassandra\DataType\Data;

use VankoSoft\Cassandra\ODM\Entity\BaseEntity;

class EntityHydrator implements EntityHydratorInterface
{
	use CamelCaseTrait;
	
	/**
	 * @var \VankoSoft\Cassandra\ODM\EntityManager $em
	 */
	protected $em;
	
	/**
	 * @var bool $camelized
	 */
	protected $camelized;
	
	/**
	 * @brief	Convert this cassandra db type to DataStax Data type.
	 * 
	 * @var		array
	 */
	protected  $convertTypes;

	/**
	 * @brief	Class constructor
	 * 
	 * @param	\VankoSoft\Cassandra\ODM\EntityManager $em
	 * 
	 * @return	void
	 */
	public function __construct( EntityManager $em )
	{
		$this->em			= $em;
		
		// Initialize options
		$options			= $this->em->getEntityMetaDataConfig()->getOptions();
		$this->camelized	= isset( $options['camelize'] ) ? $options['camelize'] : true;
		$this->convertTypes	= isset( $options['convert_types'] ) ? $options['convert_types'] : array();
	}
	
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
			$entities[]	= $this->populateEntity( new $entityType(), $entityData );
		}

		return $entities;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::populateEntity()
	 * 
	 * @throws OrmException
	 */
	public function populateEntity( BaseEntity $entity, array $data )
	{
		foreach ( $data as $column => $value )
		{
			$property	= $this->camelized ? lcfirst( $this->camelize( $column ) ) : $column;
			switch ( true )
			{
				case ( $value instanceof \Enigma\Library\Database\Cassandra\DataType\DataTypeInterface ):
				case ( $value instanceof \Enigma\Library\Database\Cassandra\Adapter\Driver\Datastax\DataType\DataTypeInterface ):
					$entity->$property = $value->toData()->getValue();
					break;
				case ( $value instanceof \Enigma\Library\Database\Cassandra\DataType\Data ):
					$entity->$property = $value->getValue();
					break;
				case is_object( $value ):
					throw new OrmException( 'Populate Entity: Unknown data type object ' . get_class( $value ) );
				default:
					$entity->$property = $value;
			}
		}
		
		return $entity;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::extractEntity()
	 */
	public function extractEntity( BaseEntity $entity, $dbTable = null )
	{
		$entityType			= '\\' . get_class( $entity );
		$metaConfig			= $this->em->getEntityMetaDataConfig();
		$primaryKeyColumns	= $metaConfig->getEntityPrimaryKeyColumns( $entityType );
		$dataColumns		= $dbTable == null
							? $metaConfig->getAllEntityDataColumns( $entityType ) 
							: $metaConfig->getEntityTableDataColumns( $entityType, $dbTable ) ;
		$metaColumns		= array_merge( $primaryKeyColumns, $dataColumns );
		
		
		$data	= array();
		foreach( $metaColumns as $column => $info )
		{	
			$property		= $this->camelized 
							? lcfirst( $this->camelize( $column ) ) 
							: $column;
			
			$data[$column]	= in_array( $info['type'], $this->convertTypes ) && $entity->$property != null 
							? new Data( $info['type'], $entity->$property ) 
							: $entity->$property;
		}

		return $data;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::extractPrimaryKeys()
	 */
	public function extractPrimaryKeys( BaseEntity $entity )
	{
		$primaryKeys	= $this->em->getEntityMetaDataConfig()
								->getEntityPrimaryKeyColumns( 
									'\\' . get_class( $entity )
								);
		
		return $this->extractData( $entity, $primaryKeys );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::prepareParams()
	 */
	public function prepareParams( $entityType, array $params )
	{
		$columnsMeta	= $this->em->getEntityMetaDataConfig()
								->getEntityColumns( $entityType );
		
		foreach ( $params as $key => $value )
		{
			$columnType	= $columnsMeta[$key]['type'];
			if( in_array( $columnType, $this->convertTypes ) && ! ( $value instanceof Data ) )
			{
				$params[$key]	= new Data( $columnType, $value );
			}
		}
		
		return $params;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @brief	Extract Db data for passed columns only
	 * 
	 * @param	\VankoSoft\Cassandra\ODM\BaseEntity $entity
	 * @param	array $keys	Array with columns meta.
	 * 
	 * @return	array
	 */
	protected function extractData( $entity, array $keys )
	{
		$ret	= array();
		foreach ( $keys as $key => $columnInfo )
		{
			$property		= $this->camelized ? $this->camelize( $key ) : $key;
			
			if ( in_array( $columnInfo['type'], $this->convertTypes ) )
			{
				 $ret[$key]	= new Data( $columnInfo['type'], ( int ) $entity->$property );
			}
			else
			{
				$ret[$key]	= $entity->$property;
			}
		}
		
		return $ret;
	}
}
