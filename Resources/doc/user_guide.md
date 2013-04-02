User's guide
============

So, you have installed the JamBundle and read the 
[introduction](https://github.com/davidjegat/JamBundle/Resources/doc/introduction.md) now
your are ready for rocking !

## Manipulate jam with the console.

Using jam with your console, allows you to delegate
the path resolution of your jam application without taking care of that. You can
display the `help` like that :

```
php app/console djeg_jam:jam help
```

Now, started by install some vendors. For exemple jquery :

```
php app/console djeg_jam:jam install jquery
```

Once the command is executed, go into your `web/` directory and you should
see this kind of structures.

```
jam/
---- jquery/
---- ------- dist/
---- ------- ----- other stuff
---- ------- src/
---- ------- ----- other stuff
---- ------- test/
---- ------- ----- other stuff
---- ------- package.json
---- require.config.js
---- require.js
```

Let's clarify this a little. the `web/jam` directory is the started point of your application. The
`require.js` contains the require.js library and some packages definitions for your vendors.

## Generate your module.

Okay, jquery seems to be installed on your project. Now, you have to create your first module for 
interact with jquery.

Launch this command :

```
php app/console djeg_jam:generate:module MyCoolBundle:someModule
```

This command will generate two files into this directory of your bundle :

```
Resources/
----------public/
-----------------js/
---------------------main.js
---------------------someModule.js
```