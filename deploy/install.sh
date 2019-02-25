#!/bin/bash
curl -fsSL https://get.docker.com/ | sh
useradd www
usermod -aG docker www
apt install -y git
curl -L https://github.com/docker/compose/releases/download/1.21.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
docker-compose --version
curl -sSL https://get.daocloud.io/daotools/set_mirror.sh | sh -s http://45bf300f.m.daocloud.io

