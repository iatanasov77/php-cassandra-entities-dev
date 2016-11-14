<?php

namespace VankoSoft\Alexandra\DBAL\Adapter;

class AdapterConfig implements AdapterConfigInterface
{
	protected $config;
	
	public function __construct( array $config )
	{
		$this->config	= $config;
		
		$this->validate();
	}
	
	public function driver()
	{
		return $this->config['driver'];
	}
	
	public function port()
	{
		return $this->config['port'];
	}
	
	public function contactPoints()
	{
		return $this->config['contact_points'];
	}
	
	public function username()
	{
		return $this->config['username'];
	}
	
	public function password()
	{
		return $this->config['password'];
	}
	
	public function keyspace()
	{
		return $this->config['keyspace'];
	}
	
	///////////////////////////////////////////////////////////////
	
	protected function validate()
	{
		
	}
}