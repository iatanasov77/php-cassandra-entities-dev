<?php

return array(
	'schema'	=> array(

		'products' => array(
			'columns' => array(
				'product_id'	=> array( 'type' => 'int' ),
				'title'			=> array( 'type' => 'varchar' ),
				'qty'			=> array( 'type' => 'int' ),
				'price'			=> array( 'type' => 'float' ),
				'categories'	=> array( 'type' => 'set', 'valueType' => 'int' )
			),
			'partition_keys'	=> array( 'product_id' ),
			'clustering_keys'	=> array()
		),
		
		'categories' => array(
			'columns' => array(
				'category_id'	=> array( 'type' => 'int' ),
				'title'			=> array( 'type' => 'varchar' ),
				'parent_id'		=> array( 'type' => 'int' ),
			),
			'partition_keys'	=> array( 'category_id' ),
			'clustering_keys'	=> array()
		)
	)
);
