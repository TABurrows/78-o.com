#!/bin/sh
# This script automatically installs and configures MariaDB 10.

# copy repo file
#sudo cp /vagrant/mariadb.repo /etc/yum.repos.d/

# update
sudo apt-get update

# install mariadb
sudo apt-get -y install nginx unzip mariadb-server sqlite3 php-fpm php-mysql php-sqlite3 php-mbstring

# ensure it is running
#sudo /etc/init.d/mysql restart
sudo service mysql restart

# set db to auto start
sudo update-rc.d mysql defaults

# Create an install.txt file in the home directory
cd ~/
touch install.txt 

### post db install setup

# create a root password
RPWD=$(date | md5sum | sed -e 's/ -//g')
echo $RPWD

# Write out mariadb root details to install.txt file
echo "root user on db has password:" >> /root/install.txt
echo $RPWD >> /root/install.txt

# set root password
sudo /usr/bin/mysqladmin -u root password $RPWD

# grant root user privileges to all databases
sudo mysql -u root -p$RPWD -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '$RPWD' WITH GRANT OPTION; FLUSH PRIVILEGES;"

# create PRICE database
sudo mysql -u root -p$RPWD -e "CREATE DATABASE BITCOIN;"

# create the BITCOIN table
sudo mysql -u root -p$RPWD -e "CREATE TABLE BITCOIN.GBP (id int NOT NULL, p24h_avg int NULL, ask int NULL, bid int NULL, last int NULL, timestamp TEXT NULL, total_vol int NULL, PRIMARY KEY(id));"

# strip quoted table names from the export
sudo sed -i 's/\"GBP\"/GBP/g' /vagrant_data/bitcoindb.sql

# replace the badly named key with kee
#sudo sed -i 's/key/id/g' /vagrant_data/bitcoindb.sql

# amend sqlite3 transactions for mysql ones
sudo sed -i 's/BEGIN TRANSACTION/BEGIN/g' /vagrant_data/bitcoindb.sql

# comment out sqlite3 create syntax
sudo sed -i 's/CREATE TABLE/\#CREATE TABLE/g' /vagrant_data/bitcoindb.sql

# comment out sqlite3 create syntax
sudo sed -i 's/PRAGMA/\#PRAGMA/g' /vagrant_data/bitcoindb.sql

# import the data from sqlite3 fileName.db .dump >> bitcoindb.sql
sudo mysql -u root -p$RPWD BITCOIN < /vagrant_data/bitcoindb.sql

# sleep between 1-10 secs
#sleep $[ ( $RANDOM % 10 )  + 1 ]s

# create a redbean password
UPWD=$(date | md5sum | sed -e 's/ -//g')
echo $UPWD

# Write out mariadb root details to install.txt file
echo "redbean user on db has password:" >> /root/install.txt
echo $UPWD >> /root/install.txt

# create a user for use with the database
sudo mysql -u root -p$RPWD -e "GRANT SELECT, INSERT, UPDATE ON BITCOIN.GBP TO 'redbean'@'%' IDENTIFIED BY '$UPWD' WITH GRANT OPTION; FLUSH PRIVILEGES;" 

# restart
sudo /etc/init.d/mysql restart

# create an env for db connection password
if [ grep -q REDBEAN /etc/environment ]; then
    echo "REDBEAN already in /etc/environment";
else 
    sudo echo "REDBEAN=\"$UPWD\"" >> /etc/environment;
fi

# Install 78-o.com web directory
# make the destination folder
sudo mkdir /var/www/78-o.com

# change to temp directory
cd /tmp

#get the latest site release from github
wget https://github.com/TABurrows/78-o.com/archive/master.zip --output-document="78-o.com-master.zip"

#extract the site to tmp directory
unzip 78-o.com-master.zip -d /tmp

#remove the existing files in the web server root
sudo rm -Rf /var/www/78-o.com/*

#move public folder to web server root
sudo mv /tmp/78-o.com-master/public/* /var/www/78-o.com/


# Install redbeanphp
# make the destination lib folder
if [ -d /var/www/78-o.com/lib ]; then
    echo "/var/www/78-o.com/lib directory already exists"
else 
    sudo mkdir /var/www/78-o.com/lib
fi

# change to temp directory
cd /tmp

# download latest 
wget http://www.redbeanphp.com/downloadredbean.php --output-document="redbeanphp.tar.gz"

# unpack tarball
tar -zxvf redbeanphp.tar.gz

# move over to the destination directory
sudo mv /tmp/rb.php /var/www/78-o.com/lib/

# now write the nginx config for the site
# that will use this rig
# change to the tmp directory
cd /tmp

#create a file called 78-o.com
cat <<EOF > 78-o.com
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    root /var/www/78-o.com;
    index index.php index.html index.htm;

    server_name server_domain_name_or_IP;

    location / {
        add_header 'Access-Control-Allow-Origin' '*';
        try_files \$uri \$uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        fastcgi_param HTTP_HOST \$host;
    }

    location /api {
        add_header 'Access-Control-Allow-Origin' '*';
        try_files \$uri \$uri/ /index.php;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# move the config file to the nginx 
# sites-available directory
mv /tmp/78-o.com /etc/nginx/sites-available/

# remove the original default symbolic link
sudo rm /etc/nginx/sites-enabled/default

# create a new default symlink
sudo ln -s /etc/nginx/sites-available/78-o.com /etc/nginx/sites-enabled/78-o.com

#TODO configure the db
# copy the sqlite3 db
sudo cp /vagrant_data/.htBitcoin.db /var/www/78-o.com/api/price/bitcoin/

#set the permissions
sudo chown -R www-data:www-data /var/www/78-o.com

# restart the web server
sudo service php7.0-fpm restart
sudo service nginx restart

# update the installed software
sudo apt-get -y update
sudo apt-get -y upgrade

# restart the box
sudo shutdown -r now