Queue Workers on a Live Server
All the examples we’ve shown so far should work and on either a local host or a live server. However, on a live server, the administrator can't constantly check whether the queue workers are active and run the php artisan queue:work command when necessary, especially if the project is international and involves users from different time zones. What happens when a worker encounters an error or an execution timeout? Your application queue workers must be active 24 hours per day and react every time a user performs a specific action. On a live server, a process monitor is needed. This monitor controls the queue workers and automatically restarts processes if they fail or are impacted by other processes. A supervisor is commonly used on Linux server. The first step of installing a supervisor on a live server is to run the following command:

sudo apt-get install supervisor

After installation, in the /etc/supervisor/conf.d directory, prepare a configuration laravel-worker.conf file with the following content:

RUN command : sudo nano /etc/supervisor/conf.d/laravel-worker.conf

Add the following script:
# ================================================================
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/app.com/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/app.com/worker.log
stopwaitsecs=3600
# ================================================================

All directories depend on your server structure. After the configuration files are created, you’ll need to activate the supervisor using the following commands:

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*