Genry
=====

Genry is a static page generator that uses [Twig](http://twig.sensiolabs.org/) templating system.

Use cases include:

- HTML pages loaded from file system
- hosting environments where no scripting language is available (e.g. no PHP, Ruby, Python, Perl, node.js, etc.)
- GitHub project and user pages

It's perfect for people who don't have experience with building dynamic websites in server side languages, but want to ease their website building process by eliminating copy/paste code (like navigation HTML) or want to make use of powerful templating language.

## TL;DR

- Get [Composer](https://getcomposer.org)
- Run `$ composer create-project michaldudek/genry-project suchwebsite -s dev` to start a Genry project in `suchwebsite` dir
- Inside that dir run `$ php genry generate` to generate HTML files from [Twig templates](http://twig.sensiolabs.org/) located in `_templates` dir
- In templates:
	- Use `{{ stylesheet('assets/css/global.css') }}` and `{{ javascript('assets/js/global.js') }}` to add CSS and JS files and `{{ stylesheets() }}` and `{{ javascripts() }}` to output them to make sure their URL is always correct and relative to the generated page
	- Import Markdown files using `{{ markdown('path/to/file/in/_templates/dir') }}`
- Templates that filenames end with `.inc.html.twig` are not rendered directly into HTML files (they are only to be included by other templates)

## More information

#### Write content in Markdown

Genry allows you to import Markdown files directly into HTML files, so you can focus on writing your content, instead of markup.

#### Templating 

For comprehensive list of possibilites you should check [Twig for Template Designers](http://twig.sensiolabs.org/doc/templates.html), but to highlight a few of them:

- **template inheritance** - create one layout and use it on every page without repeating yourself
- **includes** - include other templates to easily reuse HTML elements you have already created elsewhere - update in one place and it will change everywhere
- **control structure** - use features like `if / elseif / else`, `for` loops or powerful `block` system to build your HTML dynamically
- **macros** - generate HTML dynamically using macros with arguments based on which you can alter the output
- **variables** - store some settings or strings you use often in variables to reuse them easily
- **filters** - modify your output by applying filters to them (e.g. format a number, capitalize first word letters in whole sentence, url encode a string)

#### Extensibility

Genry is built in with [Splot Framework](https://github.com/splot/Framework) (to be released, currently in active development) and can be easily extended through Splot's module system and dependency injection, as well as Twig's extensions feature.

## Requirements

Genry requires PHP 5.3+ command line environment to run which means that it runs just fine on Mac OS X and Linux (*I have no idea about Windows*).

It also requires PHP's package manager - [Composer](https://getcomposer.org/). To install Composer follow [their instructions](https://getcomposer.org/doc/00-intro.md#installation-nix), but in short it's this:

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Composer will help your projects to use the latest version of Genry and will give you access to all of its modules and extensions.

## Installation

Genry lives directly inside your project, so you don't have to install it on your computer. Simply, when you are about to start a new website project open your Terminal and run this command (where `suchwebsite` is name/path to your project so you should probably change it to something else):

```
$ composer create-project michaldudek/genry-project suchwebsite -s dev
$ cd suchwebsite
```

This will create a basic boilerplate template for your project and some files and dirs that are required for Genry to run:

- `.genry` and `.cache` contain code required by Genry
- `_templates` is where your website templates will leave (so that they are separate from the main website)
- `assets` is a suggested place to put your CSS and JS files, images, videos and any other "assets", but you can put them wherever you want really
- `.gitignore` comes prepopulated with some files to ignore by Git, so they don't trash your repository
- `.bowerrc` is a suggested configuration for [Bower](http://bower.io) if you want to use it
- `bower.json` comes with few suggested packages that you might want to get from [Bower](http://bower.io) to get you started
- `composer.json` and `composer.lock` are files created by Composer that hold information which version of Genry and other PHP libraries you are using
- `genry` is finally the actual Genry.

This may seem like quite a lot of files to just start a website, but they are there for a reason and should never get in your way.

#### Updating and re-installing

If you ever wanted to update Genry to newer version or install it for a Genry project that you deleted from your computer, but pulled it back from somewhere, just use these commands:

- `$ composer update` - to update Genry to its latest version
- `$ composer install` - to install Genry in a previously "Genry-enabled" project

## Usage

inc.html.twig


### Use markdown

### Data objects

## Assets management

## Roadmap

- More documentation including some recipes, examples and "built with Genry" section as well as information on how to create your own modules.
- Watching the project for changes and auto regenerating the pages
- Minification of CSS and JS files

## Contribute