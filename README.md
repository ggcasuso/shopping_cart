shopping_cart
=============

A simple shopping cart running in Symfony with MongoDB.
The shopping cart shows a product list that you can choose, if you add a product to the order, you can have discounts:
 - Menu Discount: You receive a 20% off when you choose a menu (Main course, drink and dessert)
 - 3x2 Discount: You receive a 3x2 discount adding 3 items of the same product

The discount will appear automatically in your order.

In this first version, every time you enter in homepage, you create a new order in database to add products.

I use a free temporary template for this version downloaded from: http://tutorialzine.com/2014/04/responsive-shopping-cart-layout-twitter-bootstrap-3/


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

**Remember to update your parameters.yml with the parameters.yml.dist**

Unit Testing
============

You can run some unit tests executing the next command in the project root:

```
phpunit src
```