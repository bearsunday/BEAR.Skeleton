# BEAR.Skeleton

This is a skeleton app which can be used a base for your own BEAR.Sunday applications. You can build the app by entering the following command.

##  Create project

    $ composer create-project bear/skeleton {project-path} dev-php7
    Created project in my-project
    > BEAR\Skeleton\Installer::preInstall

    What is the vendor name ?

    (MyVendor):Koriym

    What is the project name ?

    (MyProject):AwesomeProject


## Run server

    composer serve

## Console

    composer web get /
    composer api get /

# Structure

This is an example meaning that you can change any part of it to how you like to for your application. But this bear bones example is a good place to start.

## Where to start

The app is then booted procedurally, you can manipulate this bootstrap process in anyway you please by either adding or editing scripts in the `bootstrap/bootstrap.php` directory and any of the entry point script files you may be using.

## Modules

Although the procedural bootstrap process offers flexibility in setting up your application BEAR.Sunday's real power starts to kick in through wiring dependencies together. This all takes place in the `Modules` directory. A number of defaults for the `App` and several runtime *modes* are available to you here. These can also be edited or added to at will.

## Page and App Resources

`page` and `app` resources are added in the resources directory along with any template views that you may choose to add.

More ducumentation is available at http://bearsunday.github.io/ .

## Environment Variables

You can place environment-specific values in the .env file at the project root.
See more at [josegonzalez/dotenv](https://github.com/josegonzalez/php-dotenv#static-environment-definition).

More documentation available at http://bearsunday.github.io/

# Requirements

 * PHP 7.0+
