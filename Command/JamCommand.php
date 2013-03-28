<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\jamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * jam command.
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamCommand extends ContainerAwareCommand
{
	/**
	 * configure the command
	 */
	public function configure()
	{
		$this
			->setName('djeg_jam:jam')
			->setDescription('Execute a jam binary command and send add some path resolver solution.')
			->addArgument('cmd', InputArgument::REQUIRED, 'The jam command to execute')
			->addArgument('action', InputArgument::OPTIONAL, 'The action to execute (install, search ...)')
			->addOption('options', 'o',  InputOption::VALUE_NONE, 'If set, represent the options pass to the jam command'.
				'you can separate the different options with a "|" character and set a value with the ":" character. exemple :'.
				' php app/console djeg_jam:jam search jquery --options=repository:https://github.com/jquery/jquery');
		;
	}

	/**
	 * Execute the jam:help command
	 * 
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$cmd = $input->getArgument('cmd');

		$action = ( $input->getArgument('action') ) ?
			$input->getArgument('action') :
			'';


		$cmd = ( $action ) ?
			$cmd.' '.$action :
			$cmd;

		$opts = ( $input->getOption('options') ) ?
			explode( '|', $input->getOption('options') ) :
			array();

		$output->writeln('Execute jam "'.$cmd.'":');

		// change directory for the comand execution
		$act_dir = getcwd();
		chdir('web/');

		$exec = $this->getContainer()->get('djeg_jam.exec');

		$exec->execute($cmd, $opts);
		// return to the working directory
		chdir($act_dir);

		// display the output
		$exec->out($output);
	}
}