#!/usr/bin/env bash

#* * * * * /data/www/tcm/deploy/check.sh >/dev/null 2>&1
for((i=1;i<=60;i++));do
docker exec php7_min  php /data/www/tcm/artisan check:token >/dev/null 2>&1 &
sleep 1
done