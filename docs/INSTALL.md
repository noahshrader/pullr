Install
=====

####First steps

Select folder to locate pullr
* `git clone https://github.com/noahshrader/pullr`

Next step install less compiler
* `npm install -g less` 

Download composer (e.g. to you home folder, next home folder will be supposed)
* https://getcomposer.org/download/

Next from pullr folder run 
* `~/composer.phar update`

Next step from "pullr" directory
Select Yes for install under Development Environment
For All "index.php" type no
* ./init 

Next step you should change you db connection settings in file
* `common/config/main.php`

Next step let's install db data, run from pullr project folder
* `./yii migrate`


####Update your local repository with new changes
To update files use your GUI App to sync or use that to run via terminal:   
* `git pull`

Sometimes you need to update db, so run that (first line to remove previous db data, second to apply db data again): 
```
./yii migrate/down 10
./yii migrate
```

####Mac
If you use MAMP please enable "Allow Network access to MYSQL" 

And you should use "127.0.0.1" instead of localhost  in `common/config/main-local.php`

(For some reason connection to localhost via file is not working by default for pullr in MAC). 


####Mac issues with lessc 
Some pathes issues exist when using lessc in Mac. Here we have a workaround. 

in common/config/main.php change from first line to second 

* `'less' => ['css', 'lessc {from} {to} --source-map --compress'],`
* `'less' => ['css', '/usr/local/bin/lessc {from} {to} --source-map --compress'],`


Next change first line of /usr/local/bin/lessc from first line to second 
* `#!/usr/bin/env node`
* `#!/usr/bin/env /usr/local/bin/node`

And last thing to check is **/Applications/MAMP/Library/bin/envars**. To prevent $DYLD_LIBRARY_PATH variables being changed by MAMP comment out two lines. So the file should looks like:

```
if test "x$DYLD_LIBRARY_PATH" != "x" ; then
#DYLD_LIBRARY_PATH="/Applications/MAMP/Library/lib:$DYLD_LIBRARY_PATH"
else
#DYLD_LIBRARY_PATH="/Applications/MAMP/Library/lib"
fi
export DYLD_LIBRARY_PATH
```