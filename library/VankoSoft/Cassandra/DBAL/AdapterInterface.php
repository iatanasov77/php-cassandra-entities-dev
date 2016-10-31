<?php

namespace VankoSoft\Cassandra\DBAL;

interface AdapterInterface
{
	function query( $cql, array $params, array $options );
}
