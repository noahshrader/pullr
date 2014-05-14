Install
=====

####First steps

Select folder to locate pullr
* `git clone https://github.com/noahshrader/pullr`

Next step install less compiler
* `npm install -g less` 

Download composer (e.g. to you home folder, next home folder will be supposed)
* https://getcomposer.org/download/

Next from home folder run 
* `~/composer.phar update`

Next step from "pullr" directory
Select Yes for install under Development Environment
For All "index.php" type no
* ./init 



####Mac
If you use MAMP please enable "Allow Network access to MYSQL" 

And you should use "127.0.0.1" instead of localhost  in `common/config/main-local.php`

(For some reason connection to localhost via file is not working by default for pullr in MAC). 
