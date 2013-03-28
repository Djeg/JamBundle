<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * Jam controller
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamController extends ContainerAware
{
	/**
	 * Display the main script for Jam
	 * 
	 * @Route(
	 * 		"/application.{_format}",
	 * 		requirements={"_format" = "js"},
	 * 		defaults={"_format" = "js"},
	 * 		name="djeg_jam.application"
	 * )
	 * @Method({"GET"})
	 */
	public function applicationAction()
	{
		// get the require.js from jam
		$require = $this->container
			->get('djeg_jam.require_generator')
			->generate();

		return new Response($require);
	}
}
