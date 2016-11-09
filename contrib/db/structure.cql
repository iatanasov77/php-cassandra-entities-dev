DROP KEYSPACE IF EXISTS vs_dev;
CREATE KEYSPACE vs_dev WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 3 };
USE vs_dev;

CREATE TABLE products (
  product_id	int,
  title 	varchar,
  qty   	int,
  price		float,
  categories	set< int >,
  PRIMARY KEY ( product_id )
);

CREATE TABLE categories (
  category_id	int,
  title		varchar,
  PRIMARY KEY ( category_id )
);



