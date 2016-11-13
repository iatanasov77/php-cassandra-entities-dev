<?php

class QueryBuilder
{
	public function select( $table, $columns, $where )
	{
		$query	= sprintf( "SELECT %s FROM %s", implode( ', ', $columns ), $tableName );
		
		if ( is_array( $where ) && ! empty( $where ) )
		{
			$where	=  implode( ' AND ', array_map(
					function ( $v, $k ) { return sprintf( "%s = :%s", $k, $v ); },
					$keys,
					$keys
					) );
		
			$query	.= ' WHERE ' .$where;
		}
			
		return $query;
	}
	
	public function insert( $table, $valueMap )
	{
		
	}
	
	public function update( $table, $valueMap )
	{
		
	}
	
	public function delete( $table, $where )
	{
		
	}
}