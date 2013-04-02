User's guide
============

So, you have installed the JamBundle and read the 
[introduction](https://github.com/davidjegat/JamBundle/blob/master/Resources/doc/introduction.md) now
your are ready for rocking !

## Initialize your package

The first step once the JamBundle is installed is to intialize a corect `package.json` file
that will contains all your dependencies and project informations. Let's get it started !

```
php app/console djeg_jam:init
```

The command will ask you some questions before initialize the `package.json` file. Once you have
answered to all the questions you can check the generated package into your `web/` directory.

### A package per bundles !

JamBundle integrate a powerfull functionality that allow you to configure a `package.json` per
bundles and corectly install it into the main `package.json`. For generate a bundle package execute
this command :

```
php app/console djeg_jam:init AcmeDemoBundle
```

This command will generate a `package.json` file into the `Resources/public/scripts` directory of
your bundle.

## Install the dependencies

Once your `package.json` file is created you can set-up some dependencies and simply install it like
that :

```
php app/console djeg_jam:install
```

Don't take care about your bundle packages. This command will concatenate all the 
encountered `package.json` files from your bundles.

You can also update your dependencies with this simple command.

```
php app/console djeg_jam:update
```

## Generate your first module

JamBundle comes with a cool module generation command. Just launch this command in the bundle of
your choice :

```
php app/console djeg_jam:generate:module AcmeDemoBundle:MyFirstModule
```

It will ask you if you wan't to generate a bootstrap script for your bundle called `main.js`. Say 
`yes` (bundle bootstrap script is explained later). Now go into the `Resources/public/scripts'
directory of your bundle and you should see two files :

* `main.js`
* `MyFirstModule.js
* optionaly a `package.json` file.


### Bootstrap script : `main.js`

The bootstrap script (conventionaly called `main.js`) is the started point off your AMD application. It
looks like that :

```javascript
/**
 * main.js
 *
 * Bundle bootstrap. Implement the initialize method
 * that will be called on DOMReady.
 */
define([], function()
{
	var main = {};

	main.initialize = function(){
		// Dom Ready code here
	}

	return main;
});
```

This simple script create a json object called `main`, add a function called 
`initialize` in it and finaly return the `main` json object. The `initialize` 
function will be called once your `document` will be ready ! You can pass
some dependencies to the first Array of the `define` function. For exemple,
we will pass the `jquery` and `MyFirstModule` generated before :

```javascript
define([ 'jquery', 'AcmeDemoBundle/MyFirstModule' ], function($, module)
{
	// main.js code here ...
});
```
Notice than the bundle path is resolved by using the litteral bundle name :
`VendorNameBundle`. This path will be resolved like that : `bundles/vendorname/scripts`.

## Include the code

Okay, now you have installed your project dependencies, generate a module and a bootstrap
script. You just have to install you asset :

```
php app/console assets:install --symlink
```

Finaly, into your twig template use this simple following tag to add the corect
scripts :

```html+jinja
{% jam %}
```

It's done ! Your application is ready to run !

## The all in one : compilation

You can compiled all your scripts and dependencies into one compressed file. The first
thing to do is to configure the JamBundle in the `app/config/config.yml` file :

```yaml
djeg_jam:
	# the nodejs path
	node: "/usr/local/bin/node" 
	# the r.js script path. It will be used to compile your scripts.
	# By default on linux system it's :
	rjs: "/usr/local/lib/node_modules/jamjs/node_modules/.bin/r.js" 
	# the compiled script name
	compiled_output: "myApplication.min.js"
```

You just have to launch this command to compile all your script :

```
php app/console djeg_jam:compile
```

## You wan't to see an sample application ?

You can see a sample application into the [sample application](https://github.com/davidjegat/JamBundle/blob/master/Resources/doc/sample.md) exemple section.