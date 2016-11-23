<?php

// Controller services
$container['controller.default'] = function ( $c )
{
	return new \VankoSoft\AlexandraDev\Controller\DefaultController( $c );
};

// Routes
$app->get('/', 'controller.default:index' );
