<?php

namespace VankoSoft\Common\Application;

use Noodlehaus\Config as ConfigLoader;
use VankoSoft\Common\Service\ServiceContainer;

class Kernel
{
	protected $rootPath;
	
	protected $configPath;
	
	protected $appPath;
	
	protected $publicPath;
	
	protected $configContainer;
	
	protected $serviceContainer;
	
	public function __construct( $rootPath )
	{
		$this->rootPath			= $rootPath;
		
		$this->configPath		= $this->rootPath . DIRECTORY_SEPARATOR . 'config';
		
		$this->appPath			= $this->rootPath . DIRECTORY_SEPARATOR . 'src';
		
		$this->publicPath		= $this->rootPath . DIRECTORY_SEPARATOR . 'webroot';
		
		$this->config			= new ConfigLoader( $this->configPath );
		
		$this->serviceContainer	= new ServiceContainer( $this );
	}
	
	public function getRootPath()
	{
		return $this->rootPath;
	}
	
	public function getConfigPath()
	{
		return $this->configPath;
	}
	
	public function getAppPath()
	{
		return $this->appPath;
	}
	
	public function getPublicPath()
	{
		return $this->publicPath;
	}
	
	public function getConfig()
	{
		return $this->config;
	}
	
	public function getServiceContainer()
	{
		return $this->serviceContainer;
	}
}
