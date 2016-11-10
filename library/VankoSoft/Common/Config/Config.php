<?php

namespace VankoSoft\Common\Config;

class Config implements ConfigInterface
{
	protected $config;
	
	public function __construct( array $config )
	{
		$this->config	= $config;
	}
	
	public function get( $option, $default = null )
	{
		if ( ! isset( $this->config[$option] ) )
		{
			return $default;
		}
		
		return $this->config[$option];
	}
}
