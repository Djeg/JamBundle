<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Packager;

use Djeg\JamBundle\Path\PathResolver;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use webignition\JsonPrettyPrinter\JsonPrettyPrinter;

/**
 * This class generate a corect package.json with the given information.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class Packager 
{
	/**
	 * @var array $name
	 * @access private
	 */
	private $content = array();

	/**
	 * @var JsonPrettyPrinter $formater
	 * @access private
	 */
	private $formater;

	/**
	 * Merge package with the given bundles
	 * 
	 * @param Package      $package
	 * @param PathResolver $path
	 * @param array        $bundles
	 * @static
	 */
	static public function mergePackage(Packager $package, PathResolver $path, array $bundles)
	{
		foreach( $bundles as $bundle ){
			if( is_object($bundle) and $bundle instanceof Bundle ){
				$bundle = $bundle->getName();
			}
			if( file_exists($path->getBundlePath($bundle).'/Resources/public/scripts/package.json') ) {
				$package->merge($path->getBundlePath($bundle).'/Resources/public/scripts');
			}

		}
	}

	/**
	 * Set the project name
	 * 
	 * @param string $name
	 * @return Packager
	 */
	public function setName($name)
	{
		$this->content['name'] = $name;

		return $this;
	}

	/**
	 * Get the project name
	 * 
	 * @return string or null
	 */
	public function getName()
	{
		return ( isset($this->content['name']) ) ? $this->content['name'] : null;
	}

	/**
	 * Set the version
	 * 
	 * @param string $version
	 * @return Packager
	 */
	public function setVersion($version)
	{
		$this->content['version'] = $version;

		return $this;
	}

	/**
	 * get the version
	 * 
	 * @return string
	 */
	public function getVersion()
	{
		return ( isset($this->content['version']) ) ? $this->content['version'] : null;
	}

	/**
	 * Set the description
	 * 
	 * @param string $description
	 * @return Packager
	 */
	public function setDescription($description)
	{
		$this->content['description'] = $description;

		return $this;
	}

	/**
	 * Get the description
	 * 
	 * @return string
	 */
	public function getDescription()
	{
		return ( isset($this->content['description']) ) ? $this->content['description'] : null;
	}

	/**
	 * Set the jam dependencies
	 * 
	 * @param string $vendor
	 * @param string $version
	 */
	public function addDependency($vendor, $version)
	{
		

		$this->content['jam']['dependencies'][$vendor] = $version;
 	}

 	/**
 	 * Get a dependency version
 	 * 
 	 * @param string $vendor
 	 * @return string or null
 	 */
 	public function getDependencyVersion($vendor)
 	{
 		

 		return ( isset($this->content['jam']['dependencies'][$vendor]) ) ?
 			$this->content['jam']['dependencies'][$vendor] :
 			null;
 	}

 	/**
 	 * Return all the setting dependencies
 	 * 
 	 * @return array
 	 */
 	public function getDependencies()
 	{
 		

 		return $this->content['jam']['dependencies'];
 	}

 	/**
 	 * Set the base url
 	 * 
 	 * @param string $baseUrl
 	 * @return Packager
 	 */
 	public function setBaseUrl($baseUrl)
 	{
 		

 		$this->content['jam']['config']['baseUrl'] = $baseUrl;

 		return $this;
 	}

 	/**
 	 * Get the baseUrl
 	 * 
 	 * @return string or null
 	 */
 	public function getBaseUrl()
 	{
 		return ( isset($this->content['jam']['config']['baseUrl']) ) ?
 			$this->content['jam']['config']['baseUrl'] :
 			null;
 	}

 	/**
 	 * Set a path
 	 * 
 	 * @param string $key
 	 * @param string $path
 	 * @return Packager
 	 */
 	public function addPath($key, $path)
 	{
 		

 		$this->content['jam']['config']['paths'][$key] = $path;

 		return $this;
 	}

 	/**
 	 * Get a path
 	 * 
 	 * @param string $key
 	 * @return string or null
 	 */
 	public function getPath($key)
 	{
 		

 		return ( isset($this->content['jam']['config']['paths'][$key]) ) ?
 			$this->content['jam']['config']['paths'][$key] :
 			null;
 	}

 	/**
 	 * Return all the registered paths
 	 * 
 	 * @return array
 	 */
 	public function getPaths()
 	{
 		

 		return $this->content['jam']['config']['paths'];
 	}

 	/**
 	 * Set all the paths
 	 * 
 	 * @param array $paths
 	 * @return Packager
 	 */
 	public function setPaths($paths)
 	{
 		

 		$this->content['jam']['config']['paths'] = $paths;

 		return $this;
 	}

 	/**
 	 * Save the current Packager object into the given directory
 	 * 
 	 * @throws RuntimeException
 	 * 
 	 * @param string $directory
 	 * @return boolean
 	 */
 	public function save($directory)
 	{
 		if( ! is_dir($directory) ) {
 			throw new \RuntimeException('No directory seems to exists at '.$directory);
 		}

 		return file_put_contents(
 			$directory.'/package.json', 
 			$this->formater->format(json_encode($this->content))
 		);
 	}

 	/**
 	 * Read and initialise the Packager object from the given directory. A package.json
 	 * file must exists !
 	 * 
 	 * @throws RuntimeException
 	 * 
 	 * @param string $directory
 	 * @return array
 	 */
 	public function read($directory)
 	{
 		if( ! is_dir($directory) ) {
 			throw new \RuntimeException('No directory seems to exists at '.$directory);
 		}
 		if( ! file_exists($directory.'/package.json') ) {
 			throw new \RuntimeException('No package.json file seems to exists in '.$directory);
 		}

 		return json_decode(file_get_contents($directory.'/package.json'), true);
 	}

 	/**
 	 * Read and assign the content for the given package.json in the given directory
 	 * 
 	 * @param string $directory
 	 * @return Packager
 	 */
 	public function open($directory)
 	{
 		$this->content = $this->read($directory);

 		return $this;
 	}

 	/**
 	 * Merge the actual Packager with an other package.json file located
 	 * into the given directory
 	 * 
 	 * @throws RuntimeException
 	 * 
 	 * @param string $directory
 	 * @return Packager
 	 */
 	public function merge($directory)
 	{
 		if( ! is_dir($directory) ) {
 			throw new \RuntimeException('No directory seems to exists at '.$directory);
 		}
 		if( ! file_exists($directory.'/package.json') ) {
 			throw new \RuntimeException('No package.json file seems to exists in '.$directory);
 		}

 		$package = $this->read($directory);

 		$this->content = $this->mergeArrayRecursively($this->content, $package);

 		return $this;
 	}

 	/**
 	 * Merge recursively two array between us
 	 * 
 	 * @param array $master
 	 * @param array $slave
 	 * @return array, the corect merging array
 	 */
 	protected function mergeArrayRecursively(array $master, array $slave)
 	{
 		$f = $master + $slave;

		foreach( $master as $k => $v ) {
			if( is_array($v) and isset($slave[$k]) ) {
				$f[$k] = $this->mergeArrayRecursively($master[$k], $slave[$k]);
			}
		}

		foreach( $slave as $k2 => $v2 ) {
			if( is_array($v2) and isset($master[$k2]) ) {
				$f[$k2] = $this->mergeArrayRecursively($master[$k2], $slave[$k2]);
			}
		}

		return $f;
 	}

 	/**
 	 * Generate a build.js file and return it's content. This build.js file must
 	 * be located in the web directory. Once the javascripts source has been 
 	 * compressed, this build.js file will be deleted.
 	 * 
 	 * @param PathResolver $path
 	 * @param string $out, the compiled output name
 	 * @return boolean
 	 */
 	public function generateBuild(PathResolver $path, $out)
 	{
 		$build = array();

 		$build['baseUrl'] = '.';

 		$build['paths'] = $path->getBundlesPackagerPaths();

 		$build['name'] = 'jam/bootstrap.js';

 		$build['out'] = $out;

 		$build['mainConfigFile'] = "jam/require.config.js";

 		$build['include'] = "jam/require";

 		return file_put_contents(
 			$path->getWebPath().'/build.js', 
 			'('.$this->formater->format(json_encode($build)).')'
 		);
 	}

 	/**
 	 * Destroy the build.js file from the given directory.
 	 * 
 	 * @param PathResolver $path
 	 * @return Packager
 	 */
 	public function destroyBuild(PathResolver $path)
 	{
 		if( file_exists($path->getWebPath().'/build.js') ){
 			unlink($path->getWebPath().'/build.js');
 		}

 		return $this;
 	}

 	/**
 	 * consructor (allias for open)
 	 * 
 	 * @param string $directory = null
 	 */
 	public function __construct($directory = null)
 	{
 		if( $directory ) {
 			$this->open($directory);
 		} else {
 			$this->open(dirname(__DIR__).'/Resources/skeleton');
 		}

 		$this->formater = new JsonPrettyPrinter();
 	}
}