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
	 * @var array $lastCommand, the last command return
	 * @access private
	 */
	private $lastCommand;

	/**
	 * Execute a command and return the state
	 * 
	 * @param string $toExecute
	 * @param array $options
	 * @return integer, the commnd status 
	 */
	public function execute($toExecute, array $options)
	{
		// format the options :
		$opts = '';
		foreach( $options as $opt ){
			$o = explode(':', $opt);

			$opts .= '--'.implode('=', $o).' ';
		}

		// execute the command :
		exec($this->binary.' '.$toExecute.' '.$opts, $this->lastCommand, $state);

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
	}

	/**
	 * Constructor
	 * 
	 * @param string $binary
	 */
	public function __construct($binary)
	{
		$this->binary = $binary;
	}
}