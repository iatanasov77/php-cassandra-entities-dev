<?php

namespace VankoSoft\Alexandra\DBAL;

interface AdapterInterface
{
	function close();
	
	function query( $cql, array $params, array $options );
}
