<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Djeg\JamBundle\Generator\JamGenerator;

/**
 * This command generate a module into the given bundle. The default
 * path is Resources/js/{{ModuleName}}
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class GenerateModuleCommand extends ContainerAwareCommand
{

	/**
	 * Configure the command
	 */
	public function configure()
	{
		$this
			->setName('djeg_jam:generate:module')
			->setDescription('Generate a javascript module that respect the AMD structure.')
			->addArgument('module', InputArgument::OPTIONAL, 'precise the bundle and the module name like that :'.
				' MyCoolBundle:ModuleName')
		;
	}

	/**
	 * Execute the command
	 * 
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$dialog = $this->getHelperSet()->get('dialog');
		// get the bundle and module name
		$exploder = explode(':', $input->getArgument('module'));

		// Test the exploder length
		while( count($exploder) < 2 ){
			$bundleName = $dialog->ask(
				$output,
				'Please enter the bundle and module name (<info>MyBundle:MyModule</info>) : ',
				null
			);
			$exploder = explode(':', $bundleName);
		}

		// get the bundle and module name
		list($bundle, $module) = $exploder;

		// Test the bundle existence :
		$kernelResolver = '@'.$bundle.'/';
		try {
			$bundlePath = $this->getContainer()->get('kernel')->locateResource($kernelResolver);
		} catch(\Exception $e) {
			throw new  \RuntimeException(sprintf(
				'Unable to find the bundle %s :(',
				$bundle	
			));
		}

		// We have the bundle try to locate the Resources
		if( ! is_dir($bundlePath.'/Resources') ) {
			mkdir($bundlePath.'/Resources');
		}
		if( ! is_dir($bundlePath.'/Resources/public') ) {
			mkdir($bundlePath.'/Resources/public');
		}
		if( ! is_dir($bundlePath.'/Resources/public/js') ){
			mkdir($bundlePath.'/Resources/public/js');
		}

		// Test the module existence :
		if( file_exists($bundlePath.'/Resources/public/js/'.$module.'.js') ) {
			$output->writeln('<info>The module '.$module.' always exists in the '.$bundle.' bundle !</info>');
			if( ! $dialog->askConfirmation(
				$output,
				'Do you want to rewrite them? [no] ',
				false
			)) {
				$output->writeln('<error>Module generation aborted</error>');
				return;
			}
		}

		// generate the module :
		$generator = new JamGenerator($bundlePath.'/Resources/public/js');
		$canonicalModule = explode('/', $module);
		$canonicalModule = array_pop($canonicalModule);
		$generator->generate('module.js', $module.'.js', array(
			'module' => str_replace('/', '.', $module),
			'canonicalModule' => $canonicalModule
		));

		// checking for bootstrap
		if( ! file_exists($bundlePath.'/Resources/public/js/main.js') ) {
			// ask for the bootstrap generation
			if ( $dialog->askConfirmation(
				$output,
				'<info>Do you want to generate a bootstrap script (main.js)? [yes] </info>',
				true
			) ) {
				$generator->generate('main.js', 'main.js', array());
			}
		}

		$output->writeln(sprintf(
			'<info>The module %s has been generate with success ;) !</info>',
			$module
		));
	}
}