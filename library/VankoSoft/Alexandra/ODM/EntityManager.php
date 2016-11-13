<?php

namespace VankoSoft\Alexandra\ODM;

use VankoSoft\Alexandra\DBAL\Driver\DataStax\Adapter as DbAdapter;
use Enigma\Library\Config\ConfigLoaderInterface;

use Cyclonis\Api\V1\Module\Main\Service\Security\User\User;
use VankoSoft\Alexandra\ODM\Entity\BaseEntity;

/**
 * @brief	EntityManager Service.
 * @details	This service play a role of a Repository Factory.
 */
class EntityManager implements EntityManagerInterface
{	
	/**
	 * @var \Enigma\Library\Database\Alexandra\Adapter\Adapter $dbAdapter
	 */
	protected $dbAdapter;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\EntityMetaDataConfig $entityMetaDataConfig
	 */
	protected $entityMetaDataConfig;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\EntityHydrator $entityHydrator
	 */
	protected $entityHydrator;
	
	/**
	 * @var array $repositories
	 */
	protected $repositories;
	
	/**
	 * @brief	Entity manager constructor.
	 * 
	 * @param	\Enigma\Library\Database\Alexandra\Adapter\Adapter $dbAdapter
	 * @param	\Enigma\Library\Config\ConfigLoaderInterface $entityMetaData
	 * @param	\Cyclonis\Api\V1\Module\Main\Service\Security\User\User $user
	 * @param	string $accessId
	 * 
	 * @return	void
	 */
	public function __construct( DbAdapter $dbAdapter, ConfigLoaderInterface $entityMetaData )
	{
		$this->dbAdapter			= $dbAdapter;
		$this->entityMetaDataConfig	= new EntityMetaDataConfig( $entityMetaData );
		$this->entityHydrator		= new EntityHydrator( $this );
		$this->repositories			= array();
	}

	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityManagerInterface::getDbAdapter()
	 */
	public function getDbAdapter()
	{
		return $this->dbAdapter;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityManagerInterface::getEntityMetaDataConfig()
	 */
	public function getEntityMetaDataConfig()
	{
		return $this->entityMetaDataConfig;
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\EntityManagerInterface::getRepository()
	 */
	public function getRepository( $alias )
	{
		// Repositories lazy Loading. If repository is not loaded , try to load it.
		if ( ! isset( $this->repositories[$alias] ) )
		{
			$entityBase		= '\VankoSoft\Alexandra\ODM\Entity\BaseEntity';
			$repositoryBase	= '\VankoSoft\Alexandra\ODM\EntityRepositoryInterface';
			
			extract( $this->entityMetaDataConfig->getRepositoryConfig( $alias ) );
			
			switch ( true )
			{
				case ! isset( $dbTable ) || ! isset( $repository ) || ! isset( $entity ):
					throw new OrmException( 'Invalid repository config!' );
				case ! class_exists( $entity ) || ! is_subclass_of( $entity, $entityBase ):
					throw new OrmException( 'Invalid entity type!' );
				case ! class_exists( $repository ) || ! is_subclass_of( $repository, $repositoryBase ):
					throw new OrmException( 'Invalid repository type!' );
			}
			
			$tableGateway	= new TableGateway( $dbTable, $tableMetaConfig, $this->dbAdapter );
			$hydrator		= new Hydrator( $tableMetaConfig );
			
			$this->repositories[$alias]	= new $repository( $entity, $tableGateway, $hydrator );
		}
		
		return $this->repositories[$alias];
	}
}
