<?php

namespace AlexandraDev\Model\Entity;

use VankoSoft\Alexandra\ODM\Entity\BaseEntity;

class Product extends BaseEntity
{
	
	public $productId;
	
	public $title;
	
	public $qty;
	
	public $price;
	
	public $categories;
}
