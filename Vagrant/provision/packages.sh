#!/bin/bash

echo "Provsioning guest with custom packages..."

# Upgrade YUM
#sudo yum -y update
#sudo yum -y upgrade

# Install Packages
sudo yum -y install mc gitflow redhat-lsb-core

# Install VankoSoft PhpDevTools - mkvhost
wget https://github.com/iatanasov77/mkvhost/releases/download/0.2.2/mkvhost.phar
chmod +x mkvhost.phar
sudo mv mkvhost.phar /usr/local/bin/mkvhost

# Install VankoSoft PhpDevTools - bumpversion
wget https://raw.githubusercontent.com/iatanasov77/bumpversion/v0.1.0/bumpversion.php
chmod +x bumpversion.php
sudo mv bumpversion.php /usr/local/bin/bumpversion
