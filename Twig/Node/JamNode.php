<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Twig\Node;

use Twig_Node;
use Twig_Compiler;

/**
 * This class compile a jam tag
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamNode extends Twig_Node
{
	/**
	 * @var array $assets
	 * @access private
	 */
	private $assets;

	/**
	 * Construct the JamNode
	 * 
	 * @param array   $assets
	 * @param integer $lineNo
	 * @param string  $tag
	 */
	public function __construct(array $assets, $lineNo, $tag)
	{
		parent::__construct(array(), array('scripts' => $assets), $lineNo, $tag);
	}

	/**
	 * Compile the node
	 * 
	 * @param Twig_Compiler $compiler
	 */
	public function compile(Twig_Compiler $compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write('$context[\'jam_script\'] = ')
			->repr($this->getAttribute('scripts'))
			->raw(";\n")
			->write('foreach( $context[\'jam_script\'] as $script){ echo $script; }')
		;
	}
}