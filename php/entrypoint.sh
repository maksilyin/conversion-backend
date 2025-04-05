#!/bin/bash

printenv | grep -v "no_proxy" >> /etc/environment

echo "[entrypoint] Обновляем вирусные базы ClamAV..."
freshclam

echo "[entrypoint] Запускаем ClamAV daemon..."
mkdir -p /var/run/clamav
chown clamav:clamav /var/run/clamav
clamd &

echo "[entrypoint] Запускаем Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf