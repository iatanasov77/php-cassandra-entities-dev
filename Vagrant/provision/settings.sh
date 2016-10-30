#!/bin/bash

echo 'pathmunge /usr/local/bin' > /etc/profile.d/custom.sh
chmod +x /etc/profile.d/custom.sh
. /etc/profile
