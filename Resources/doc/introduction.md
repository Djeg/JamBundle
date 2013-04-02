Introduction to jam and require.js
==================================

I will do a short introduction about jam and require.js. If you wan't to know more
about those technologies, please read the officials documentations :
	
-	[require.js](http://requirejs.org/)
-	[jam](http://jamjs.org/)

## Require.js

Require js is a client side library that allow you to manage dependencies injections with the 
AMD code structure. You can defined modules and inject some other modules in or require some
dependencies and execute code with them. All scripts is load asynchronously and can be
compiled for a production mode.

Define a module is very easy :

```javascript
define(
	'MyModule', // you're module name (optional)
	[ 'jquery', 'underscore' ], // The dependencies (optional too)
	function( $, _ ) // The module, it takes jquery and underscore in parameters
	{
 		
		return { // return a JSON as the public stuff of your module

			someMethod: function()
			{
				return 'MyModule';
			}

		};

	}
);
```

Require a module is also so easy !

```javascript
require(
	[ 'jquery', 'underscore', 'MyModule' ], // the dependencies
	function( $, _, MyModule ) // it takes jquery, underscore and MyModule in parameters
	{
		// You're code here !
	}
);
```
If you wan't to know more about require.js please visit the 
[official website](http://requirejs.org/).

## jam

jam is a package manager for javascript client browser libraries. As you know maybe,
conventionally Symfony2 recommends to not include javascripts vendors 
into any exportable bundles. With jam, the javascripts vendor can be directly install
with a simple file called : `package.json`. Moreover jam manages the different 
vendors versions. However you must be familiar with require.js before start to 
rocks with jam, because it uses it intensively ! Don't worry, a complete 
[user's guide](https://github.com/davidjegat/JamBundle/Resources/doc/user_guide.md) 
is available.

This is some jam command that can be usefull :

```
# search a vendor
jam search jquery
```

```
# install a vendor
jam install jquery
```

You can learn more about jam on the [official website](http://jamjs.org/docs).

Now you are a little more familiar with Require.js and Jam, you can start to read
the nest section : [user guide](https://github.com/davidjegat/JamBundle/Resources/doc/user_guide.md).