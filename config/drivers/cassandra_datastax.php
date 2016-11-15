<?php

return array(
	'cassandra'    => array(
		'adapter'  => array(
			'driver'			=> 'datastax',
			'port'				=> 9042,
			'contact_points'	=> array('localhost'),
			'keyspace'			=> 'vs_dev',
			'page_size'			=> 5000
		)
	)
);

