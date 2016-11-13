<?php

namespace VankoSoft\Alexandra\DBAL;

interface AdapterInterface
{
	function query( $cql, array $params, array $options );
}
