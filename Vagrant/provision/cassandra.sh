#!/bin/bash

# Install and start DataStax Cassandra
sudo yum -y install java
sudo cat /vagrant/Vagrant/etc/yum.repos.d/datastax.repo > /etc/yum.repos.d/datastax.repo
sudo yum -y install dsc20

systemctl enable cassandra
systemctl start cassandra

# Install PHP driver for Cassandra
sudo yum install gmp gmp-devel gmp-status libuv libuv-devel
sudo pecl install cassandra