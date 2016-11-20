<?php

namespace VankoSoft\Alexandra\ODM\Repository;

use VankoSoft\Alexandra\ODM\EntityGateway;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface;

/**
 * @brief	Base class for repositories that used EntityGateway to load and persist entities.
 */
class Repository implements EntityRepositoryInterface
{
	/**
	 * @var string $entityType
	 */
	protected $entityType;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\Entity\EntitySupport $es
	 */
	protected $es;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface;
	 */
	protected $uow;
	
	/**
	 * @brief	Initialize Entity Repository
	 * 
	 * @param	string $entityType
	 * @param	\VankoSoft\Alexandra\ODM\Entity\EntitySupport $es
	 * 
	 * @return	void
	 */
	public function __construct( $entityType, EntitySupport $es, UnitOfWorkInterface $uow )
	{
		$this->entityType	= $entityType;
		$this->es			= $es;
		$this->uow			= $uow;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::create()
	 */
	public function create( array $data = array() )
	{
		$hydrator	= new DefaultHydrator();
		
		$entity	= $hydrator->hydrate( new $this->entityType() , $data );
		$meta 	= new EntityMeta( $this->gw, $hydrator );
		
		$this->uow->schedule( $entity, $meta, State::NOT_PERSISTED );
		
		return $entity;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::find()
	 */
	public function find( $params, $options, $query = null )
	{
		$hydrator	= new DataStaxHydrator();
		$rows		= $this->eg->fetch( $this->entityType, $params, $options );
		
		$entities	= array();
		$meta 	= new EntityMeta( $this->gw, $hydrator );
		foreach ( $rows as $row )
		{
			$entity	= $hydrator->hydrate( new $this->entityType() , $row );
			$this->uow->schedule( $entity, $meta, State::PERSISTED );
			$entities[]	= $entity;
		}
		
		
		
		return $entities;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::findOne()
	 */
	public function findOne( $params, $options, $query = null )
	{
		$entities	= $this->find( $params, $options );
		
		return empty( $entities ) ? new EntityNotExists() : $entities[0];
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::save()
	 */
	public function save( BaseEntity $entity, $query = null )
	{
		$meta 	= new EntityMeta( $this->gw, $hydrator );
		$this->uow->schedule( $entity, $meta, State::PERSISTED );
		
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::remove()
	 */
	public function remove( BaseEntity $entity, $query = null )
	{
		$meta 	= new EntityMeta( $this->gw, $hydrator );
		$this->uow->schedule( $entity, $meta, State::REMOVED );
	}
	
	public function increment( $column )
	{
		$meta 	= new EntityMeta( $this->gw, $hydrator );
		$this->uow->schedule( $entity, $meta, State::REMOVED );
	}
	
	public function decrement( $column )
	{
		$meta 	= new EntityMeta( $this->gw, $hydrator );
		$this->uow->schedule( $entity, $meta, State::REMOVED );
	}
}	
