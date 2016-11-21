<?php

namespace VankoSoft\Alexandra\ODM;

use Noodlehaus\Config as NoodlehausConfig;

use VankoSoft\Alexandra\DBAL\Connection\Connection;
use VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWork;
use VankoSoft\Alexandra\DBAL\Driver\DataStax\Adapter as DbAdapter;
use VankoSoft\Alexandra\ODM\Entity\Entity;
use VankoSoft\Alexandra\ODM\Entity\EntitySupport;

/**
 * @brief	EntityManager Service.
 * @details	This service play a role of a Repository Factory.
 */
class RepositoryContainer implements RepositoryContainerInterface
{	
	/**
	 * @var \Enigma\Library\Database\Alexandra\Adapter\Adapter $dbAdapter
	 */
	protected $dbAdapter;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\UnitOfWork\UnitOfWorkInterface $uow;
	 */
	protected $uow;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\EntityHydrator $entityHydrator
	 */
	protected $entityHydrator;
	
	/**
	 * @var array $repositories
	 */
	protected $repositories;
	
	/**
	 * @brief	Repository container constructor.
	 * 
	 * @param	\Enigma\Library\Database\Alexandra\Adapter\Adapter $config
	 * 
	 * @return	void
	 */
	public function __construct( NoodlehausConfig $config )
	{
		$connection			= new Connection( $config->get( 'connection' ) );
		$connection->setDefaultConnection( 'evseevnn' );
		
		$this->db			= $connection->get();
		
		$this->uow			= new UnitOfWork();
		
		$this->repositories	= array();
	}
	
	/**
	 * @copydoc	\VankoSoft\Alexandra\ODM\RepositoryContainerInterface::get()
	 */
	public function get( $alias )
	{
		// Repositories lazy Loading. If repository is not loaded , try to load it.
		if ( ! isset( $this->repositories[$alias] ) )
		{
			$entityBase		= '\VankoSoft\Alexandra\ODM\Entity\Entity';
			$repositoryBase	= '\VankoSoft\Alexandra\ODM\Repository\RepositoryInterface';
			
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
			
			$table						= $config->get( 'repositories.' . $alias . '.table' );
			$tableGateway				= new TableGateway(
															$table,
															$config->get( 'schema.' . $table ),
															$this->dbAdapter
														);
			$hydrator					= new EntityHydrator();
			$entitySupport				= new EntitySupport( $tableGateway, $hydrator );
			
			$this->repositories[$alias]	= new $repository( $entity, $entitySupport, $this->uow );
		}
		
		return $this->repositories[$alias];
	}
	
	public function commit()
	{
		$this->uow->commit();
	}
}
