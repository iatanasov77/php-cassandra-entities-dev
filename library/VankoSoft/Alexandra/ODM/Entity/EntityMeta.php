<?php

namespace VankoSoft\Alexandra\ODM\Entity;

class EntityMeta
{
	private $gw;
	
	private $hydrator;

	public function __construct( TableGatewayInterface $gw, EntityHydratorInterface $hydrator )
	{
		$this->gw		= $gw;
		$this->hydrator	= $hydrator;
	}
}
