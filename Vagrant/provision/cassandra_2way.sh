#!/bin/bash

sudo yum update
sudo yum -y install automake cmake gcc gcc-c++ git libtool openssl-devel wget gmp gmp-devel boost php-devel pcre-devel git
pushd /tmp
wget http://dist.libuv.org/dist/v1.8.0/libuv-v1.8.0.tar.gz
tar xzf libuv-v1.8.0.tar.gz
pushd libuv-v1.8.0
sh autogen.sh
./configure
sudo make install
popd
popd
sudo curl http://downloads.datastax.com/cpp-driver/centos/7/dependencies/libuv/v1.8.0/libuv-1.8.0-1.el7.centos.x86_64.rpm
sudo curl http://downloads.datastax.com/cpp-driver/centos/7/dependencies/libuv/v1.8.0/libuv-devel-1.8.0-1.el7.centos.x86_64.rpm
sudo curl http://downloads.datastax.com/cpp-driver/centos/7/cassandra/v2.4.3/cassandra-cpp-driver-2.4.3-1.el7.centos.x86_64.rpm
sudo curl http://downloads.datastax.com/cpp-driver/centos/7/cassandra/v2.4.3/cassandra-cpp-driver-devel-2.4.3-1.el7.centos.x86_64.rpm
sudo rpm -ivh libuv-1.8.0-1.el7.centos.x86_64.rpm
sudo rpm -ivh libuv-devel-1.8.0-1.el7.centos.x86_64.rpm
sudo rpm -ivh cassandra-cpp-driver-2.4.3-1.el7.centos.x86_64.rpm
sudo rpm -ivh cassandra-cpp-driver-devel-2.4.3-1.el7.centos.x86_64.rpm
sudo yum update
sudo pecl install cassandra
sudo yum update
