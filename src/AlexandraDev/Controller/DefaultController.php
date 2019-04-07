<?php

namespace VankoSoft\AlexandraDev\Controller;

use VankoSoft\AlexandraDev\Model\Entity\Product;
use VankoSoft\AlexandraDev\Model\Entity\TestUuid;

class DefaultController
{
	private $container;
	
	public function __construct( $container )
	{
		$this->container	= $container;
	}
	
	public function index( $request, $response, $args )
	{
	    $manager   = $this->container->get('repository');
		$repo		= $manager->get( Product::class );
		
		$products	= $repo->find();
		
		echo "<pre>"; var_dump($products);
		
// 		$newProduct   = new Product();
// 		$newProduct->title = 'New Product';
// 		$newProduct->qty = 5;
// 		$newProduct->price = 2.0;

// 		$repo->save( $newProduct );
// 		$manager->commit();
		
		$testUuid         = new TestUuid();
		$uuid             = new \Cassandra\UUID();
		$testUuid->uid   = $uuid->uuid();
		$testUuid->name   = 'Test 1';
		$testRepo         = $manager->get( TestUuid::class );
		$testRepo->save( $testUuid );
		$manager->commit();
	}
}
