Authentication 
==============


Users
-----

Main example user (login/password) - *Stanislav@gmail.com* / *Stanislav*  
Admin user (for backend/admin part of pullr app) - *Admin@gmail.com* / *Admin*


Development server
------------------
For password ask [@trerums](http://github.com/trerums)

    ssh root@188.226.219.80
    cd /var/www/pullr

To synchronize project with github repository type at project directory
    
    git pull

To make dev frontend and backend server work add this lines to your `hosts` file:

`188.226.219.80 dev.app.pullr.io`

`188.226.219.80 dev.admin.pullr.io`

**Frontend link** - http://dev.app.pullr.io

**Backend link**  - http://dev.admin.pullr.io
