<?php
namespace GoogleClient\Factories;

use Google_Client;
use Google_Service_Calendar;
use GoogleClient\Client;
use GoogleClient\Store\CredentialsStore;
use GoogleClient\Store\CredentialsSessionStore;

Class ClientCalendarFactory{

	/**
	 * Create calendar client
	 * @param string $app_name	 
	 * @param string $client_secret
	 * @param string $redirect_uri
	 * @param Google_client $api_client
	 * @param CredentialsStore $store
	 * @return Google_Client
	 */ 
	public static function create( $app_name, 
								   $client_secret, 
								   $redirect_uri, 
								   Google_Client $api_client = null,
								   CredentialsStore $store = null ){
		
		if( !$api_client ){			
			$api_client = new Google_Client( $api_client_config );
			$api_client->setAuthConfig( $client_secret );
			$api_client->setApplicationName( $app_name );
			$api_client->setScopes( Google_Service_Calendar::CALENDAR );
			$api_client->setAccessType('offline');
			$api_client->setRedirectUri( $redirect_uri );
		}
		

		$client = new Client( 
			$api_client,
			$store ? $store : new CredentialsSessionStore()
		);

		

        return $client;

	}


}