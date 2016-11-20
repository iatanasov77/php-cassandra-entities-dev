<?php

namespace VankoSoft\Alexandra\ODM\Entity;

use VankoSoft\Alexandra\DBAL\TableGatewayInterface;
use VankoSoft\Alexandra\ODM\Hydrator\EntityHydratorInterface;

class EntitySupport
{
	/**
	 * @var \VankoSoft\Alexandra\DBAL\TableGatewayInterface	$gw
	 */
	private $gw;
	
	/**
	 * @var \VankoSoft\Alexandra\ODM\Hydrator\EntityHydratorInterface
	 */
	private $hydrator;

	public function __construct( TableGatewayInterface $gw, EntityHydratorInterface $hydrator )
	{
		$this->gw		= $gw;
		$this->hydrator	= $hydrator;
	}
}
