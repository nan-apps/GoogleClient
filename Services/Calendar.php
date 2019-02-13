<?php
namespace GoogleClient\Services;

use GoogleClient\Client;
use GoogleClient\GCException;
use \Google_Service_Calendar;

Class Calendar{

	private $calendar_id;	
	private $api_service;

	/**
	 * 
	 */ 
	public function __construct( Google_Service_Calendar $api_service, $calendar_id  ){

		$this->api_service = $api_service;
		$this->calendar_id = $calendar_id;

		//parent::__construct( $client );

	}

	/**
	 * @param Google_Service_Calendar_Event $event
	 * @return Google_Service_Calendar_Event
	 * @throws GCException
	 */ 
	public function addEvent( \Google_Service_Calendar_Event $event ){
		try {	

			return $this->api_service->events->insert( $this->calendar_id, $event, ['sendUpdates' => 'all'] );

		} catch ( \Google_Service_Exception $e ) {
			prd( $e );
			throw new GCException("Error creating event, try again");										
		}

	}

	/**
	 * @param $event_id
	 * @param Google_Service_Calendar_Event $event
	 * @return Google_Service_Calendar_Event
	 * @throws GCException
	 */ 
	public function updateEvent( $event_id, \Google_Service_Calendar_Event $changes ){

		try {	

			return $this->api_service->events->patch( $this->calendar_id, $event_id, $changes, ['sendUpdates' => 'all'] );

		} catch ( \Google_Service_Exception $e ) {
			$this->_manageGoogleServiceException( $e, 'Error updating event, try again' );
		}

	}

	/**
	 * @param $event_id
	 * @param Google_Service_Calendar_Event $event
	 * @return boolean	 
	 * @throws GCException
	 */ 
	public function deleteEvent( $event_id ){

		try {			

			$rsp = $this->api_service->events->delete( $this->calendar_id, $event_id );			
			return is_a( $rsp, 'GuzzleHttp\Psr7\Response' );

		} catch ( \Google_Service_Exception $e ) {	
			if( $e->getCode() == '410' ){
				return true; //event already deleted
			} else {
				return $this->_manageGoogleServiceException( $e, 'Error deleting event, try again' );						
			}
		}

	}
	

	private function _manageGoogleServiceException( \Google_Service_Exception $e, $default_msg = 'Error, try again' ){
		if( $e->getCode() == '404' ){
			throw new GCException("Event not found in google calendar", 404);				
		} else {
			throw new GCException( $default_msg );				
		}			
	}

}