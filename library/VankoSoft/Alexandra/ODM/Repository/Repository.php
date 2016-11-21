<?php

namespace VankoSoft\Alexandra\ODM\Repository;

use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;
use VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface;

/**
 * @brief	Base class for repositories that used EntityGateway to load and persist entities.
 */
class Repository implements RepositoryInterface
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
		$entity	= $this->es->hydrator()->hydrate( new $this->entityType , $data );
		
		$this->uow->schedule( $entity, $this->es, State::NOT_PERSISTED );
		
		return $entity;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::find()
	 */
	public function find( array $params, array $options = array() )
	{
		$rows		= $this->es->gw()->select( $params );
		
		$entities	= array();
		foreach ( $rows as $row )
		{
			$entity	= $this->es->hydrator()->hydrate( new $this->entityType , $row );
			$this->uow->schedule( $entity, $this->es, State::PERSISTED );
			$entities[]	= $entity;
		}
		
		return $entities;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::findOne()
	 */
	public function findOne( array $params, array $options = array() )
	{
		$entities	= $this->find( $params, $options );
		
		return empty( $entities ) ? new EntityNotExists() : $entities[0];
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::save()
	 */
	public function save( Entity $entity )
	{
		$this->uow->schedule( $entity, $this->es, State::PERSISTED );
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::remove()
	 */
	public function remove( Entity $entityl )
	{
		$this->uow->schedule( $entity, $this->es, State::REMOVED );
	}
	
	public function increment( $column )
	{
		$this->uow->schedule( $entity, $this->es, State::REMOVED );
	}
	
	public function decrement( $column )
	{
		$this->uow->schedule( $entity, $this->es, State::REMOVED );
	}
}	
