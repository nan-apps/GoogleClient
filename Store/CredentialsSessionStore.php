<?php

namespace GoogleClient\Store;

use GoogleClient\Store\CredentialsStore;

/**
 * Manage session credentials
 */ 

Class CredentialsSessionStore implements CredentialsStore {


	/**
	 * @param string $service_name
	*/
	public function __construct(){
	}

	
	/** 
	 * True if has credentilas in store
	 * @return boolean
	 */	
	public function hasCredentials(){		
		return isset( $_SESSION[ $this->_getIdentifier() ] );
	}

	/**
	 * Save credentials to session
	 * @param Array $access_token	 
	 */
	public function saveCredentials( Array $access_token ){		
		$_SESSION[ $this->_getIdentifier() ] = $access_token;		
		return true;
	}

	/**
	 * Restore credentials from session
	 * @return Array|false
	 */
	public function restoreCredentials(){

		return $this->hasCredentials() ? $_SESSION[ $this->_getIdentifier() ]
									   : false;									   
	}

	/**
	 * Delete credentials from session
	 * @return Array|false
	 */
	public function deleteCredentials(){
		unset( $_SESSION[ $this->_getIdentifier() ] );
	}
	
	/**
	 * Return session credentials key identifier
	 * @return string
	 */ 
	private function _getIdentifier(){

		return "google_client_access_token";

	}

}
