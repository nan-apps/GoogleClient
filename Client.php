<?php
namespace GoogleClient;

use Google_Client;
use GoogleClient\GCException;
use GoogleClient\Store\CredentialsStore;

Class Client {

	private $api_client;
	private $store;

   /** 
    * Construct the wrapper Google Client.
    * @param Google_client $api_client
    * @param CredentialsStore $store
    */
	public function __construct( Google_Client $api_client,
								 CredentialsStore $store ){

		$this->api_client = $api_client;
		$this->store = $store;

	}	

	/**
	 * Get de Google Api Client
	 * @return GoogleClient
	 */
	 public function getApiClient(){
	 	return $this->api_client;
	 } 

	/**
	 * Ask the google api client for the autorization link
	 * @return string
	 */ 
	public function getAuthorizationLink(){
		return $this->api_client->createAuthUrl();
	}
   
	/**
	 * Gets the token from the auth code and store credentilas in disk
	 * @param string $auth_code
	 * @return boolean
	 */ 
	public function createCredentials( $auth_code ){
		
		$access_token = $this->api_client->fetchAccessTokenWithAuthCode( $auth_code );

		$this->_validateToken( $access_token );
			    
	    return $this->store->saveCredentials( $access_token );

	}	

	/**
	 * True if the store has credentials
	 * @return boolean
	 */
	public function hasStoredCredentials(){		
		return $this->store->hasCredentials();
	}

	/**
	 * Load credentials from store if exist. 
	 * If access token expired, refresh token and re store it 
	 * @return boolean
	 */
	public function loadCredentials(){

		$access_token = $this->store->restoreCredentials();
		if( $access_token ){

			$this->api_client->setAccessToken( $access_token );

			return $this->_refreshAndStoreTokenIfExpired();			

		} else {
			return false;
		}
	    
	}

	/**
	 * If token expired refresh it and store it.
	 * If no refresh token delete credentials so user has to authorize again
	 * @return boolean
	 */ 	
	private function _refreshAndStoreTokenIfExpired(){
		if ( $this->api_client->isAccessTokenExpired() ) {
			$refresh_token = $this->api_client->getRefreshToken();
			if( $refresh_token ){
		    	$access_token = $this->api_client->fetchAccessTokenWithRefreshToken( $refresh_token );
				return $this->store->saveCredentials( $access_token );		    				
			} else {
				$this->store->deleteCredentials();
				return false;
			}
		} else {
			return true;
		}
	}

	/**
	 * Validate acces token
	 * @param array $access_token
	 * @throws GCException
	 */ 
	private function _validateToken( $access_token ){		
		if( isset( $access_token['error'] ) ){			
			throw new GCException("Access ticket error, try again. ".var_export( $access_token, true ));			
		}

	}
	



}