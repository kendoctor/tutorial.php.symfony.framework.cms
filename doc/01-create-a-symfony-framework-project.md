#Create a Symfony Framework project

First of all, we need setup a symfony framework project

As you have already setup a php development environment(apache + php + mysql).
	
Then we will go on.

Here, we will use composer to create this project, so you need download a composer.

**NOTE**: You should stay in a directory which you have a write permission.

```	
$ curl -sS https://getcomposer.org/installer | php
```

If you want this composer work in global, move it

```
$ sudo mv composer.phar /usr/bin/composer
```

Then you can issue composer everywhere.

OK. Go on.

```
$ composer create-project symfony/framework-standard-edition symfony-cms 2.4.*
```

Waiting for download to be completed.

When in the end, this framework version will prompt you to enter some basic configurations. I think you can do that.
	
* database configuration
* mail configuration
* locale
* cookie secret
	

Next. we need clear cache and grant write permissions of  two directories : cache and logs to apache user and your current account.

```
$ rm -rf app/cache/*
$ rm -rf app/logs/*
```

For mac users

```
$ HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
$ sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```

For ubuntu users

```
$ HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```

When you finished these scripts above, we need run this project on apache.
Here, we will create a virtual host on our dev machine for symfony-cms.com

If mac users

```
$ sudo vim /etc/apache2/extra/httpd-vhosts.conf
```

Append a virtual host for symfony-cms project.

Also you need append a mapping to /etc/hosts for servername with symfony-cms.com

**NOTE**: if you are the first time to create virtual host, you will also want to uncommet
`Include .. httpd-vhosts` in /etc/apache2/httpd.conf file.

Then restart apache server

```
$ sudo apachectl restart
```

If ubuntu users

```
$ sudo cp /etc/apache2/site-availabe/defaut /etc/apache2/site-available/symfony-cms.com
```

Then you will also need append a virtual host for symfony-cms.
Also append a host mapping in /etc/hosts just like above.
Then you enable your virtual host as following

```
$ sudo a2ensite symfony-cms.com
$ sudo service apache2 restart
```
	
When all have been done, we can open browser and type symfony.com/app_dev.php
to open symfony framework default demo application in dev environment.

**NOTE**: this demo only can be run in dev mode.

If the demo page correctly displayed, you have secceeded.
Also we check you php environment whether fully suited for symfony framework project or not.
In your project root folder

```
$ php app/check.php
```

If there are only some warnings, no errors, it does not matter.
Also you can do some work to make it perfect.
	
Until now, we have already setup a symfony framework project.

Next section, we will explore symfony's MVC.
	

	
