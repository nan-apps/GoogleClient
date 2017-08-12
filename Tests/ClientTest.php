<?php

use PHPUnit\Framework\TestCase;
use GoogleClient\Client;
use \Mockery as m;

class ClientTest extends TestCase {

	private $client;
	
	public function tearDown(){
 		m::close();
 	}

	public function setUp(){

 		$this->client = new Client(
 			m::mock('Google_Client'),
			m::mock('GoogleClient\Store\CredentialsStore')
		);

 	}

	public function testInstance(){		
	 	$this->assertInstanceOf( 'GoogleClient\Client', $this->client );
	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  	
	public function testInstanceWithNoArguments(){
		new Client();
	}

	public function testHasStoredCredentialsReturnFalse(){

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$store_mock->shouldReceive([
			'hasCredentials' => false	
		])->once();

		$client = new Client(
			m::mock('Google_Client'),
			$store_mock
		);

		$this->assertFalse( $client->hasStoredCredentials() );

	}

	/**	 
	 * @expectedException \ArgumentCountError
	 */  	
	public function testCreateCredentialsWithNoArgs(){

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$client = new Client(
			m::mock('Google_Client'),
			$store_mock
		);
		$client->createCredentials();

	}

	
	/**	 
	 * @expectedException GoogleClient\GCException
	 */  	
	public function testCreateCredentialsInvalidToken(){

		$api_client_mock = m::mock('Google_Client');
		$api_client_mock->shouldReceive([
			'fetchAccessTokenWithAuthCode' => ['error'=>'error']	
		])->once();
		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');

		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$client->createCredentials('');

	}

	
	public function testCreateCredentials(){

		$api_client_mock = m::mock('Google_Client');
		$api_client_mock->shouldReceive([
			'fetchAccessTokenWithAuthCode' => []	
		])->once();

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$store_mock->shouldReceive([
			'saveCredentials' => true	
		])->once();

		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$this->assertTrue( $client->createCredentials('') );

	}

	public function testLoadEmptyCredentials(){

		$api_client_mock = m::mock('Google_Client');		

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$store_mock->shouldReceive([
			'restoreCredentials' => false
		])->once();

		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$this->assertFalse( $client->loadCredentials() );

	}

	public function testLoadExpiredCredentials(){

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$store_mock->shouldReceive([
			'restoreCredentials' => ['espired_token']
		])->once();		

		$api_client_mock = m::mock('Google_Client');		
		$api_client_mock->shouldReceive('setAccessToken')
					    ->with( [ 'espired_token' ] )
					    ->once();
		$api_client_mock->shouldReceive([
			'isAccessTokenExpired' => true
		])->once();

		$api_client_mock->shouldReceive([
			'getRefreshToken' => 123
		])->once();

		$api_client_mock->shouldReceive([
			'fetchAccessTokenWithRefreshToken' => [ 'token' ]
		])->once()->with( 123 );

		$store_mock->shouldReceive([
			'saveCredentials' => true
		])->once()->with( ['token'] );


		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$this->assertTrue( $client->loadCredentials() );

	}

	public function testLoadExpiredCredentialsButNoFreshToken(){

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$store_mock->shouldReceive([
			'restoreCredentials' => ['espired_token']
		])->once();	
		

		$api_client_mock = m::mock('Google_Client');		
		$api_client_mock->shouldReceive('setAccessToken')
					    ->with( [ 'espired_token' ] )
					    ->once();
		$api_client_mock->shouldReceive([
			'isAccessTokenExpired' => true
		])->once();

		$api_client_mock->shouldReceive([
			'getRefreshToken' => false
		])->once();

		$api_client_mock->shouldNotReceive('fetchAccessTokenWithRefreshToken');
		$store_mock->shouldNotReceive('saveCredentials');

		$store_mock->shouldReceive('deleteCredentials');		

		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$this->assertFalse( $client->loadCredentials() );

	}

	public function testLoadCredentials(){

		$access_token = [ 'token' => '' ];

		$api_client_mock = m::mock('Google_Client');		
		$api_client_mock->shouldReceive('setAccessToken')
					    ->with( $access_token )
					    ->once();
		$api_client_mock->shouldReceive([
			'isAccessTokenExpired' => false
		])->once();

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		$store_mock->shouldReceive([
			'restoreCredentials' => $access_token
		])->once();

		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$this->assertTrue( $client->loadCredentials() );

	}




	public function testGetApiClient(){

		$this->assertInstanceOf( 'Google_Client', $this->client->getApiClient() ); 

	}


	public function testGetAuthorizationLink(){

		$api_client_mock = m::mock('Google_Client');		
		$api_client_mock->shouldReceive(['createAuthUrl' => 'http://'])
					    ->once();
		

		$store_mock = m::mock('GoogleClient\Store\CredentialsStore');
		
		$client = new Client(
			$api_client_mock,
			$store_mock
		);

		$this->assertEquals( $client->getAuthorizationLink(), 'http://' );

	}

	


}