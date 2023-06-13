# Install Redis Server ================================================================
$ sudo apt-get update 
$ sudo apt-get upgrade
=> Step 2 – Installing Redis
$ sudo apt-get install redis-server
$ sudo systemctl enable redis-server.service
=> Step 3 – Configure Redis
sudo vim /etc/redis/redis.conf

maxmemory 256mb max
memory-policy allkeys-lru

=> Step 4 – Test Redis Connection
$ redis-cli
127.0.0.1:6379> ping PONG 127.0.0.1:6379>
# ================================================================


# Install PHP extension for redis ================================================================
$ sudo apt-get install php-redis
# ================================================================

# Install Laravel Hirozon Package ================================================================
$ composer require predis/predis
$ composer require laravel/horizon
$ php artisan horizon:install
$ php artisan horizon:publish

See: https://laravel.com/docs/9.x/horizon

# ================================================================

# Modified .env file: ================================================================

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=redis
REDIS_CLIENT=predis
SESSION_DRIVER=file
SESSION_LIFETIME=120
# ================================================================

Queue Workers on a Live Server
All the examples we’ve shown so far should work and on either a local host or a live server. However, on a live server, the administrator can't constantly check whether the queue workers are active and run the php artisan queue:work command when necessary, especially if the project is international and involves users from different time zones. What happens when a worker encounters an error or an execution timeout? Your application queue workers must be active 24 hours per day and react every time a user performs a specific action. On a live server, a process monitor is needed. This monitor controls the queue workers and automatically restarts processes if they fail or are impacted by other processes. A supervisor is commonly used on Linux server. The first step of installing a supervisor on a live server is to run the following command:

sudo apt-get install supervisor

After installation, in the /etc/supervisor/conf.d directory, prepare a configuration laravel-worker.conf file with the following content:

RUN command : sudo nano /etc/supervisor/conf.d/laravel-worker.conf

Add the following script:
# ================================================================
[program:horizon]
process_name=%(program_name)s
command=php /var/www/backend-v3/artisan horizon
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=/var/www/backend-v3/horizon.log
stopwaitsecs=3600
# ================================================================

All directories depend on your server structure. After the configuration files are created, you’ll need to activate the supervisor using the following commands:

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start horizon