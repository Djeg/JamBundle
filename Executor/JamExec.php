<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\Executor;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * This class execute a jam command and display the output
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class JamExec
{
	/**
	 * @var string $binary, jam binary path
	 * @access private
	 */
	private $binary;

	/**
	 * @var string $binary, jam binary path
	 * @access private
	 */
	private $rjs;

	/**
	 * @var string $node, the nodejs binary
	 * @access private
	 */
	private $node;

	/**
	 * @var array $lastCommand, the last command return
	 * @access private
	 */
	private $lastCommand;

	/**
	 * @var Container $container
	 * @access private
	 */
	private $container;

	/**
	 * Execute a command and return the state
	 * 
	 * @param string $toExecute
	 * @param array $options
	 * @return integer, the commnd status 
	 */
	public function execute($toExecute, array $options)
	{
		// change directory to the web directory
		$actDir = getcwd();
		$webDir = $this->container
			->get('djeg_jam.path_resolver')
			->getWebPath();
		chdir($webDir);

		// format the options :
		$opts = '';
		foreach( $options as $opt ){
			$o = explode(':', $opt);

			$opts .= '--'.implode('=', $o).' ';
		}

		// execute the command :
		exec($this->binary.' '.$toExecute.' '.$opts, $this->lastCommand, $state);

		// return to the original directory
		chdir($actDir);

		return $state;
	}

	/**
	 * Compiled a with the -o build.js option.
	 * 
	 * @throws RuntimeException, if no rjs path is precised in the configuration.
	 * 
	 * @return integer, the compiler state.
	 */
	public function compile()
	{
		if( null === $this->rjs or null === $this->node ){
			throw new \RuntimeException("Unable to compile your javascripts. It missing the r.js and/or the nodejs paths(s) in your config.yml file.");
		}

		// change directory to the web directory
		$actDir = getcwd();
		$webDir = $this->container
			->get('djeg_jam.path_resolver')
			->getWebPath();
		chdir($webDir);

		// execute the command :
		exec($this->node.' '.$this->rjs.' -o build.js', $this->lastCommand, $state);

		// return to the original directory
		chdir($actDir);

		return $state;
	}

	/**
	 * Display the last command esult
	 * 
	 * @param OutputInterface $output
	 */
	public function out(OutputInterface $output)
	{
		foreach( $this->lastCommand as $k => $o ){
			$output->writeln("\t".$o);
		}
		$this->lastCommand = array();
	}

	/**
	 * Constructor
	 * 
	 * @param Container $container
	 */
	public function __construct($container)
	{
		$this->binary = $container->getParameter('djeg_jam.jam');
		$this->rjs    = $container->getParameter('djeg_jam.rjs');
		$this->node   = $container->getParameter('djeg_jam.node');
		$this->container = $container;
	}
}