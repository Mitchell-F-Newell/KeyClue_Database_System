CPSC471 Final Project
ZOO KeyClue Database System

Passwords for this system are hash encrypted in php so you will not be able to insert a new account through MySQL workbench or another
method. For the sake of testing if you require access to the system, the following credentials will provide administrator access:
Username: JBrintnell
Password: Brintnell

This project is coded in php, js, html, and css.
During development and during presentation the system was being run off of an Apache server with the database running on a MySQL database
with InnoDB serving as the database connector.

The folders in this project indicate the pages contained within them. The exceptions are files, images, and tools.
Files contains uploaded files that exsist in the database, it is also the location where uploaded images are saved.
Images contains the image sources for the website to display.
Tools contains files frequently called by multiple pages (ie. connection to the database, logout from the system)

For the purpose of commenting this code, php and js files will be completely commented, however html and css files will only be commented
where required as they are only used for styling the display and the exact purpose of each line can be determined by simply looking at the
page.

Additionally, anytime a user does not have permission to access a page or perform an action, the user will be redirected back to the
competition years page (or the login page if they are not logged in).

If you are going to run this project, make sure you change the php.ini and increase the post_max_size to 200M and the
upload_max_filesize to 200M