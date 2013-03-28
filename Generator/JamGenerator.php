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
 * This abstract class represent the generator default
 * class for any Jam code generation
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamGenerator extends Generator
{

	/**
	 * @var string $skeletonDir
	 * @access private
	 */
	private $skeletonDir;

	/**
	 * Default constructor
	 * 
	 * @param string $basePath = '';
	 */
	public function __construct($basePath = '')
	{
		$this->skeletonDir = __DIR__.'/../Resources/skeleton';
		$this->basePath = $basePath;
	}

	/**
	 * Create recursively target directory if they have to !
	 * 
	 * @param string $target
	 * @param string $dir = null
	 */
	public function createTargetDirectory($target, $dir = null)
	{
		if( $dir === null ){
			$dir = $this->basePath;
		}

		$exploder = explode('/', $target);
		$script = array_pop($exploder);
		
		if( count($exploder) == 0 ) {
			return;
		}

		$directory = array_shift($exploder);

		if( ! is_dir($dir.'/'.$directory) ) {
			mkdir($dir.'/'.$directory);
		}

		if( count($exploder) > 0) {
			$this->createTargetDirectory(implode('/', $exploder).'/'.$script, $dir.'/'.$directory);
		}
	}

	/**
	 * Default generation method. It takes two parameters, the first
	 * is the target file name. You can precise any directory by separate
	 * your target path with a "/".
	 * 
	 * @param string $skeleton
	 * @param string $target
	 * @param array $parameters
	 */
	public function generate($skeleton, $target, $parameters)
	{
		$skeleton .= '.twig';
		// test skeleton existence
		if( ! file_exists($this->skeletonDir.'/'.$skeleton) ){
			throw new \RuntimeException(
				sprintf(
					'Unable to generate the skeleton %s ! This skeleton seems to not exists :( !',
					$this->skeletonDir.'/'.$skeleton
				)
			);
		}

		// Create recursive directory precised by the target :
		$this->createTargetDirectory($target);

		// Render the target file :
		$this->renderFile($this->skeletonDir, $skeleton, $this->basePath.'/'.$target, $parameters);
	}

}