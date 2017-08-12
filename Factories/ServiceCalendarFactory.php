<?php
namespace GoogleClient\Factories;

use GoogleClient\Client;
use GoogleClient\Services\Calendar;
use Google_Service_Calendar;

Class ServiceCalendarFactory{

	/**
	 * Create calendar service	 
	 * @return Calendar
	 */ 
	public static function create( Client $client, 
								   $calendar_id,
								   Google_Service_Calendar $api_service = null ){

		return new Calendar( $api_service ? $api_service : new Google_Service_Calendar( $client->getApiClient() ), 
							 $calendar_id );


	}


}