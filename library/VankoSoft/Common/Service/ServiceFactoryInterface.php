<?php

namespace VankoSoft\Common\Service;

interface ServiceFactoryInterface
{
	public static function create( array $params );
}