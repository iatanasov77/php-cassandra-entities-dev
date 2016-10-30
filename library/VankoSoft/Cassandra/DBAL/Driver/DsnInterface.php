<?php

namespace VankoSoft\Cassandra\DBAL\Driver;

interface DsnInterface
{
	
	public function getHost();
	
	public function getPort();
	
	public function getUser();
	
	public function getPassword();
	
	public function getKeyspace();
}
