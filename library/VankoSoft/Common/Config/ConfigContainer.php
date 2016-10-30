<?php

namespace VankoSoft\Common\Config;

use VankoSoft\Common\ContainerInterface;

class ConfigContainer implements ContainerInterface
{
	/**
	 * 
	 * @var	array
	 */
	protected $config;

	/**
	 * 
	 * @var	array
	 */
	protected $objectMap;
	
	/**
	 * 
	 * @var	array
	 */
	protected $objectContainer;
	
	public function __construct( $config = array(), $objectMap =array() )
	{
		$this->config		= $config;
		$this->objectMap	= $objectMap;
	}
	
	public function get( $alias )
	{
		if ( ! array_key_exists( $alias , $this->objectContainer ) )
		{
			$this->objectContainer[$alias]	= $this->createObject( $alias );
		}
		
		return $this->objectContainer[$alias];
	}
	
	public function createObject( $alias )
	{
		$this->checkCreateObject( $alias );
		
		return new $this->objectMap[$alias]( $this->config[$alias] );
	}
	
	protected function checkCreateObject( $alias )
	{
		switch( true )
		{
			case ! array_key_exists( $alias, $this->config ):
			case ! array_key_exists( $alias, $this->objectMap ):
				throw new Exception\ContainerInvalidObject( sprintf( 'Can\'t create config object for alias: "%s".', $alias ) );
				
		}
	}
}
