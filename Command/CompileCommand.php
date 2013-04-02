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
 * Generate a compiled javascript with all your dependencies inside
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class CompileCommand extends ContainerAwareCommand
{
	/**
	 * Configure the command
	 */
	public function configure()
	{
		$this
			->setName('djeg_jam:compile')
			->setDescription('Compile all your javascript dependencies and bootstrap into one minified file.')
		;
	}

	/**
	 * Execute the compiller
	 * 
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Compile your javascript ...');

		// Get the compiled output name
		$compiledOutput = $this->getContainer()
			->getParameter('djeg_jam.compiled_output');

		// get the pathresolver
		$path = $this->getContainer()
			->get('djeg_jam.path_resolver');

		// get packager
		$packager = new Packager($path->getWebPath());

		// Generate the build.js file
		$packager->generateBuild($path, $compiledOutput);

		// execute the compile command from jam
		$jam = $this->getContainer()
			->get('djeg_jam.exec');
		$jam->compile();

		// Out jam result
		$jam->out($output);

		// delete build.js
		$packager->destroyBuild($path);

		$output->writeln('<info>Javascript compiled !</info>'); 
	}
}