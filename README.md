shopping_cart
=============

A simple shopping cart running in Symfony with MongoDB.
The shopping cart shows a product list that you can choose, when you add a product to the order, you could have discounts:
 - Menu Discount: You received a 20% off when you choose a menu (Main course, drink and dessert)
 - 3x2 Discount: You received a 3x2 discount adding 3 items of the same product

The discount appears automatically in your order.

In this first version, every time you enter in homepage, you create a new order in database to add products.

Installation Guide
==================

Once you clone the project you have to execute the next commands to have the project ready to run:

Installing composer dependencies:
```
composer install
```

Installing bower dependencies:
```
bower install
```

Generating Symfony assetics:
```
php bin/console assetic:dump --env=prod
```

The project has some fixtures to test the application, to load it, enter the next command:
```
php bin/console doctrine:mongodb:fixtures:load
```

Now run a server in project path, Symfony could do it, just execute:

```
php bin/console server:run
```

The server running in http://127.0.0.1:8000, so you're ready to test the project.

Unit Testing
============

You could run some unit tests executing the next command in the project root:

```
phpunit src
```