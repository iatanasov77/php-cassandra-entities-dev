<?php

namespace VankoSoft\Common\Application;

class Location
{
	protected $rootPath;
	
	protected $configPath;
	
	protected $appPath;
	
	protected $publicPath;
	
	public function __construct( $rootPath )
	{
		$this->rootPath			= $rootPath . DIRECTORY_SEPARATOR;
		
		$this->configPath		= $this->rootPath . 'config' . DIRECTORY_SEPARATOR;
		
		$this->srcPath			= $this->rootPath . 'src' . DIRECTORY_SEPARATOR;
		
		$this->publicPath		= $this->rootPath . 'webroot' . DIRECTORY_SEPARATOR;
	}
	
	public function getRootPath()
	{
		return $this->rootPath;
	}
	
	public function getConfigPath()
	{
		return $this->configPath;
	}
	
	public function getSrcPath()
	{
		return $this->srcPath;
	}
	
	public function getPublicPath()
	{
		return $this->publicPath;
	}
}
