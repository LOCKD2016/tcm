#!/bin/bash

#创建目录
mkdir -p  config/php7session  config/logs

chown -R www:www config/php7session

#构建docker
docker-compose up -d
