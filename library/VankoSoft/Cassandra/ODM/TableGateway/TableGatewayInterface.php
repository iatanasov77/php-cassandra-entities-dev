<?php


interface TableGatewayInterface
{
	
	public function fetch();
	
	public function persist();
}