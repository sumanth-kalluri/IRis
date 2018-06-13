#!/bin/bash

#	Install Script For IRis Interface on Raspberry Pi
#	Author : Nishad Mandlik
#	Organization : WhizMantra Educational Services
#	Purpose : Jharkhand Gorvernment Project Under Deputy Development Commissioner (DDC), Ranchi


#Install LAMP Server (Apache 2, PHP, MySQL)
sudo apt update
sudo apt --assume-yes install apache2
sudo apt --assume-yes install mysql-server
sudo apt --assume-yes install php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml libapache2-mod-php
sudo apt --assume-yes install phpmyadmin
sudo ln -s /usr/share/phpmyadmin /var/www/html

#Install PyQt4
sudo apt-get install python-qt4

#Make Directory In /var/www/html/
sudo mkdir /var/www/html/Login_v2/

#Copy All Files To Apache2 Directory
cp -R ./Login_v2/* /var/www/html/Login_v2/

#Own The Site Directory
sudo chown -R $(whoami) /var/www/html/Login_v2

#Change Permissions to 777
sudo chmod 777 -R /var/www/html/Login_v2

#Ask User For Password Of MySQL & PHPMyAdmin
read -sp 'Please Enter The Password Used For MySQL: ' userPass

#Move To Next Line
echo

#Change The Password In The DB Config File
sed -i "4s/.*/define('db_password', '$userPass');/" /var/www/html/Login_v2/database/db_connect.php

#Configure MySQL from phpmyadmin or terminal(preferably)

#Edit .bashrc and rc.local files

#/home/$(whoami)/.bashrc
#Added By IRis
BASHRC_COMMAND="/usr/bin/python /var/www/html/Login_v2/home/pythonFiles/testDetect.py"
RCLOCAL_COMMAND1="sudo service apache2 start"
RCLOCAL_COMMAND2="rm /var/www/html/Login_v2/home/pythonFiles/running"

#Check If Command Already Exists in ~/.bashrc
if grep -Fxq "$BASHRC_COMMAND" /home/$(whoami)/.bashrc
then
	echo "Command Already Exists In ~/.bashrc"		
	# Do Nothing
else
	# Add To File
	echo "Command Does Not Exist In ~/.bashrc"
	echo "Adding Command To ~/.bashrc"
	echo >> /home/$(whoami)/.bashrc
	echo "#Added By IRis" >> /home/$(whoami)/.bashrc
	echo "$BASHRC_COMMAND" >> /home/$(whoami)/.bashrc
	echo "Command Added To ~/.bashrc"	
fi

#Check If Command 1 Already Exists in /etc/rc.local
if grep -Fxq "$RCLOCAL_COMMAND1" /home/$(whoami)/.bashrc
then
	echo "Command 1 Already Exists In /etc/rc.local"		
	# Do Nothing
else
	# Add To File
	echo "Command 1 Does Not Exist In  /etc/rc.local"
	echo "Adding Command 1 To /etc/rc.local"
	sudo sed "\$i \ \ " /etc/rc.local | sudo tee /etc/rc.local_bak
	sudo sed "\$i #Added By IRis" /etc/rc.local_bak | sudo tee /etc/rc.local
	sudo sed "\$i $RCLOCAL_COMMAND1" /etc/rc.local | sudo tee /etc/rc.local_bak
	sudo sed "\$i \ \ " /etc/rc.local_bak | sudo tee /etc/rc.local
	sudo rm /etc/rc.local_bak
	echo "Command 1 Added To /etc/rc.local"	
fi

#Check If Command 2 Already Exists in /etc/rc.local
if grep -Fxq "$RCLOCAL_COMMAND2" /home/$(whoami)/.bashrc
then
	echo "Command 2 Already Exists In /etc/rc.local"		
	# Do Nothing
else
	# Add To File
	echo "Command 2 Does Not Exist In  /etc/rc.local"
	echo "Adding Command 2 To /etc/rc.local"
	udo sed "\$i \ \ " /etc/rc.local | sudo tee /etc/rc.local_bak
	sudo sed "\$i #Added By IRis" /etc/rc.local_bak | sudo tee /etc/rc.local
	sudo sed "\$i $RCLOCAL_COMMAND2" /etc/rc.local | sudo tee /etc/rc.local_bak
	sudo sed "\$i \ \ " /etc/rc.local_bak | sudo tee /etc/rc.local
	sudo rm /etc/rc.local_bak
	echo "Command 2 Added To /etc/rc.local"	
fi



