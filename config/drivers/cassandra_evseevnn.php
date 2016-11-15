<?php

return array(
	'cassandra'    => array(
		'adapter'  => array(
			'driver'			=> 'evseevnn',
			'port'				=> 9042,
			'contact_points'	=> array('localhost'),
			'keyspace'			=> 'vs_dev',
			'page_size'			=> 5000
		)
	)
);
