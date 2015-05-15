# BEAR.Skeleton

BEAR.Skeleton relies on BEAR.Package which can be downloaded [here](http://github.com/koriym/BEAR.Package).

This is a skeleton app which can be used a base for your own BEAR.Sunday applications. You can build the app by entering the following command.

    $ composer create-project -na bear/skeleton:~1.0@dev ./{Vendor.Package} 
    $ cd {Vendor.Package}
    $ composer install

    // test
    $ phpunit
    // console
    $ php bootstrap/web.php get /
    // built-in web
    $ php -S 0.0.0.0:8081 -t var/www/
    
## Structure

This is an example meaning that you can change any part of it to how you like to for your application. But this bear bones example is a good place to start.

## Where to start

The app is then booted procedurally, you can manipulate this bootstrap process in anyway you please by either adding or editing scripts in the `bootstrap/bootstrap.php` directory and any of the entry point script files you may be using.

## Modules

Although the procedural bootstrap process offers flexibility in setting up your application BEAR.Sunday's real power starts to kick in through wiring dependencies together. This all takes place in the `Modules` directory. A number of defaults for the `App` and several runtime *modes* are available to you here. These can also be edited or added to at will.

## Page and App Resources

`page` and `app` resources are added in the resources directory along with any template views that you may choose to add.

## Requirements

 * PHP 5.5+
