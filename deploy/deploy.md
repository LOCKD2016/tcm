# 安装过程
```
yum install -y yum-utils
yum-config-manager \
    --add-repo \
    https://download.docker.com/linux/centos/docker-ce.repo
yum-config-manager --enable docker-ce-stable
yum list docker-ce.x86_64  --showduplicates |sort -r
yum install -y docker-ce-17.03.1.ce-1.el7.centos
systemctl start docker
systemctl enable docker.service
useradd www
yum install -y git
curl -L https://github.com/docker/compose/releases/download/1.13.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
docker-compose --version
mkdir -p /data/www
cd /data/www
git clone https://git.coding.net/bjvmingnet/dangwen.git
cd /data/www/dangwen
chown -R www:www storage bootstrap

cd /data/www/dangwen/deploy
./deploy.sh

docker exec -ti php7_min sh
composer install --no-dev
php artisan key:generate
php artisan passport:keys
php artisan migrate:refresh
php artisan db:seed

定时任务
* * * * *  docker exec php7_min  php /data/www/dangwen/artisan schedule:run >> /dev/null 2>&1
```