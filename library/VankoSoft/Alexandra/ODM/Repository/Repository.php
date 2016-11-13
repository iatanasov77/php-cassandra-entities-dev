<?php

namespace VankoSoft\Alexandra\ODM\Repository;

use VankoSoft\Alexandra\ODM\EntityGateway;
use VankoSoft\Alexandra\ODM\EntityManager;
use VankoSoft\Alexandra\ODM\Entity\BaseEntity;
use VankoSoft\Alexandra\ODM\Entity\EntityNotExists;

/**
 * @brief	Base class for repositories that used EntityGateway to load and persist entities.
 */
class Repository implements EntityRepositoryInterface
{
	/**
	 * @var \VankoSoft\Alexandra\ODM\EntityGatewayInterface
	 */
	private $eg;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\EntityManager $em
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
	 * @param	\VankoSoft\Alexandra\ODM\EntityManager $em
	 * 
	 * @return	void
	 */
	public function __construct( $entityType, EntityManager $em )
	{
		$this->em					= $em;
		$this->entityType			= $entityType;
		$this->persistedEntities	= array();
		
		$this->eg					= new EntityGateway( $this->em );
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::create()
	 */
	public function create( array $data = array() )
	{
		$hydrator	= new DefaultHydrator();
		
		return $hydrator->hydrate( new $this->entityType() , $data );
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::find()
	 */
	public function find( $params, $options, $query = null )
	{
		$hydrator	= new DataStaxHydrator();
		$rows		= $this->eg->fetch( $this->entityType, $params, $options );
		$entities	= array();
		foreach ( $rows as $row )
		{
			$findedEntities[]	= $hydrator->hydrate( new $this->entityType() , $row );
		}
		$this->persistedEntities	= array_merge( $this->persistedEntities, $findedEntities );
		
		return $findedEntities;
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
		return $this->eg->persist( $entity, EntityGateway::PERSIST_MODE_INSERT );
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityRepositoryInterface::remove()
	 */
	public function remove( BaseEntity $entity, $query = null )
	{
		return $this->eg->persist( $entity, EntityGateway::PERSIST_MODE_DELETE );
	}
}	
