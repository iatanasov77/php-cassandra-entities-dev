<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

interface UnitOfWorkInterface
{
	public function addEntity( $entity, $state );
	
	public function addEntities( $entity, $state );
	
	public function commit();
}
