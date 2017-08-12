<?php

use PHPUnit\Framework\TestCase;
use GoogleClient\Factories\ClientCalendarFactory;
use \Mockery as m;

class ClientCalendarFactoryTest extends TestCase {

	public function tearDown(){
 		m::close();
 	}

	public function testCreateShouldReturnAClient(){

		//when i perform this action
		$client = ClientCalendarFactory::create( 
			'',
			[],
			'',
			m::mock('\Google_Client'),
			m::mock('GoogleClient\Store\CredentialsSessionStore'),
			[]
		);

		//the i expect this response
	 	$this->assertInstanceOf( 'GoogleClient\Client', $client );

	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  
	public function testCreateRequiredDependencies(){
		ClientCalendarFactory::create();
	}

}