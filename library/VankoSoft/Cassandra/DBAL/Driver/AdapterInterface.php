<?php

namespace VankoSoft\Cassandra\DBAL\Driver;

interface AdapterInterface
{
	function query();
	
	function executeSelect( $cql, $params );
	
	function executePersist( $cql, $params );
}
