<?php

return array(
	'cassandra'    => array(
		'adapter'  => array(
			'driver'			=> 'datastax',
			'port'				=> 9042,
			'contact_points'	=> array('localhost'),
			'keyspace'			=> 'vanko_soft',
			'page_size'			=> 5000
		)
	)
);

