<?php

namespace VankoSoft\Alexandra\DBAL\Adapter;

interface AdapterInterface
{
	function query( $cql, array $params, array $options );
}
