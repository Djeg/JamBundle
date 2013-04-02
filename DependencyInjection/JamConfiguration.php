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
	        		->info('The command or path to the jam binary')
	        	->end()
	        	->scalarNode('node')
	        		->defaultNull()
	        		->info('The path to the binary of your nodejs (required for compilation)')
	        	->end()
	        	->scalarNode('rjs')
	        		->defaultNull()
	        		->info('The path to the binary r.js scripts (required for compilation)')
	        	->end()
	        	->scalarNode('base_url')
	        		->defaultValue('/')
	        		->info('Precised the web uri from your server')
	        	->end()
	        	->scalarNode('compiled_output')
	        		->defaultValue('app.min.js')
	        		->info('The compiled output file name')
	        	->end()
	        ->end();

        return $treeBuilder;
    }
}