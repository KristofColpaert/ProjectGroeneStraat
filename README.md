# Project Groene Straat

De basic connection settings voor de website.

## Webserver

* Link: http://groenestraat.azurewebsites.net
* Eigenlijke site: http://groenestraat.azurewebsites.net/abcdefghij
* Hostname (FTP): ftp://waws-prod-am2-025.ftp.azurewebsites.windows.net
* Username (FTP): groenestraat\groenestraat
* Password (FTP): Groenestraat123
* HTTP-folder: /site/wwwroot/

## MySQL-server

* Host: http://groenestraat.cloudapp.net/
* Root user: root
* Root password: Groenestraat123
* PHPMyAdmin: http://groenestraat.cloudapp.net/phpmyadmin/

**Problemen met MySQL: in file */etc/mysql.conf.d/mysqld.cnf* binding doen naar het private interne IP-adres van de server via: *100.113.10.50*.**
