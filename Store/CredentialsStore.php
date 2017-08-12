<?php

namespace GoogleClient\Store;

Interface CredentialsStore {

	/** 
	 * True if has credentilas in store
	 * @return boolean
	 */ 
	public function hasCredentials();

	/** 
	 * Save crederentials
	 * @param array $access_token
	 * @return boolean
	 */ 
	public function saveCredentials( Array $access_token );
	
	/**
	 * Restore credentials
	 * @return Array|false
	 */
	public function restoreCredentials();

	public function deleteCredentials();
	
	
}
