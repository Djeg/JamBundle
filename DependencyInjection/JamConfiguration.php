<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configure JamBundle
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamConfiguration implements ConfigurationInterface
{
	/**
	 * Return the configuration TreeBuilder that
	 * validate JamBundle configuration.
	 * 
	 * @return TreeBuilder
	 */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('djeg_jam');

        $rootNode
	        ->children()
	        	->scalarNode('jam')
	        		->defaultValue('jam')
	        		->info('The command or path to the jam binary (default to "jam").')
	        	->end()
	        	->scalarNode('uri')
	        		->defaultNull()
	        		->info('Precised the web uri from your server. You can set this option empty for a controller'.
	        			' utilisation but you must precised a value when you compile your code')
	        	->end()
	        ->end();

        return $treeBuilder;
    }
}