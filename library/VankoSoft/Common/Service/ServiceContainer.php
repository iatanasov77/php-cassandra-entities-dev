<?php

namespace VankoSoft\Common\Service;

use VankoSoft\Common\Application\Kernel;

class ServiceContainer
{
	/**
	 * @var	\VankoSoft\Common\Application\Kernel $kernel
	 */
	protected $kernel;

	/**
	 * @var	array
	 */
	protected $services;

	public function __construct( Kernel $kernel )
	{
		$this->kernel		= $kernel;
		$this->services		= array();
	}

	public function get( $alias )
	{
		if ( ! array_key_exists( $alias , $this->services ) )
		{
			$this->services[$alias]	= $this->createService( $alias );
		}

		return $this->services[$alias];
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	
	protected function createService( $alias )
	{
		$config			= $this->kernel->getConfig();
		$serviceConfig	= $config->get('services.'.$alias);
		
		$params			= array();
		if ( is_array( $serviceConfig['params'] ) )
		{
			foreach ( $serviceConfig['params'] as $key => $value )
			{
				if ( strpos( $value, '%' ) === 0  )
				{
					$params[$key]	= $config->get( ltrim( $value, '%' ) );
				}
				else if( strpos( $value, '&' ) === 0 )
				{
					$params[$key]	= $this->get( ltrim( $value, '&' ) );
				}
			}
		}
		
		if ( isset( $serviceConfig['factory'] ) )
		{
			return $serviceConfig['factory']::create( $params );
		}
		else if ( isset( $serviceConfig['class'] ) )
		{
			return new $serviceConfig['class']( ...array_values( $params ) );
		}
		
		throw new ServiceContainerException( sprintf( 'Cannot create service by alias: "%s"', $alias ) );
	}
}
