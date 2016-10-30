<?php

namespace VankoSoft\Cassandra\DBAL\Driver;

class DriverConfig
{
	protected $config;
	
	public function __construct( array $config )
	{
		$this->config	= $config;
		
		$this->validate();
	}
	
	public function getContactPoints()
	{
		return $this->config['contactPoints'];
	}
	
	public function getUsername()
	{
		
	}
	
	public function getPassword()
	{
		
	}
	
	///////////////////////////////////////////////////////////////
	
	protected function validate()
	{
		
	}
}