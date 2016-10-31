#!/bin/bash

# Install and start DataStax Cassandra
sudo yum -y install java
sudo cat /vagrant/Vagrant/etc/yum.repos.d/datastax.repo > /etc/yum.repos.d/datastax.repo
sudo yum -y install dsc21

systemctl enable cassandra
systemctl start cassandra

# Install PHP driver for Cassandra
sudo yum -y install gmp gmp-devel gmp-status libuv libuv-devel

#sudo rpm -ivh /vagrant/Vagrant/rpm/libuv-1.8.0-1.el7.centos.x86_64.rpm
#sudo rpm -ivh /vagrant/Vagrant/rpm/libuv-devel-1.8.0-1.el7.centos.x86_64.rpm
sudo rpm -ivh /vagrant/Vagrant/rpm/cassandra-cpp-driver-2.4.3-1.el7.centos.x86_64.rpm
sudo rpm -ivh /vagrant/Vagrant/rpm/cassandra-cpp-driver-devel-2.4.3-1.el7.centos.x86_64.rpm

sudo pecl install cassandra
sudo echo ";Enable Cassandra module\n;This file created from Vagrant provision\nextension=cassandra.so" > /etc/php.d/cassandra.ini
