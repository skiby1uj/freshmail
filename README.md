# Freshmail

###Create project

If you have run application you must clone is with repository. 
Open console in directory where you have create project and write:

    git clone https://github.com/skiby1uj/freshmail.git

Go to project (cd freshmail) and write:

    composer install

###Use application in website

Now you can run project. You write in console:

    php bin/console s:r

In your console you see where you open application.

###Use application in CLI

In your console you must write:

    php bin/console app:discount-code --numberOfCodes x --lengthOfCode y --file z

#####Where:

    x - is integer and defined how many codes you want create
    y - is integer and defined how length one of codes you want create
    z - is path and name where you want save generated code
#####Example:

    php bin/console app:discount-code --numberOfCodes 100000 --lengthOfCode 10 --file /var/www/html/test.txt