<?php

return array(
	'connection'	=> array(
		'datastax'	=> array(
			'driver'			=> 'datastax',
			'port'				=> 9042,
			'contact_points'	=> array( 'localhost' ),
			'keyspace'			=> 'vs_dev',
			'page_size'			=> 5000
		),
		'evseevnn'	=> array(
			'driver'			=> 'evseevnn',
			'port'				=> 9042,
			'contact_points'	=> array( 'localhost' ),
			'keyspace'			=> 'vs_dev',
			'page_size'			=> 5000
		)
	)
);
