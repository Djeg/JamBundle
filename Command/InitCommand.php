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
use Djeg\JamBundle\Packager\Packager;

/**
 * This command initialize a package.json file for your jam
 * project.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class InitCommand extends ContainerAwareCommand
{

	/**
	 * Configure the command
	 */
	public function configure()
	{
		$this
			->setName('djeg_jam:init')
			->setDescription('Initialize a package.json file for setting up dependencies')
			->addArgument('bundle', InputArgument::OPTIONAL, 'You can precise a bundle name and generate a corect '.
				'package.json on it.');
		;
	}

	/**
	 * execute the command and gnerate a corect package.json
	 * file.
	 * 
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$package = new Packager();
		$pathResolver = $this->getContainer()->get('djeg_jam.path_resolver');
		// get the dialog
		$dialog = $this->getHelperSet()->get('dialog');
		// try to get the project name
		$package->setName($dialog->ask(
			$output,
			'Enter your project name : ',
			''
		));

		// enter the version
		$package->setVersion($dialog->ask(
			$output,
			'Enter your project version : ',
			'0.0.1'
		));

		// enter description
		$package->setDescription($dialog->ask(
			$output,
			'Enter a short description : ',
			''
		));

		while($dialog->askConfirmation(
			$output,
			'<question>Do you wan\'t to set a dependency ? [yes]</question> ',
			true
		)) {
			$vendor = $dialog->ask(
				$output,
				'Enter the vendor name : ',
				''
			);
			$version = $dialog->ask(
				$output,
				'Enter the version : ',
				''
			);
			$package->addDependency($vendor, $version);
		}

		// get the bundle argument :
		$bundleName = $input->getArgument('bundle');

		// Set up the base url :
		if( ! $bundleName ){
			$package->setBaseUrl($this->getContainer()->getParameter('djeg_jam.base_url'));
		}

		// set up the bundles paths :
		if( ! $bundleName ){
			$package->setPaths($pathResolver->getBundlesPackagerPaths());
		}

		// generate the package.json
		if( ! $bundleName ) {
			$package->save($pathResolver->getWebPath());
		} else {
			if( ! is_dir($pathResolver->getBundlePath($bundleName).'/Resources/public/scripts') ) {
				mkdir($pathResolver->getBundlePath($bundleName).'/Resources/public/scripts');
			}
			$package->save($pathResolver->getBundlePath($bundleName).'/Resources/public/scripts');
		}

		$output->writeln(sprintf(
			'<info>package.json generated with success into %s !</info>',
			( $bundleName ) ? $bundleName : 'web/'
		));
	}
}
