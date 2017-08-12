# Google api Wrapper

## Services

1. Calendar


## Calendar example

```php

	require_once('vendor/autoload.php');


	$conf = include( 'conf.php' );

	$client = ClientCalendarFactory::create( 
		$conf('app_name'), $conf('client_secret'), $conf('auth_redirect_uri') 
	);		

	if( !$client->hasStoredCredentials() ){
		$auth_link = $client->getAuthorizationLink();
		//go to link and get the $auth_code		
		$client->createCredentials( $auth_code );
	}

	$client->loadCredentials();

	$service = ServiceCalendarFactory::create( $client, $conf('calendar_id') );


	//new calendar event
	$event = $service->addEvent( new Google_Service_Calendar_Event( /*data*/ ) );

	//update calendar event
	$event = $service->updateEvent( 'event_id', new Google_Service_Calendar_Event( /* data */ ) );

	//delete calendar event
	$service->deleteEvent( 'event_id' );


```