#!/bin/bash

echo "Provsioning guest with httpd config..."

# Set document root to /vagrant
if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant /var/www
fi
