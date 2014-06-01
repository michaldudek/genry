<a name="top"></a>Genry
=====

Genry is a static page generator that uses [Twig](http://twig.sensiolabs.org/) templating system.

Use cases include:

- HTML pages loaded from file system
- hosting environments where no scripting language is available (e.g. no PHP, Ruby, Python, Perl, node.js, etc.)
- GitHub project and user pages

It's perfect for people who don't have experience with building dynamic websites in server side languages, but want to ease their website building process by eliminating copy/paste code (like navigation HTML) or want to make use of powerful templating language.

Visit [http://www.michaldudek.pl/genry/](http://www.michaldudek.pl/genry/).

## <a name="tldr"></a>TL;DR

- Get [Composer](https://getcomposer.org).
- Run `$ composer create-project michaldudek/genry-project suchwebsite -s dev` to start a Genry project in `suchwebsite` dir.
- Use  [Twig templates](http://twig.sensiolabs.org/) to create your website.
- Put all your templates inside `_templates` directory.
- Run `$ php genry generate` to generate static HTML files.
- In templates:
    - When including/extending templates refer to them relative to the `_templates` dir, e.g. `{{ include "subdir/content.html.twig" }}` for including `_templates/subdir/content.html.twig`.
    - Use `{{ stylesheet('assets/css/global.css') }}` and `{{ javascript('assets/js/global.js') }}` to add CSS and JS files and `{{ stylesheets() }}` and `{{ javascripts() }}` to output them to make sure their URL is always correct and relative to the generated page
    - Import Markdown files using `{{ markdown('path/to/file/in/_templates/article.md') }}`
- Templates that filenames end with `.inc.html.twig` are not rendered directly into HTML files (they are only to be included by other templates)

## <a name="moreinfo"></a>More information

#### <a name="markdown"></a>Write content in Markdown

Genry allows you to import Markdown files directly into HTML files, so you can focus on writing your content, instead of markup.

#### <a name="templating"></a>Templating 

For comprehensive list of possibilites you should check [Twig for Template Designers](http://twig.sensiolabs.org/doc/templates.html), but to highlight a few of them:

- **template inheritance** - create one layout and use it on every page without repeating yourself
- **includes** - include other templates to easily reuse HTML elements you have already created elsewhere - update in one place and it will change everywhere
- **control structure** - use features like `if / elseif / else`, `for` loops or powerful `block` system to build your HTML dynamically
- **macros** - generate HTML dynamically using macros with arguments based on which you can alter the output
- **variables** - store some settings or strings you use often in variables to reuse them easily
- **filters** - modify your output by applying filters to them (e.g. format a number, capitalize first word letters in whole sentence, url encode a string)

#### <a name="extensibility"></a>Extensibility

Genry is built in with [Splot Framework](https://github.com/splot/Framework) (to be released, currently in active development) and can be easily extended through Splot's module system and dependency injection, as well as Twig's extensions feature.

## <a name="requirements"></a>Requirements

Genry requires **PHP 5.3+ command line** environment to run which means that it runs just fine on Mac OS X and Linux (*I have no idea about Windows, sorry...*).

It also requires PHP's package manager - **[Composer](https://getcomposer.org/)**. To install Composer follow [their instructions](https://getcomposer.org/doc/00-intro.md#installation-nix), but in short it's this:

    $ curl -sS https://getcomposer.org/installer | php
    $ mv composer.phar /usr/local/bin/composer

Composer will help your projects to use the latest version of Genry and will give you access to all of its modules and extensions.

## <a name="installation"></a>Installation

Genry lives directly inside your project, so you don't have to install it on your computer. Simply, when you are about to start a new website project open your Terminal and run this command (where `suchwebsite` is name/path to your project so you should probably change it to something else):

    $ composer create-project michaldudek/genry-project suchwebsite -s dev
    $ cd suchwebsite

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

#### <a name="updating"></a>Updating and re-installing

If you ever wanted to update Genry to newer version or install it for a Genry project that you deleted from your computer, but pulled it back from somewhere, just use these commands:

- `$ composer update` - to update Genry to its latest version
- `$ composer install` - to install Genry in a previously "Genry-enabled" project

## <a name="usage"></a>Usage

By default all templates are stored inside the `_templates` dir so that they don't pollute the root dir and are clearly separated from the generated HTML files. Any `*.html.twig` files inside that dir (and its subdirs) will be compiled and turned to static HTML files inside the root folder of the project. The directory structure from inside `_templates` will be kept, e.g.

- `./_templates/index.html.twig` will compile to `./index.html`
- `./_templates/subdir/index.html.twig` will compile to `./subdir/index.html`
- `./_templates/subdir/contents.html.twig` will compile to `./subdir/contents.html`

One exception to this rule are files that end with `*.inc.html.twig` - these will not be compiled. Their only purpose is to be included inside other templates.

When you include or extend other templates in Twig, always specify their locations relative to the `_templates` directory. 

So, if you have a directory structure like this:

    _templates/
        slides/
            project1.inc.html.twig
            project2.inc.html.twig
        layout/
            header.inc.html.twig
            footer.inc.html.twig
        index.html.twig
        layout.inc.html.twig
    assets/
        ...
    composer.lock
    composer.json
    genry

Your `index.html.twig` template should refer to all other templates like this:

    {% extends "layout.inc.html.twig" %}

    {% block body %}
        {% include "layout/header.html.twig" %}

        <section>
            {% include "project1.inc.html.twig" %}
        </section>
        <section>
            {% include "project2.inc.html.twig" %}
        </section>

        {% include "layout/footer.html.twig" %}
    {% endblock %}

**Always** refer to templates relative to the `_templates/` dir, even from templates next to each other in the same sub directory.

### <a name="compiling"></a>Compiling

When you have created your templates compile them by executing this command in the command line in your project folder:

    $ php genry generate

### <a name="assets"></a>Assets Management

Because the generated HTML files will be saved in different locations relative to the root dir while probably extending a single layout template and because your website might not always live in the root dir of your domain (think GitHub project pages which are located at `http://username.github.io/projectname/`) using relative or absolute paths to your JavaScript and CSS files might cause a lot of problems.

For example, if your `_templates/layout.inc.html.twig` file includes assets like this:

    <link rel="stylesheet" href="/assets/css/global.css">
    <script type="text/javascript" src="/assets/js/global.js"></script>

It will only work if your website resides in root folder of your domain. If you were to put it in a subdir, e.g. at `yourdomain.com/suchwebsite/` links to the assets would be broken.

However, if you change them to be relative:

    <link rel="stylesheet" href="assets/css/global.css">
    <script type="text/javascript" src="assets/js/global.js"></script>

This will only work for files that are located in the root dir of your website. If you made a template `_templates/subdir/article.html.twig`:

    {% extends "layout.inc.html.twig" %}

    {% block content %}
        Lorem ipsum dolor sit amet.
    {% endblock %}

It will compile to a file `subdir/article.html` and the relativeassets links will also be broken for this one page.

Genry takes care of these two problems if you let him.

Wherever you want to add a CSS or JS file use this notation:

    {{ stylesheet('/path/to/css/relative/to/project/dir.css') }}
    {{ javascript('/path/to/js/relative/to/project/dir.js') }}

And to output all CSS files (typically in `<head>`):

    {{ stylesheets() }}

And JS files (typically right before `</body>`):

    {{ javascripts() }}

The result will be that Genry will generate URL's to those assets always relative to the generated static HTML file. This way your generated files can safely be put at whatever location on your server as well as opened locally straight from the file system.

## <a name="features"></a>Features

Genry comes with few pre-packaged features that don't require any additional modules.

### <a name="markdown-feature"></a>Markdown

If you want to write your content in Markdown (like this documentation) you can easily import and compile Markdown files inside your templates by simply writing:

    {{ markdown('path/to/file/in/templates/article.md') }}

You can also use a Markdown filter:

    {{ "[such website](http://www.dogeweather.com), **much markdown**, such HTML."|markdown }}

### <a name="data"></a>Data Objects

TBD

## <a name="roadmap"></a>Roadmap

Genry is very much **work in progress** and the core of it has been created in one (albeit long) evening. There are many ideas and features that could be developed for it, but I want to keep it simple. Here is a list of features that are on the roadmap for the nearest future (ie. when need be):

- More documentation including some recipes, examples and "built with Genry" section as well as information on how to create your own modules.
- Watching the project for changes and auto regenerating the pages.
- Minification of CSS and JS files (reusing what is already in Splot Assets Module).

## <a name="contribute"></a>Contribute

Pull requests, reported issues and any help is welcome. Just keep in mind that this is a strongly personal project and I might have already started working on something that you wanted to do, so before doing any coding work please submit an issue with your idea for discussion.

If you want to develop something for Genry then I will be very happy to help, as there isn't much documentation on this topic yet. Just open an issue and I'll reply to it.