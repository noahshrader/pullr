Install
=====

####First steps

Select folder to locate pullr  
`git clone https://github.com/noahshrader/pullr`

Download composer (e.g. to you home folder, next home folder will be supposed)  
`https://getcomposer.org/download/`

Plugin for composer  
`~composer.phar global require "fxp/composer-asset-plugin:1.0.*@dev"`

Next from pullr folder run  
`~/composer.phar update`

Next step from "pullr" directory. 
Select "yes" for install under Development Environment  
`./init `

Next step you should change your db connection settings in file  
`common/config/main-local.php`

Next step let's load tables and sampe db data, run from pullr project folder  
`./makesample.sh`

Next we will load js libraries and npm packages we need  
`npm install`

####Update your local repository with new changes
To update files use your GUI App to sync or use that to run via terminal:   
`git pull`

Sometimes you need to update db, so run that (that will remove all tables and load data sample data again): 
`./makesample.sh`

####Useful commands
* `./yii pullr/purge` - will remove all tables from db
* `./yii migrate` - will load table's sheme at db
* `./yii theme/rescan` - will update themes
* `npm install` - that will update npm & js dependencies
* `npm test` - unit tests for streamboard
* `npm run-script protractor` e2e tests for streamboard

####Mac
If you use MAMP please enable "Allow Network access to MYSQL" 

And you should use "127.0.0.1" instead of localhost  in `common/config/main-local.php`

(For some reason connection to localhost via file is not working by default for pullr in MAC). 

####Mac issue with mcrypt 
To install mcrypt extension php in case you have not it these link can be helpful: 

http://topicdesk.com/downloads/mcrypt/mcrypt-download

All that is left is to enable the extension by editing /etc/php.ini. If this file is not present, copy /etc/php.ini.default and rename it:

* sudo cp /etc/php.ini.default /etc/php.ini

Edit the /etc/php.ini file and add the following:

* extension=mcrypt.so

If you are installing to OSX Yosemite, here's a workaround for mcrypt:

* http://coolestguidesontheplanet.com/install-mcrypt-php-mac-osx-10-10-yosemite-development-server/
