<?php

/*!
 *
 * BcryptHasher.php
 *
 * A class to hash passwords using bcrypt
 *
 * @author			Tim Bennett
 * @version			1.0.3
 * @license			www.texelate.co.uk/mit-license/
 *
 * Download the latest version at www.texelate.co.uk/lab/project/bcrypt-hasher/
 *
 * Copyright (c) 2015 Texelate Ltd, www.texelate.co.uk
 *
 */ 



/**
 * Class declaration
 */
class BcryptHasher {


	/**
	 * Properties
	 */
	private $rounds;					// The number of rounds, higher = more secure
	private $defaultRounds 	= 9;		// Default number of rounds, 9 won't slow your scripts down too much but it's still quite slow
	const   MIN_ROUNDS	   	= 4;		// Minimum rounds, as set by bcrypt
	const   MAX_ROUNDS		= 31;		// Maximum rounds, as set by bcrypt
	const   ERR_NO_BLOWFISH	= 0;		// Error code thrown if bcrypt isn't installed
	const	SALT_LENGTH		= 22;		// Length of the salt


	/**
	 * Constructor
	 *
	 * @param int	rounds	Number of rounds
	 */
	function __construct($rounds = null) {
	
		// Default rounds
		if($rounds === null) {
		
			$rounds = $this->defaultRounds;
			
		}
	
		// Check blowfish is available
		if(defined('CRYPT_BLOWFISH') === false || CRYPT_BLOWFISH === false) {
		
			throw new \Exception('Blowfish is not installed', self::ERR_NO_BLOWFISH);
			
		}
			
		// Set the number of rounds
		$this->setRounds($rounds);
	
	}
	
	
	/**
	 * Generates a random salt and hashes the input
	 *
	 * @param   string   input   		The string to hash
	 * @return  string   Anonymous		The hashed string
	 */
	public function hash($input) {
	
		// Blowfish salt can be ./0-9A-Za-z
		$salt 			= '';
		$saltChars 		= array_merge(range('A', 'Z'), 
		                              range('a', 'z'), 
		                              range(0, 9), 
		                              array('.', '/'));
		                              
		// Create a single string from the salt
		$saltString = '';
		
		foreach($saltChars as $char) {
		
			$saltString .= $char;
		
		}
		
		// Make a random 22 character salt
		for($i = 0; $i < self::SALT_LENGTH; $i ++) {
		
			// Use mt_rand() to generate the salt, while not cryptographically secure it's good enough for a salt 
			$salt .= $saltChars[mt_rand(0, strlen($saltString) - 1)];
			
		}
		
		// $2a$ is subject to high-bit attacks, $2y$ should be used instead
		// However, it is only available in PHP 5.3.7 and later
		// More info: http://www.php.net/security/crypt_blowfish.php
		if(PHP_VERSION_ID < 50307) {
			
			$char = 'a';
			
		}
		else {
		
			$char = 'y';
			
		}
			
		// Create the blowfish salt based on the PHP version and number of rounds
		$salt = sprintf('$2' . $char . '$%02d$', $this->rounds) . $salt;
		
		return crypt($input, $salt);
	
	}
	
	
	/**
	 * Compares the input and the hash
	 *
	 * @param   string   input   		The input to compare
	 * @param   string   hash   		The original hash
	 * @return  bool     Anonymous		True if matched, false if not
	 */
	public function compare($input, $hash) {
		
		if(crypt($input, $hash) == $hash) {
		
		    return true;
		}
		else {
		
		   return false;
		   
		}
	
	}
	
	
	/**
	 * Sets the number of rounds
	 *
	 * @param   int   rounds   	Number of rounds
	 */
	public function setRounds($rounds) {
	
		// Cast to int
		$this->rounds = (int) $rounds;
		
		// For Blowfish the rounds must be between 4...
		if($this->rounds < self::MIN_ROUNDS) {
		
			$this->rounds = self::MIN_ROUNDS;
		
		}
			
		// ...and 31
		if($this->rounds > self::MAX_ROUNDS) {
		
			$this->rounds = self::MAX_ROUNDS;
			
		}
	
	}
	
}

?>