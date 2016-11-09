#!/bin/bash

echo "Provsioning guest with httpd config..."

mkvhost -spcedev.dev -d/vagrant/webroot -tsimple -f
