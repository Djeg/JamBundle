<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Path;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This class provide a simple way to resolve some path relative
 * to the bundles or not.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class PathResolver 
{
	/**
	 * @var Container $container
	 * @access private
	 */
	private $container;

	/**
	 * @var string $webDirectory
	 * @access private
	 */
	private $webDirectory;

	/**
	 * Return the bundle asset name. The bundleName
	 * parameter must be like that :
	 * 	- MyCoolBundle
	 * 	- AcmeDemoBundle 
	 * 	- ...
	 * 
	 * @param string|Bundle $object
	 * @return string, the corect asset bundle name
	 */
	public function getAssetBundleName($bundle)
	{
		if( is_string($bundle) ) {
			return strtolower(str_replace('Bundle', '', $bundle));
		}

		if( $bundle instanceof Bundle ){
			return strtolower(str_replace('Bundle', '', $bundle->getName()));
		}


	}

	/**
	 * Return all the bundles allias for the packager paths
	 * 
	 * @return array
	 */
	public function getBundlesPackagerPaths()
	{
		$paths = array();
		$bundles = $this->container->get('kernel')->getBundles();

		// parse the bundles assets
		foreach( $bundles as $bundle ){
			if( is_dir($this->getBundlePath($bundle->getName()).'/Resources/public/scripts') ) {
				$paths[$bundle->getName()] = 'bundles/'.$this->getAssetBundleName($bundle->getName()).'/scripts';
			}
		}

		return $paths;
	}

	/**
	 * Return the asset bundle path
	 * 
	 * @param string | Bundle $bundle
	 * @return string
	 */
	public function getAssetBundlePath($bundle)
	{
		return $this->webDirectory.'/'.$this->getAssetBundleName($bundle);
	}

	/**
	 * Get the absolute path of a bundle
	 * 
	 * @param string $bundle
	 * @return string
	 */
	public function getBundlePath($bundle)
	{
		return $this->container
			->get('kernel')
			->locateResource('@'.$bundle);
	}

	/**
	 * Return the web directory path
	 * 
	 * @return string
	 */
	public function getWebPath()
	{
		return $this->webDirectory;
	}

	/**
	 * Construct the path resolver
	 * 
	 * @param Container $container
	 */
	public function __construct($container)
	{
		$this->container = $container;
		$this->webDirectory = $container->get('kernel')
			->getRootDir().'/../web';
	}
}