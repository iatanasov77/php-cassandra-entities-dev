<?php

namespace VankoSoft\Cassandra\ODM;

/**
 * @brief Entity manager interface.
 */
interface EntityManagerInterface
{	
	
	/**
	 * @brief	Get db adaptor.
	 * 
	 * @return \Enigma\Library\Database\Cassandra\Adapter\Adapter $dbAdapter
	 */
	public function getDbAdapter();
	
	/**
	 * @brief	Return entity meta data configuration.
	 * 
	 * @return	\VankoSoft\Cassandra\ODM\EntityMetaDataConfigInterface
	 */
	public function getEntityMetaDataConfig();
	
	/**
	 * @brief	Get current logged in user.
	 * 
	 * @return	\Cyclonis\Api\V1\Module\Main\Service\Security\User\User
	 */
	public function getUser();
	
	/**
	 * @brief	Get access id.
	 * 
	 * @return	string
	 */
	public function getAccessId();
	
	/**
	 * @brief	Get repository for an entity type.
	 * 
	 * @details	This method play role of a factory for all cyclonis entity repositories.
	 * 
	 * @param	string $entityType
	 * 
	 * @throws \VankoSoft\Cassandra\ODM\OrmException
	 * 
	 * @return \VankoSoft\Cassandra\ODM\EntityRepositoryInterface
	 */
	public function getRepository( $entityType );
	
}

