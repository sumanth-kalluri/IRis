#!/bin/sh

#Install LAMP Server (Apache 2, PHP, MySQL)
sudo apt update
sudo apt --assume-yes install apache2
sudo apt --assume-yes install mysql-server
sudo apt --assume-yes install php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml libapache2-mod-php

#Install PyQt4
sudo apt-get install python-qt4

#Make Directory In /var/www/html/
sudo mkdir /var/www/html/Login_v2/
sudo chmod 777 -R /var/www/html/Login_v2

#Copy All Files To Apache2 Directory
cp -R ./Login_v2/* /var/www/html/Login_v2/

#Edit .bashrc and rc.local files
