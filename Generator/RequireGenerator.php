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
class RequireGenerator extends ModuleGenerator
{
	/**
	 * Rewrite the generate method
	 * 
	 * @param string $module
	 */
	public function generate($module)
	{
		$skeleton = 'require.js.twig';

		$target = $module.'.js';

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
		$this->renderFile($this->skeletonDir, $skeleton, $this->basePath.'/'.$target, array());
	}
}