<?php

namespace VankoSoft\Alexandra\CONF;

use Noodlehaus\Config as NoodlehausConfig;

use VankoSoft\Common\TypeCast\ArrayCastAwareInterface;

class Config implements ArrayCastAwareInterface
{
	protected $config;
	
	public function __construct( $configPath )
	{
		$this->config	= NoodlehausConfig::load( $configPath );
	}
	
	public function get( $key )
	{
		return $this->config->get( $key );
	}
	
	public function toArray()
	{
		return $this->config->all();
	}
}
