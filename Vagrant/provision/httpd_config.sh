#!/bin/bash
echo "Provsioning guest with httpd config..."

# Install Cassandra Cluster Admin
git clone https://github.com/iatanasov77/Cassandra-Cluster-Admin.git /home/vagrant/www/CassandraClusterAdmin
sudo php /usr/local/bin/mkvhost -scassandra.pcedev.lh -d/home/vagrant/www/CassandraClusterAdmin -tsimple -f

sudo php /usr/local/bin/mkvhost -spcedev.lh -d/vagrant/webroot -tsimple -f
