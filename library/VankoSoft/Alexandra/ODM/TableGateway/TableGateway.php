<?php

class TableGateway implements TableGatewayInterface
{
	protected $tableName;
	
	protected $tableMeta;
	
	protected $dbAdapter;
	
	protected $queryBuilder;
	
	public function __construct( $tableName, $tableMetaConfig, $dbAdapter )
	{
		$this->tableName	= $tableName;
		
		$this->tableMeta	= $tableMetaConfig->get( $tableName );
		
		$this->dbAdapter	= $dbAdapter;
		
		$this->queryBuilder	= new QueryBuilder;
	}
	
	public function select( array $params = array() )
	{
		$cql	= $this->queryBuilder->select( );
		
		$rows	= $this->dbAdapter->query( $cql, $params );
		
		return $rows;
	}
	
	public function insert( $valueMap )
	{
		
	}
}
