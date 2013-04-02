<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Twig;

use Twig_TokenParser;
use Twig_Token;
use Djeg\JamBundle\Twig\Node\JamNode;

/**
 * Add the token "jam" to your twig extension file
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamTokenParser extends Twig_TokenParser
{
	/**
	 * @var Container $container
	 * @access private
	 */
	private $container;

	/**
	 * Get the parser tag
	 *
	 * @return string
	 */
	public function getTag()
	{
		return 'jam';
	}

	/**
	 * Parse the given jam tag
	 * 
	 * @return Twig_Node
	 */
	public function parse(Twig_Token $token)
	{
		$lineNo = $token->getLine();

		// $this->parser->expect(Twig_Token::NAME_TYPE);
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

		// get the environment
		$env = $this->container->getParameter('kernel.environment');
		
		// get the assets :
		$webPath = $this->container->get('request')->getBasePath();
		$assets = array();
		if( $env != 'prod' ) {
			$assets[] = '<script src="'.$webPath.'/jam/require.js'.'"></script>';
			$assets[] = '<script src="'.$webPath.'/jam/bootstrap.js'.'"></script>';
		} else {
			// get the compiled file path
			$compiledPath = $this->container->getParameter('djeg_jam.compiled_output');
			$assets[] = '<script src="'.$webPath.'/'.$compiledPath.'"></script>';
		}

		return new JamNode($assets, $lineNo, $this->getTag());
	}

	/**
	 * Contruct the TokenParser
	 * 
	 * @param Container $container
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

}