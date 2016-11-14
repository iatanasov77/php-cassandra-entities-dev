<?php

namespace VankoSoft\Alexandra\DBAL\Adapter;

interface AdapterConfigInterface
{
	public function driver();
	
	public function contactPoints();
	
	public function username();
	
	public function password();
	
	public function keyspace();
}