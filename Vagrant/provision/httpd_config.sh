#!/bin/bash

echo "Provsioning guest with httpd config..."

sudo php /usr/local/bin/mkvhost -spcedev.lh -d/vagrant/webroot -tsimple -f
