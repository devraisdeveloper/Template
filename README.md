Test task SEVEN PRINCIPLES AG
============================

Test task SEVEN PRINCIPLES AG

Description

This app takes json string and based on that creates a table in your database.
Json text is stored in "Template Collection" table. Every json template has an "ID", "name" and "json" fields. Field "json" stores the contents of the string.

When you submit a json string, app will validates it. If it is ok stores it and creates a table. The name for the table is provided from json string.

App work in a specific way.
I manage to create an "adaptive model". 
This model will by itself create the validation rules and establish connection (This is usually hardcoded and was the main difficulty that consumed most of the development time) based on the provided json. 

So the user can just manipulate the json string and the app will do all the work.

I have decided to use post for jason submit instead of file submition. I used this approach because I think it is more user friendly.

I used Yii2 framework as the base for my project. 


USED TOOLS
----------

- Project was done on Ubuntu operation system

INSTALLATION
------------

Download url: 

~~~
https://github.com/devraisdeveloper/Template.git
~~~

You can clone or download a zip-folder.

Make sure you have "fxp/composer-asset-plugin:^1.2.0" installed globally via composer

php composer.phar global require "fxp/composer-asset-plugin:^1.2.0"

Additional info on this in http://www.yiiframework.com/download/


### Create a virtual host

```
<VirtualHost *:80>
        ServerAdmin admin@template.com
        ServerName template.com
        ServerAlias template.com
        DocumentRoot /var/www/blog.com/public_html/template.com/web
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```


### Download "Vendor" files to project

Use composer to install all needed files

~~~
composer update
~~~

### Add permissions
~~~
/runtime

/web/assets

~~~


CONFIGURATION
-------------

### Database

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.

Create your database and add it to the project. I called my database "TemplateDatabase"

/config/db.php

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=YourDatabaseName',
    'username' => 'YourUserName',
    'password' => 'YourPassword',
    'charset' => 'utf8',
];
```
### Create table - This table will store all your templates. Other tables based on templates will be created by this project

```sql
CREATE TABLE `TemplateDatabase`.`TemplateCollection` ( 
`id` INT NOT NULL AUTO_INCREMENT , 
`name` VARCHAR(45) NOT NULL , 
`json` TEXT NOT NULL , 
PRIMARY KEY (`id`)) 
ENGINE = InnoDB;
```


### Open main page

Main url for project should be :
~~~
http://template.com
~~~

In case you have problems with pretty urls you can disable them
This can be done /config/web.php

This is how you can disable:

```php
        'urlManager' => [
            'enablePrettyUrl' => false,
        /*    'showScriptName' => false,
            'rules' => [
            ],*/
        ],
```
Just comment 2 lines and set 'enablePrettyUrl' => false


HOW TO USE
-------------

You will open the app on the http://template.com

There will be a button that will direct you to start (http://template.com/template-collection).
You will see an empty table. Now add your json strings.

in the root of the project - there is folder "/web/templates"

I have saved a few examples. These are correctly build json files. You can just post them to insert field and submit.

The structure of json string is not difficult - consists of 3 parts [ "table_name", "table_fields", "table_display_format"]

First - name of your table. 
Second - You give properties to your table fields. In regards to 'rules' - this is range for integer and string
Third - How do you want to represent your info in the view.

For example, in template1.json We want to see full name but in table see them seperatly.

```php
 "Full name": [
            "First_name",
            "Last_name"
        ],
```
So in the view we will see them together.


When we whant to update a json. We just press the pencil icon next to your template and we add our changes.
After submitting changes will effect the table.

IMPORTANT

I forgot to add validation to "date" type. So when adding date you need to use this format (YYYY-MM-DD). Or you will have an error. 


Additional Info
-------------

For this project I created a number of files:

1) "helpers" folder and all the contents
2)In "models" folder - my files are "ModelAdapter.php", "ModelAdapterSearch.php", "TemplateCollection.php", "TemplateCollectionSearch.php"
3) In "controllers" folder - "TemplateCollectionController.php"
4) In "views" folder - "template-collection" all contents are mine
5) web/templates - a collection of example files for json templates that YOU CAN USE. And create your own test case.

Example of a json template:

{
    "table_name": "table_name",
    "table_fields": {
        "First_name": {
            "type": "varchar",
            "value": "45",
            "rules": {
                "min":"1",
                "max":"45"
            }
        },
        "Last_name": {
            "type": "varchar",
            "value": "45",
            "rules": {
                "min":"1",
                "max":"45"
            }
        },
        "Date_of_birth":{
            "type": "date"
        },
        "Internal_company_ID": {
            "type": "int",
            "value": "11",
            "rules":{
                "min":"10000",
                "max":"99999"
            }
        },
        "Department": {
            "type": "enum",
            "value": [
                "R&D",
                "Sales",
                "Marketing",
                "Management"    
            ]
        },
        "Use_company_car":{
            "type":"boolean",
            "value":"1"
        }
    },
    "table_display_format": {
        "Full name": [
            "First_name",
            "Last_name"
        ],
        "Date of birth": [
            "Date_of_birth"
        ],
        "Internal company ID": [
            "Internal_company_ID",
            "Department",
            "Use_company_car"
        ]
    }
}
