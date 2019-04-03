#!/bin/bash

# Install dependencies
sudo yum -y install java libtool openssl-devel
#sudo cat /vagrant/Vagrant/etc/yum.repos.d/datastax.repo > /etc/yum.repos.d/datastax.repo
sudo bash -c 'cat /vagrant/Vagrant/etc/yum.repos.d/datastax.repo >/etc/yum.repos.d/datastax.repo'

# Install and start DataStax Cassandra
sudo yum -y install dsc21
sudo systemctl enable cassandra
sudo systemctl start cassandra

# Install PHP driver for Cassandra
sudo yum -y install gmp gmp-devel gmp-status

wget http://downloads.datastax.com/cpp-driver/centos/7/dependencies/libuv/v1.24.0/libuv-1.24.0-1.el7.x86_64.rpm
wget http://downloads.datastax.com/cpp-driver/centos/7/dependencies/libuv/v1.24.0/libuv-devel-1.24.0-1.el7.x86_64.rpm
wget http://downloads.datastax.com/cpp-driver/centos/7/cassandra/v2.11.0/cassandra-cpp-driver-2.11.0-1.el7.x86_64.rpm
wget http://downloads.datastax.com/cpp-driver/centos/7/cassandra/v2.11.0/cassandra-cpp-driver-devel-2.11.0-1.el7.x86_64.rpm

sudo rpm -ivh libuv-1.24.0-1.el7.x86_64.rpm
sudo rpm -ivh libuv-devel-1.24.0-1.el7.x86_64.rpm
sudo rpm -ivh cassandra-cpp-driver-2.11.0-1.el7.x86_64.rpm
sudo rpm -ivh cassandra-cpp-driver-devel-2.11.0-1.el7.x86_64.rpm

sudo pecl install cassandra
sudo echo -e ";Enable Cassandra module\n;This file created from Vagrant provision\nextension=cassandra.so" > /etc/php.d/cassandra.ini
sudo service httpd restart

# Ако не работи инсталирай Cassandra така за твоята дистрибуция и според твоята PHP версия
#################################################################################################
#wget http://downloads.datastax.com/php-driver/centos/7/cassandra/v1.3.2/php70w-cassandra-driver-1.3.2stable-1.el7.centos.x86_64.rpm
#sudo rpm -ivh php70w-cassandra-driver-1.3.2stable-1.el7.centos.x86_64.rpm
#sudo service httpd restart
#################################################################################################

# Create database structure and add demo data
cqlsh -f /vagrant/contrib/db/structure.cql
cqlsh -f /vagrant/contrib/db/data.cql
