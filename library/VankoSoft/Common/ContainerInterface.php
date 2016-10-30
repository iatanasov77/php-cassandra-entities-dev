<?php

namespace VankoSoft\Common;

interface ContainerInterface
{
	/**
	 * @brief	Get an object from container by his alias.
	 * 
	 * @param	string $objectAlias
	 * 
	 * @return	object
	 * 
	 * @thrown	\VankoSoft\Common\Exception\ContainerMissingObject
	 */
	function get( $objectAlias );	
}
