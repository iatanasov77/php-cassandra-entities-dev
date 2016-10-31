<?php

return array(
	'services'	=> array(

		'cassandra_adapter'	=> array(
			'factory'		=> '\VankoSoft\Cassandra\DBAL\AdapterFactory',
			'params'		=> array( 'config' => '%cassandra.adapter' )
		),
		
		'entity_manager' => array(
			'class'		=> '\VankoSoft\Cassandra\ODM\EntityManager',
			'params'	=> array ( 'adapter' => '&cassandra_adapter' )
		)
		
	)
);
