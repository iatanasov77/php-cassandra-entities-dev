<?php

namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\ODM\Entity\BaseEntity;

interface HydratorInterface
{
	public function extract( BaseEntity $entity );
	
	public function hydrate( BaseEntity &$entity, $data );
}