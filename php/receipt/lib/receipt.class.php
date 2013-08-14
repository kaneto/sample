<?php

/**
 * Receipt Check Class
 *
 * @package Receipt
 * @author  kaneto
 * @since   PHP 5.2.0
 * @version 1.0.0
 */
abstract class Receipt 
{
	/**
	 * Construct
	 *
	 * @return null
	 */
	public function __construct()
	{
	}

	/**
	 * Execute Check Data
	 *
	 */
	abstract public function exec();
}
