<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Twig;

use Twig_Extension;

/**
 * Jam twig extension to display the corect require
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamExtension extends Twig_extension
{
	/**
	 * @var sting $container
	 * @access private
	 */
	private $container;

	/**
	 * Extension name
	 */
	public function getName()
	{
		return 'djeg_jam_extension';
	}

	/**
	 * set the environment
	 * 
	 * @param Container $container
	 */
	public function injector($container)
	{
		$this->container = $container;
	}

	/**
	 * Display the script tag for the jam require file
	 * 
	 * @return string
	 */
	public function getJamSrc()
	{
		if( $this->container->getParameter('kernel.environment') !== 'prod' ) {
			$src = $this->container->get('router')->generate('djeg_jam.application', array('_format' => 'js'));
		} else {
			$src = $this->container->get('request')->getBasePath().'/app.min.js';
		}

		return $src;
	}

	/**
	 * Return the function
	 * 
	 * @return array
	 */
	public function getGlobals()
	{
		return array(
			'jam' => array(
				'src' => $this->getJamSrc()
			)
		);
	}
}