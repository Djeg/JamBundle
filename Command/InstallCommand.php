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
 * This install all the depencies precised into a given bundle or
 * automatially merged packages.json files present into the bundles.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class InstallCommand extends ContainerAwareCommand
{
	/**
	 * Configure the command
	 */
	public function configure()
	{
		$this
			->setName('djeg_jam:install')
			->setDescription('Install dependencies from package(s) informations.')
			->addArgument('bundle', InputArgument::OPTIONAL, 'The bundle package to install. '.
				'If not precised, all the bundles will be parsed and installed.')
		;
	}

	/**
	 * Execute the command.
	 * 
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Installing packages ...');
		// Get the corest package.json file
		$pathResolver = $this->getContainer()
			->get('djeg_jam.path_resolver');
		if( file_exists($pathResolver->getWebPath().'/package.json') ) {
			$package = new Packager($pathResolver->getWebPath());
		} else {
			$package = new Packager();
		}

		// Update package baseUrl :
		$package->setBaseUrl($this
			->getContainer()
			->getParameter('djeg_jam.base_url')
		);

		// Update packages paths
		$package->setPaths($pathResolver->getBundlesPackagerPaths());

		// Merge the package.json bundle(s) file(s)
		if( $bundle = $input->getArgument('bundle') ) {

			Packager::mergePackage($package, $pathResolver, array($bundle));
		} else {

			$bundles = $this->getContainer()
				->get('kernel')
				->getBundles();
			Packager::mergePackage($package, $pathResolver, $bundles);
		}

		// create the package.json
		$package->save($pathResolver->getWebPath());
		$output->writeln('<info>Package(s) installed</info>');

		// Installed dependencies
		$output->writeln('Install dependencies ...');
		$exec = $this->getContainer()
			->get('djeg_jam.exec');
		$exec->execute('install', array());
		$exec->out($output);
		$output->writeln('<info>Dependencies installed</info>');

		// rebuild configuration
		$output->writeln('Rebuild configuration ...');
		$exec->execute('rebuild', array());
		$exec->out($output);
		$output->writeln('<info>Configuration rebuilded</info>');

		$output->writeln('Generate bootstrap ...');
		// create the boostrap.js with all the main.js located at the root
		// level of Resources/script of your bundle.
		$bootstraper = $this->getContainer()
			->get('djeg_jam.bootstrap_generator');
		// Save the bootsrtaper
		$bootstraper->save();
		$output->writeln('<info>Bootstrap generate with success</info>');
	}
}