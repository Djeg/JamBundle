Installation
============

For install jam bundle you need nodejs and jam installed on your system. For nodejs 
please refer to the [official website](http://nodejs.org/)

## Install jamjs

Once you have installed nodejs, use npm to install jamjs :

```
npm install -g jamjs
```

You can test the installation by launch the `jam` binary.

## Install JamBundle

Into your `composer.json` file add this dependency line :

```
"davidjegat/jam-bundle": "*"
```

Launch a `php composer.phar install` or `php composer.phar update` command and finally register the 
bundle into your `AppKernel.php' file :

```php
	new Djeg\JamBundle\DjegJamBundle(),
```

## Configure the JamBundle (optional)

If you have installed jamjs with the npm command writing before you can skip this step.

You can precised the `jam` binary path into your `app/config/config.yml` file like this :

```yaml
djeg_jam:
	jam: %kernel.root_dir%/path/to/jam
```

## Testing the JamBundle

Now run this command.

```
php app/console djeg_jam:jam help
```

It will display the list of jam command. You're ready for the introduction of 
[jam and require.js]((https://github.com/davidjegat/JamBundle/Resources/doc/introduction.md) or
if you are already familiar with them, the [user guide](https://github.com/davidjegat/JamBundle/Resources/doc/user_guide.md).