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

		// get the corect require.js :
		$require = $this->getContainer()
			->get('djeg_jam.require_generator')
			->generate(true);

		// get the web directory :
		$webDir = $this->getContainer()
			->get('kernel')
			->getRootDir().'/../web';

		// create the new require :
		file_put_contents($webDir.'/jam/bootstrap.js', $require);

		// change directory for a correct jam execution
		$actDir = getcwd();
		chdir($webDir);

		// execute the compile command from jam
		$jam = $this->getContainer()
			->get('djeg_jam.exec');
		$jam->execute('compile app.min.js', array('include:jam/bootstrap.js'));

		// Out jam result
		$jam->out($output);

		// Return to the corect directory
		chdir($actDir);

		$output->writeln('<info>Javascript compiled !</info>'); 
	}
}