<?php

/*!
 *
 * Test script for BcryptHasher.php
 *
 * @author			Tim Bennett
 * @version			1.0.1
 *
 * Download the latest version at www.texelate.co.uk/lab/project/blowfish-hasher/
 *
 * Open source under the MIT license:
 *
 * Copyright (c) 2014 Tim Bennett, Texelate Ltd, www.texelate.co.uk
 *  
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *  
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *  
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */ 
 

/**
 * Include the class
 */
require 'BcryptHasher.php';


/**
 * - Create a Blowfish Hasher object
 * - You can optionally set the number of rounds here
 * - Rounds can be between 4 and 31
 * - More rounds is slower and more secure
 * - The default is 9
 * - You can change the rounds using $BcryptHasher->setRounds($rounds);
 */
try {

	$BcryptHasher = new BcryptHasher();
	
}
catch(Exception $e) {

	// Blowfish isn't installed, check your hosting setup
	// Any decent hosting company sohuld have this installed
	exit($e->getMessage());

}


/**
 * Variables to test
 */
$passwordToHash 		= 'testP@$$w0rd';
$incorrectPassword		= 'Wrong password!';


/**
 * Hash the password
 */
$hashedPassword = $BcryptHasher->hash($passwordToHash);


/**
 * Here's the hashed password; Blowfish stores the salt and the hash together
 */
echo '<p>Hashed password: ' . $hashedPassword . '</p>';


/**
 * Hashing is a one-way process so to check if a password is correct we hash the 
 * one inputted by a user (e.g. from a login page) and compare the hashed results
 */
echo '<pre>';
var_dump($BcryptHasher->compare($passwordToHash,    $hashedPassword)); // True, correct password
var_dump($BcryptHasher->compare($incorrectPassword, $hashedPassword)); // False, incorrect password
echo '</pre>';


?>