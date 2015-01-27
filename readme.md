## E-Commerce Application Built with [Laravel PHP Framework](http://laravel.com)

This is a basic e-commerce application built with the Laravel PHP framework.  It is built for a specific need which I have, specially on an online store to order CDs, DVDs, and MP3s and to allow secure download of purchased MP3s.  It is probably extendable to other e-commerce situations; however, it does NOT have built-in support for images.

Some of the core principles of this application include:
- "Don't re-invent the wheel."  As much as possible, we use existing Laravel packages to accomplish various functions.  See below for list of packages used.
- Use Laravel RESTful resources where possible.  As much as possible, we use the [Laravel standard RESTful resources](http://laravel.com/docs/4.1/controllers#restful-controllers), including resource and model binding.  (See `apps\routes.php` for details.)
- "Do the simplest thing that could possibly work."  This application is not intended to be the most elegant or full-featured application.  I built it while learning the Laravel framework, so it has some of the warts due to being a newbie.  :)


### Some Tips For (and From!) Learning Laravel
- In most cases, your controller methods should `return` routes (e.g., `Redirect::route(...)`), rather than simply executing the route method itself.  While I don't completely understand all of the details, essentially, this paradigm is part of the fundamental routing functionality of Laravel.
- Pass **instances** of your model objects to controller methods, when possible, instead of IDs.  This simplifies processing of those instances by the methods and avoids having to make another call to the database.
- Use database caching judiciously by including the `remember(5)` method in your database queries.  Since caching in Laravel is so simple and transparent, there really is no reason not to use it (even if you don't think you need it!).

### Laravel Packages Used
- [Laravel Framework](http://laravel.com) - To allow support on shared hosts using PHP 5.3.x, version 4.1 of the Laravel framework is used.  (Framework will be upgraded to version 4.2 later, in a separate branch.)
- [Laravel 4 Generators](https://github.com/JeffreyWay/Laravel-4-Generators) - Jeffrey Way's amazing class generator package.
- [Ardent](https://github.com/laravelbook/ardent) - Adds self-validation functionality to Larvel Eloquent ORM model classes.
- [AWS SDK for Laravel](https://github.com/aws/aws-sdk-php-laravel) - Service provider extension to AWS SDK for PHP to provide download support for MP3s hosted on AWS.
- [Paypal IPN](https://github.com/logicalgrape/paypal-ipn-laravel) - Handles responses for [Paypal IPN](https://developer.paypal.com/docs/classic/ipn/gs_IPN/).
- [Kint](http://raveren.github.io/kint/) - This is the modern alternative to PHP's `var_dump()` method.  It displays all of the possible details that you could want for your variables in a compact, hierarchical fashion.  And it's so easy to use that it seems like magic: just put `Kint::dump($var_name)` in your Blade files.  That's it (literally!).
- [Moltin Laravel Shopping Cart Package](https://github.com/moltin/laravel-cart) - A wrapper for the minimalist Moltin Cart.  Has all of the basic shopping cart features that I needed.

### Other Resources
- [Twitter Bootstrap](http://getbootstrap.com/) - Integrated directly, rather than through a Laravel package.  (Sorry, I started using it before I knew about the various packages.)
- [laravel-precise32-php5.4](https://github.com/TimothyDJones/laravel-precise32-php5.4) - This is my personal custom Vagrant box for Laravel development.  Essentially, it's an Apache/PHP 5.4/MySQL alternative to Laravel's [Homestead](http://laravel.com/docs/4.2/homestead) tool, since I prefer an environment closer to the shared hosting platforms that I typically use and since Homestead does NOT support version 4.1 of Laravel framework.


### Contributing To The Application

Contributions to the application are welcome!  Please branch from **master** branch and upload your changes and submit a pull request.  Thanks!

### License

This open-sourced software application is distributed under the same license as the Laravel framework itself: [MIT license](http://opensource.org/licenses/MIT).  Derivative works must be distributed under the same license.
