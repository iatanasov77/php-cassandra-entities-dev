#!/bin/bash

echo "Provsioning guest with custom packages..."

# Upgrade YUM
#sudo yum -y update
#sudo yum -y upgrade

# Install Packages
sudo yum -y install mc gitflow redhat-lsb-core

# Install VankoSoft PhpDevTools - mkvhost
wget http://vankosoft.babymarket.bg/download/MkVhost.phar
chmod +x MkVhost.phar
sudo mv MkVhost.phar /usr/local/bin/mkvhost

# Install VankoSoft PhpDevTools - bumpversion
wget https://raw.github.com/iatanasov77/php-dev-tools/develop/bumpversion.php
chmod +x bumpversion.php
sudo mv bumpversion.php /usr/local/bin/bumpversion
