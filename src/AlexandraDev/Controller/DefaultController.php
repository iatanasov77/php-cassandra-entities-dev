<?php

namespace VankoSoft\AlexandraDev\Controller;

class DefaultController
{
	private $container;
	
	public function __construct( $container )
	{
		$this->container	= $container;
	}
	
	public function index( $request, $response, $args )
	{
		$repo		= $this->container->get('repository')->get( 'Main::Products' );
		
		$products	= $repo->find();
		
		echo "<pre>"; var_dump($products); die;
	}
}
