# Portfolio
My personal website and blog, simplified.

I've moved away from a JavaScript web framework and returned to a simpler,
PHP-powered site that my web host supports.

My reasoning behind this is as follows:
> The code base was getting too complex and I believe heavy JavaScript
> frameworks for simple sites such as my portfolio aren't really the right way
> to be showcasing things. Additionally, dealing with vulnerability alerts daily
> for packages I pull in just to spin up Angular (the framework I used) and that
> I have no control over made me realise that this should be simpler.

This project uses a SQLite database, [Smarty](https://www.smarty.net/) for the
templating and [Pest](https://pestphp.com/) for the unit tests. I'm
intentionally keeping the dependencies minimal.

## Get up and running
Composer is used to set up dependencies. Use `composer install`.

## Run unit tests
`./vendor/bin/pest`

All tests should be under the `tests` subfolder and have the extension `.test.php`