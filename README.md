<img src="https://brtoworldagency.com/github/logo-mynotebook.png" width="300">
<img src="https://media4.giphy.com/media/g4G27DgZwqaK8qykXS/giphy.gif?cid=6c09b952bg0lvyebl1dpa6ht4o53xjnjkefz3sqxq77p8oyi&ep=v1_gifs_search&rid=giphy.gif&ct=s" width="40">Online project at [MyNotebook by CloudTools](https://mynotebook.cloudtools.be)

###### THIS APPLICATION IS STILL UNDER DEVELOPMENT, SOME ACTIONS MAY NOT WORK OR HAVE STOPPED WORKING OR EXISTING AS THE APPLICATION DEVELOPS. WE RECOMMEND ITS USE ONLY FOR PERSONAL USE. WE REMOVE ANY COMMITMENT TO FUNCTIONALITY, AS WELL AS CONSTANT UPDATES TO RESOLVE PROBLEMS. THE PROJECT IS OPEN SOURCE, THEREFORE YOU CAN MAKE YOUR OWN UPDATES AND CONTRIBUTE TO THE FUNCTIONING OF THE APPLICATION.

# MyNotebook
MyNotebook is a revolutionary innovation that combines the practicality of a digital notebook with the functionality of a calendar. Designed to meet the needs of modern users, this notebook offers an efficient and organized way to keep track of your notes, tasks and appointments over time.

## Getting Started
To use MyNotebook, you need an Apache/2.4.53 server, PHP7.4 or higher, OpenSSL/1.1.1n and a MariaDB/MySQL database. I recommend using XAMPP or WampServer.

You should clone our repository into the server’s initial folder:

To find the initial folder in XAMPP, just follow this sequence:

``C:\xampp\htdocs``

To find the initial folder in WampServer, just follow this sequence:

``C:\wamp\www``

Now you need to upload our initial SQL file, it will create the entire database, as well as its tables, settings and necessary privileges.

[Download initial SQL file](https://mynotebook.cloudtools.be/download/project/initial-sql.sql)

## Turn on the server
After downloading, it will be necessary to upload, so it will be necessary to turn on the server. 

For this, you can access the documentation provided by the companies that created them. 

[XAMPP Documentation](https://www.apachefriends.org/docs/) 

[WampServer Documentation](https://www.wampserver.com/en/category/documentation-en/) 

Access your browser and upload the SQL file at: 

``localhost/phpmyadmin/index.php?route=/server/import``

## You are free, start whenever you want
Now, access the folder you created for your notebook. 

In my case, I kept the original name of the application, so I will access: 

``localhost/mynotebook/`` 

You will fall into the current month and see a calendar, choose a day and start typing. Don’t worry, saving is automatic and dynamic. 

Remember, if you uninstall the server or reset the computer, remember to back up your database.

You can help us improve MyNotebook, tell us how 😀😍
