<?php

use PHPUnit\Framework\TestCase;
use GoogleClient\Factories\ServiceCalendarFactory;
use \Mockery as m;

class ServiceCalendarFactoryTest extends TestCase {

	public function tearDown(){
 		m::close();
 	}

	public function testCreateShouldReturnACalendar(){

		$calendar = ServiceCalendarFactory::create( 
			m::mock('GoogleClient\Client'),
			'',
			m::mock('\Google_Service_Calendar')
		);

	 	$this->assertInstanceOf( 'GoogleClient\Services\Calendar', $calendar );

	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  
	public function testCreateRequiredDependencies(){
		ServiceCalendarFactory::create();
	}

}