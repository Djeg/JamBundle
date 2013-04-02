Sample application : Hello World
================================

This is a very famous and basic programm that say to you "Hello World !".

## Create the bundle

The first step is to create the bundle. In my case my bundle is called
`JamHelloBundle` and contains :

```
Controller/
-----------HelloController.php
DependencyInjection/
--------------------Configuration.php
--------------------JamHelloExtension.php
Resources/
----------public/
----------views/
----------------index.html.twig
JamHelloBundle.php
```

My `HelloController.php` is just displaying the `index.html.twig` template witheout any arguments.
This is my `index.html.twig` file :

```html+jinja
<!DOCTYPE html>
<html>
	
	<head>
		<title>Hello World with JamBundle</title>
		<meta charset="utf-8" />
	</head>

	<body>

		{# display all the scripts tag, in fact only two ;) ! #}
		{% jam %}
	</body>

</html>
```

## Configure the JamBundle

Go into your `app/config/config.yml` file and add this kind of configuration :

```yaml
djeg_jam:

    # The command or path to the jam binary. for me the default (jam)
    jam:                  "jam"

    # The path to the binary of your nodejs (required for compilation)
    node:                 "/usr/bin/nodejs"

    # The path to the binary r.js scripts (required for compilation)
    rjs:                  "/usr/local/lib/node_modules/jamjs/node_modules/.bin/r.js" 

    # Precised the web uri from your server. For exemple if your web site
    # is located like that : http://locahost/MyProject/web. Write "/MyProject/web".
    base_url:             /web

    # The compiled output file name.
    compiled_output:      HelloWorld.min.js
```

## Initialize a package

Open a console at the root level of your symfony project and generate a `package.json` file :

```
php app/console djeg_jam:init
```

Answer to the questions. For exemple, this is my `web/package.json` :

```javascript
{
    "name": "HelloWorld",
    "version": "0.0.1",
    "description": "A simple hello the world !",
    "jam": {
        "dependencies": {
            "domReady": "2.*",
            "jquery": "*"
        },
        "config": {
            "paths": [

            ],
            "baseUrl": "/web"
        }
    }
}
```

As you can see, i require the latest jquery. By default, JamBundle use the `domReady` module to detect
the `document.ready` state and launched your bootstraps bundle scripts.

## Install your dependencies

Just run `php app/console djeg_jam:install` for install all the dependencies.

## Generate the `HelloWorld` module

Now generate the module like that : `php app/console djeg_jam:generate:module JamHelloBundle:HelloWorld`.
Say `yes` to the bootstrap generation. Now go to yor `Resources/public/scripts` directory and you should
see this scripts :

```
Resources/
----------public/
-----------------scripts/
-------------------------HelloWorld.js
-------------------------main.js
```

## Edit your module `HelloWorld`

Open your `HelloWorld.js` module and add some code like that :

```javascript
/**
 * HelloWorld
 * 
 * The first param is an array with your dependencies, here : jquery !
 * Dependencis are send in arguments to the second function parameter
 */
define([ 'jquery' ], function($)
{
	// create the module namespace
	var module = {};

	// create the h1 tag
	module.helloTag = $('<h1>Hello World !</h1>');

	// return our module
	return module;
});
```
So, we have defined a module that return a json with an `helloTag` attribute.

## Edit your bootstrap script : `main.js`

This is my `main.js` bundle bootstrap :

```javascript
/**
 * main.js
 *
 * Bundle bootstrap. Implement the initialize method
 * that will be called on DOMReady.
 *
 * Take jquery in first dependency and in second the
 * HelloWorld module from our JamHelloBundle !
 */
define([ 'jquery', 'JamHelloBundle/HelloWorld' ], function($, HelloWorld)
{
	var main = {};

	main.initialize = function(){
		// This function will be launched once the document
		// is ready !
		// add the h1tag to the body tag :
		HelloWorld.helloTag.prependTo('body');

	}

	return main;
});
```

## Install your assets

Install all your public files : `php app/console assets:install --symlink`

## See the result !

Now open the controller with your favorite navigator and you should see the `Hello World !`
h1 tag display after few times (JamBundle load javascripts asynchronously).

## Production power ! compilation

Once you have finish. Launched an update command : `php app/console djeg_jam:update`. Be sure
that you have corectly configured JamBundle before and launch : `php app/console djeg_jam:compile`.
Pass you application to production and see the result. Just one script is included ! It contain's
all your dependencies and bundles modules code !