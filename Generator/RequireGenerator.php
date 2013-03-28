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
 * This class permit to generate a corect require.js file including a corect
 * path configuration and also the bootstrapped code from your bundle.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class RequireGenerator extends Generator
{

	/**
	 * @var string $baseUrl
	 * @access private
	 */
	private $webDir;

	/**
	 * @var string $webServerPath
	 * @access private
	 */
	private $webServerPath;

	/**
	 * @var array $bundles
	 * @access private
	 */
	private $bundles;

	/**
	 * Generate the require.js file
	 * 
	 * @return string
	 */
	public function generate()
	{
		if( file_exists($this->webDir.'/jam/require.js') ) {
			$requireFile = file_get_contents($this->webDir.'/jam/require.js');
		} else {
			throw new \RuntimeException('No jam require.js file exists, maybe you have to install jam. '.gettype($this->webDir));
		}

		// Parse the bundles name and add the bundle web path if
		// assets exists and the boostrap script (main.js)
		$bundlePaths = array();
		$bootstraps = array();
		foreach( $this->bundles as $b ){
			if( is_dir($b->getPath().'/Resources/public/js') ) {
				$assetBundleName = strtolower(str_replace('Bundle', '', $b->getName()));
				$bundlePaths['@'.$b->getName()] = 'bundles/'.$assetBundleName.'/js/';
			}

			if( file_exists($b->getPath().'/Resources/public/js/main.js') ) {
				$bootstraps[] = file_get_contents($b->getPath().'/Resources/public/js/main.js');
			}
		}

		return $this->render(__DIR__.'/../Resources/views', 'require.js.twig', array(
			'require' => $requireFile,
			'baseUrl' => $this->webServerPath,
			'bundles' => $bundlePaths,
			'bootstraps' => $bootstraps
		));
	}

	/**
	 * Constructor
	 * 
	 * @param Container $container
	 */
	public function __construct($container)
	{
		$this->webDir = $container->get('kernel')->getRootDir().'/../web';

		if( ! $container->getParameter('djeg_jam.uri') ){
			$this->webServerPath = '/';
		} else {
			$this->webServerPath = $container->getParameter('djeg_jam.uri');
		}

		$this->bundles = $container->get('kernel')->getBundles();
	}

}