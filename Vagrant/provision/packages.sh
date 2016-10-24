#!/bin/bash

echo "Provsioning guest with custom packages..."

# Upgrade YUM
#sudo yum -y update
#sudo yum -y upgrade

# Install Packages
sudo yum -y install mc
sudo yum -y install gitflow

# Install VankoSoft PhpDevTools - mkvhost
wget https://raw.github.com/iatanasov77/php-dev-tools/master/mkvhost.php
chmod +x mkvhost.php
sudo mv mkvhost.php /usr/local/bin/mkvhost

# Install VankoSoft PhpDevTools - bumpversion
wget https://raw.github.com/iatanasov77/php-dev-tools/master/bumpversion.php
chmod +x bumpversion.php
sudo mv bumpversion.php /usr/local/bin/bumpversion

# Install and start DataStax Cassandra
sudo yum -y install java
sudo cat /vagrant/Vagrant/etc/yum.repos.d/datastax.repo > /etc/yum.repos.d/datastax.repo
sudo yum -y install dsc20
sudo systemctl enable cassandra
sudo systemctl start cassandra
