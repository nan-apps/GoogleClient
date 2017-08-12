<?php

use AfipClient\ACException;
use AfipClient\Factories\BillerFactory;

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}


if( !file_exists( 'conf.php' ) ){	
    throw new Exception("Copia el contenido de conf.example.php a conf.php y completa los datos correctamente\n");	
}

require_once('vendor/autoload.php');


$conf = include( 'conf.php' );

$client = ClientCalendarFactory::create( $conf('app_name'), 
										 $conf('client_secret'), 
										 $conf('auth_redirect_uri') );		

if( !$client->hasStoredCredentials() ){
	$auth_link = $client->getAuthorizationLink();

	printf("Open the following link in your browser:\n%s\n", $auth_link);
    
    print 'Enter verification code: ';
    $auth_code = trim(fgets(STDIN));
	
	$client->createCredentials( $auth_code );
}

$client->loadCredentials();

$service = ServiceCalendarFactory::create( $client, 
										   $conf('calendar_id') );


//new calendar event
$event = $service->addEvent( new Google_Service_Calendar_Event( /*data*/ ) );

//update calendar event
$event = $service->updateEvent( 'event_id', 
								new Google_Service_Calendar_Event( /* data */ ) );

//delete calendar event
$service->deleteEvent( 'event_id' );




