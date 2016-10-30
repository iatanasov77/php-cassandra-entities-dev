<?php

namespace VankoSoft\Cassandra\ODM\Repository;

use VankoSoft\Cassandra\ODM\EntityRepositoryInterface;
use VankoSoft\Cassandra\ODM\EntityManager;
use VankoSoft\Cassandra\ODM\Entity\BaseEntity;
use VankoSoft\Cassandra\ODM\Entity\EntityNotExists;

/**
 * @brief	Base class for repositories that used DbAdapter directly to load and persist entities.
 * @details	All interface methods of the children of this class required a cql query parameter.
 */
class QueryPassEntityRepository implements EntityRepositoryInterface
{
	/**
	 * @var \VankoSoft\Cassandra\ODM\EntityManager $em
	 */
	protected $em;
	
	/**
	 * @var string $entityType
	 */
	protected $entityType;
	
	/**
	 * @var array $persistedEntities
	 */
	protected $persistedEntities;

	/**
	 * @brief	Initialize Entity Repository
	 * 
	 * @param	string $entityType
	 * @param	\VankoSoft\Cassandra\ODM\EntityManager $em
	 * 
	 * @return	void
	 */
	public function __construct( $entityType, EntityManager $em )
	{
		$this->em					= $em;
		$this->entityType			= $entityType;
		$this->persistedEntities	= array();
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityRepositoryInterface::create()
	 */
	public function create( array $data = array() )
	{
		return $this->em->populateEntity( new $this->entityType() , $data );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityRepositoryInterface::find()
	 */
	public function find( array $params, array $options, $query )
	{
		$params						= $this->em->prepareParams( $this->entityType, $params );
		$result						= $this->em->getDbAdapter()->query( $query, $params, null, $options );
		$findedEntities				= $this->em->fetchEntities( $result->toArray(), $this->entityType );
		$this->persistedEntities	= array_merge( $this->persistedEntities, $findedEntities );
		
		return $findedEntities;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityRepositoryInterface::findOne()
	 */
	public function findOne( array $params, array $options, $query )
	{
		$entities	= $this->find( $params, $options, $query );
		
		return empty( $entities ) ? new EntityNotExists() : reset( $entities );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityRepositoryInterface::save()
	 */
	public function save( BaseEntity $entity, $query )
	{
		$params	= $this->em->extractEntity( $entity );
		$this->em->getDbAdapter()->query( $query, $params );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityRepositoryInterface::save()
	 */
	public function remove( BaseEntity $entity, $query )
	{
		$params	= $this->em->extractPrimaryKeys( $entity );
		$this->em->getDbAdapter()->query( $query, $params );
	}
}
