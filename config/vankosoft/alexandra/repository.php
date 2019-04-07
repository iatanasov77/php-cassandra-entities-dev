<?php

return [
	'repository' => [
		'VankoSoft\AlexandraDev\Model\Entity\Product' => [
			'repository'    => '\VankoSoft\Alexandra\ODM\Repository\Repository',
			'entity'        => '\VankoSoft\AlexandraDev\Model\Entity\Product',
		    'table'     => 'products',
		    'columns'   => [
		        'product_id',
		        'title',
		        'qty',
		        'price',
		        'categories'
		    ]
		],
	    'VankoSoft\AlexandraDev\Model\Entity\TestUuid' => [
	        'repository'    => '\VankoSoft\Alexandra\ODM\Repository\Repository',
	        'entity'        => '\VankoSoft\AlexandraDev\Model\Entity\TestUuid',
	        'table'     => 'test_uuid',
	        'columns'   => [
	            //'uuid',
	            'name'
	        ]
	    ]
	]
];
