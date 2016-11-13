<?php


interface HydratorInterface
{
	public function extract( BaseEntity $entity );
	
	public function hydrate( BaseEntity $entity, array $data );
}