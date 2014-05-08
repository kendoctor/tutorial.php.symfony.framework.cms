#Explore symfony framework (version ~2.4.*) via a Simple CMS project

##First of all, we need setup a symfony framework project

	As you have already  setup a php development environment(apache+php+mysql).
	
	Then we will go on.

	Here, we will use composer to create this project, so you need download a composer.
	NOTE: You should stay in a directory which you have write permission.
	
	$ curl -sS https://getcomposer.org/installer | php

	If you want this composer work in global, move it
	
	$ sudo mv composer.phar /usr/bin/composer

	Then you can issue composer everywhere.

	OK. Go on.

	$ composer create-project symfony/framework-standard-edition symfony-cms 2.4.*

	Waiting for download to be completed.
	When in the end, this framework version will prompt you to enter some basic configurations. I think you can do that.
	
	*database configuration
	*mail configuration
	*locale
	*cookie secret
	

	Next. we need clear cache and grant write permissions of  two directories : cache and logs to apache user and your current account.
	
	$ rm -rf app/cache/*
	$ rm -rf app/logs/*

	For mac users
	
	$ HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
	$ sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
	$ sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
	
	For ubuntu users
	$ HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
	$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
	$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

	
