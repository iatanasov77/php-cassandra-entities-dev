#!/bin/bash

# Install Cassandra Cluster Admin
git clone https://github.com/sebgiroux/Cassandra-Cluster-Admin.git /home/vagrant/www/CassandraClusterAdmin
sudo php /usr/local/bin/mkvhost -scassandra.pcedev.lh -d/home/vagrant/www/CassandraClusterAdmin -tsimple -f

echo "Provsioning guest with httpd config..."

sudo php /usr/local/bin/mkvhost -spcedev.lh -d/vagrant/webroot -tsimple -f
