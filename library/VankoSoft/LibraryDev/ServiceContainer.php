<?php
/**
 * Created by PhpStorm.
 * User: Vanko
 * Date: 10/16/2016
 * Time: 18:15
 */

namespace VankoSoft\LibraryDev;

use VankoSoft\Cassandra\ODM\EntityManager;


class ServiceContainer
{
    protected $services;

    public function __construct()
    {
        $this->services = array();

        $this->services['entity_manager']   = new EntityManager();
    }

    public function get( $serviceName )
    {
        if ( ! isset( $this->services[$serviceName] ) )
        {
            throw new \InvalidArgumentException( 'Service not exists.' );
        }

        return $this->services[$serviceName];
    }
}