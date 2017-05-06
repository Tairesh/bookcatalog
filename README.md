Bookcatalog
============================

REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.0.0.


INSTALLATION
------------

~~~
git clone git@github.com:Tairesh/bookcatalog.git
cd bookcatalog
composer install
./yii migrate
~~~

Now you should be able to access the application through the following URL, assuming `bookcatalog` is the directory
directly under the Web root.

~~~
http://localhost/bookcatalog/web/
~~~

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```
