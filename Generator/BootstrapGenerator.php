<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;

/**
 * This class generate a corect bootstrap.js file that enable
 * the module initialization.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class BootstrapGenerator extends Generator
{
	/**
	 * @var Container $container
	 * @access private
	 */
	private $container;

	/**
	 * Redefined the render method
	 * 
	 * @return string, the render Bootstrap
	 */
	public function display()
	{
		$bundles = $this->container
			->get('kernel')
			->getBundles();
		$pathResolver = $this->container
			->get('djeg_jam.path_resolver');

		// create the corect bootstraps
		$bootstraps = array();
		foreach( $bundles as $bundle ){
			// Try to get the main.js file :
			if( file_exists($pathResolver->getBundlePath($bundle->getName()).'/Resources/public/scripts/main.js') ) {
				$bootstraps[] = '"'.$bundle->getName().'/main"';
			}
		}

		// render the bootstrap file :
		$skeletonDir = __DIR__.'/../Resources/skeleton';
		return parent::render($skeletonDir, 'bootstrap.js.twig', array(
			'bootstraps' => implode(','."\n", $bootstraps)
		));
	}

	/**
	 * Save the bootstrap into the web/jam/bootsrap.js
	 * 
	 * @return boolean
	 */
	public function save()
	{
		$pathResolver = $this->container->get('djeg_jam.path_resolver');

		return file_put_contents($pathResolver->getWebPath().'/jam/bootstrap.js', $this->display());
	}

	/**
	 * Construct the bootstrap generator
	 * 
	 * @param Container $container
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}
}