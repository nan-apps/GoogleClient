<?php

use PHPUnit\Framework\TestCase;
use GoogleClient\Services\Calendar;
use \Mockery as m;

class ServiceCalendarTest extends TestCase {

	private $calendar;

	public function tearDown(){
 		m::close();
 	}

	public function setUp(){

 		$this->calendar = new Calendar(
			m::mock('\Google_Service_Calendar'),
			'id'
		);

 	}

	public function testInstance(){		
	 	$this->assertInstanceOf( 'GoogleClient\Services\Calendar', $this->calendar );
	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  	
	public function testInstanceWithNoArguments(){
		new Calendar();
	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  	
	public function testAddEventWithNoArguments(){
		$this->calendar->addEvent();
	}

	/**	 
	 * @expectedException \TypeError
	 */  	
	public function testAddEventWithWrongArguments(){
		$this->calendar->addEvent('');
	}


	public function testAddEvent(){
		
		$event_mock = m::mock('\Google_Service_Calendar_Event');

		$events_mock = m::mock('\Google_Service_Calendar_Resource_Events');
		$events_mock->shouldReceive(['insert' => $event_mock])
					->with( 'id', $event_mock )
					->once();

		$service_mock = m::mock('\Google_Service_Calendar');
		$service_mock->events = $events_mock;

		$calendar = new Calendar(
			$service_mock,
			'id'
		);

		$this->assertEquals( $calendar->addEvent( $event_mock ), $event_mock );

	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  	
	public function testUpdateEventWithNoArguments(){
		$this->calendar->updateEvent();
	}

	/**	 
	 * @expectedException \TypeError
	 */  	
	public function testUpdateEventWithWrongArguments(){
		$this->calendar->updateEvent('', '');
	}


	public function testUpdateEvent(){
		
		$event_id = 1;	
		$event_mock = m::mock('\Google_Service_Calendar_Event');

		$events_mock = m::mock('\Google_Service_Calendar_Resource_Events');
		$events_mock->shouldReceive(['patch' => $event_mock])
					->with( 'id',$event_id, $event_mock )
					->once();

		$service_mock = m::mock('\Google_Service_Calendar');
		$service_mock->events = $events_mock;

		$calendar = new Calendar(
			$service_mock,
			'id'
		);

		$this->assertEquals( $calendar->updateEvent( $event_id, $event_mock ), $event_mock );

	}

	public function testDeleteEvent(){
		
		$event_id = 1;	

		$empty_mock = m::mock('GuzzleHttp\Psr7\Response');

		$events_mock = m::mock('\Google_Service_Calendar_Resource_Events');
		$events_mock->shouldReceive(['delete' => $empty_mock ])
					->with( 'id', $event_id )
					->once();

		$service_mock = m::mock('\Google_Service_Calendar');
		$service_mock->events = $events_mock;

		$calendar = new Calendar(
			$service_mock,
			'id'
		);

		$this->assertTrue( $calendar->deleteEvent( $event_id ) );

	}


}