<?php

namespace VankoSoft\Alexandra\DBAL;

use VankoSoft\Alexandra\DBAL\AdapterInterface;

interface ConnectionInterface
{	
	public function setDefaultConnection( $connectionName );
	
	public function get( $connectionName );
	
	public function set( $connectionName, AdapterInterface $adapter );
	
	public function close( $connectionName );
}