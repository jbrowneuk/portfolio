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

This project deliberately tries to keep dependencies minimal:
- SQLite using the built-in PHP support
- [Smarty](https://www.smarty.net/) for templating
- [PHP-DI](https://php-di.org) for DI
- [Pest](https://pestphp.com/) for the unit tests
- [Mockery](https://github.com/mockery/mockery/) for mocks

## Get up and running
The code is written for an environment using PHP 8.4, and it needs support for
the SQLite PDO.

Composer is used to set up dependencies. Use `composer install`.

A database setup SQL file is provided in `setup/database.sql`. This will create
the required tables to get going.

### Style bundle as a submodule
The [style bundle](https://github.com/jbrowneuk/style-bundle.git) is added as a
submodule in `src/theme`. If you're cloning this repo for the first time, be
sure to read the git documentation on how to use submodules.

TL;DR:
- `git submodule init` on initial clone to get git to fetch the submodules.
- `git submodule update` to fetch latest commits from the submodule repo.

Updating is as simple as navigating to the submodule folder, `cd src/theme`, and
then using `git checkout tag` to change the tag the submodule is pointing to.
You'll also want to bump the query params in `src/templates/layout/html-head.tpl`
to match the new tag.

## Run unit tests
`./vendor/bin/pest`

All tests should be under the `tests` subfolder and have the extension `.test.php`
