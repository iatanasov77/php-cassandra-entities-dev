<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

class UnitOfWork implements UnitOfWorkInterface
{
	protected $scheduledForInsert;
	
	protected $scheduledForUpdate;
	
	protected $scheduledForDelete;
	
	protected $entityClean;
	
	public function __construct()
	{
		$this->scheduleForInsert	= new \SplObjectStorage;
		$this->scheduleForUpdate	= new \SplObjectStorage;
		$this->scheduleForDelete	= new \SplObjectStorage;
		$this->entityClean			= new \SplObjectStorage;
	}
	
	public function addEntity( $entity, $state )
	{
		
	}
	
	public function addEntities( $entity, $state )
	{
	
	}
	
	public function commit()
	{
		
	}
}
