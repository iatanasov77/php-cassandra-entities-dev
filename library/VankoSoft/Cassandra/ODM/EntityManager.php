<?php

namespace VankoSoft\Cassandra\ODM;

use Enigma\Library\Database\Cassandra\Adapter\Adapter as DbAdapter;
use Enigma\Library\Config\ConfigLoaderInterface;

use Cyclonis\Api\V1\Module\Main\Service\Security\User\User;
use VankoSoft\Cassandra\ODM\Entity\BaseEntity;

/**
 * @brief	EntityManager Service.
 * @details	This service play a role of a Repository Factory.
 */
class EntityManager implements EntityManagerInterface, EntityHydratorInterface
{	
	/**
	 * @var \Enigma\Library\Database\Cassandra\Adapter\Adapter $dbAdapter
	 */
	protected $dbAdapter;
	
	/**
	 *
	 * @var \VankoSoft\Cassandra\ODM\EntityMetaDataConfig $entityMetaDataConfig
	 */
	protected $entityMetaDataConfig;
	
	/**
	 * @var \VankoSoft\Cassandra\ODM\EntityHydrator $entityHydrator
	 */
	protected $entityHydrator;
	
	/**
	 * @var array $repositories
	 */
	protected $repositories;
	
	/**
	 * @brief	Entity manager constructor.
	 * 
	 * @param	\Enigma\Library\Database\Cassandra\Adapter\Adapter $dbAdapter
	 * @param	\Enigma\Library\Config\ConfigLoaderInterface $entityMetaData
	 * @param	\Cyclonis\Api\V1\Module\Main\Service\Security\User\User $user
	 * @param	string $accessId
	 * 
	 * @return	void
	 */
	public function __construct( DbAdapter $dbAdapter, ConfigLoaderInterface $entityMetaData )
	{
		$this->dbAdapter			= $dbAdapter;
		$this->user					= $user;
		$this->accessId				= $accessId;
		$this->entityMetaDataConfig	= new EntityMetaDataConfig( $entityMetaData );
		$this->entityHydrator		= new EntityHydrator( $this );
		$this->repositories			= array();
	}

	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityManagerInterface::getDbAdapter()
	 */
	public function getDbAdapter()
	{
		return $this->dbAdapter;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityManagerInterface::getEntityMetaDataConfig()
	 */
	public function getEntityMetaDataConfig()
	{
		return $this->entityMetaDataConfig;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityManagerInterface::getUser()
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityManagerInterface::getAccessId()
	 */
	public function getAccessId()
	{
		return $this->accessId;
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityManagerInterface::getRepository()
	 */
	public function getRepository( $entityType )
	{
		// Repositories lazy Loading. If repository is not loaded , try to load it.
		if ( ! isset( $this->repositories[$entityType] ) )
		{
			if ( ! is_subclass_of( $entityType, '\VankoSoft\Cassandra\ODM\Entity\BaseEntity' ) )
			{
				throw new OrmException( 'Wrong entity type!' );
			}

			$repositoryType	= $this->entityMetaDataConfig->getRepositoryType( $entityType );
			if ( ! is_subclass_of( $repositoryType, '\VankoSoft\Cassandra\ODM\EntityRepositoryInterface' ) )
			{
				throw new OrmException( 'Wrong repository type into entity meta data!' );
			}

			$this->repositories[$entityType]	= new $repositoryType( $entityType, $this );
		}
		
		return $this->repositories[$entityType];
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::fetchEntities()
	 */
	public function fetchEntities( array $resultSet, $entityType )
	{
		return $this->entityHydrator->fetchEntities( $resultSet, $entityType );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::populateEntity()
	 */
	public function populateEntity( BaseEntity $entity, array $data )
	{
		return $this->entityHydrator->populateEntity( $entity, $data );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::extractEntity()
	 */
	public function extractEntity( BaseEntity $entity, $dbTable = null )
	{
		return $this->entityHydrator->extractEntity( $entity, $dbTable );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::extractPrimaryKeys()
	 */
	public function extractPrimaryKeys( BaseEntity $entity )
	{
		return $this->entityHydrator->extractPrimaryKeys( $entity );
	}
	
	/**
	 * @copydoc	\VankoSoft\Cassandra\ODM\EntityHydratorInterface::prepareParams()
	 */
	public function prepareParams( $entityType, array $params )
	{
		return $this->entityHydrator->prepareParams( $entityType, $params );
	}
	
	/**
	 * @brief	Populate an entity from query results.
	 * 
	 * @details	A fake method that execute one or more queries passed to it,
	 *			merge first rows of results of all queries and 
	 *			try to populate entity with resulting data.
	 * 
	 * @params	\VankoSoft\Cassandra\ODM\BaseEntity $entity
	 * @params	array $queries
	 * 
	 * @return \VankoSoft\Cassandra\ODM\BaseEntity Populated entity
	 */
	public function populateEntityFromQueries( BaseEntity $entity, array $queries )
	{
		$data	= array();
		foreach ( $queries as $query => $params )
		{
			$result	= $this->dbAdapter->query( $query,	$params );
			if ( $result->count() > 0 )
			{
				$data	= array_merge( $data, $result->toArray()[0] );
			}
		}
		
		return $this->entityHydrator->populateEntity( $entity, $data );
	}
}
