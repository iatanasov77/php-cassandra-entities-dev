<?php

$schemaPath = '/vagrant/webroot/schema.json';
$schema     = file_exists( $schemaPath ) ? json_decode( file_get_contents( $schemaPath ), true ) : [];

return [
    'schema'	=> $schema
];
