#!/bin/bash

printenv | grep -v "no_proxy" >> /etc/environment

exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf