<?php

namespace Drupal\archivesspace;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use InvalidArgumentException;

class ArchivesSpaceSession {

  protected $connection_info = [];

  protected $session = '';

  public function __construct(){
  }

  public static function withConnectionInfo($base_uri, $username, $password){
    if( !preg_match("@^https?://@", $base_uri) ){
      throw new InvalidArgumentException('Could not connect with invalid base URI: '.$base_uri);
    }
    if(empty($username) || empty($password)){
      throw new InvalidArgumentException('Could not connect. Either the username or password was missing.');
    }
    $instance = new self();
    $instance->connection_info = [
      'base_uri' => $base_uri,
      'username' => $username,
      'password' => $password,
    ];

    return $instance;
  }

  public function getSession(){
    if(empty($this->session)){
      $this->login();
    }
    return $this->session;
  }

  public function request(string $type, string $path, $parameters = array()){
    if(!in_array($type, ['GET','POST'])){
      throw new InvalidArgumentException('Cant\'t make an ArchivesSpace request with type: '.$type);
    }
    if(empty($this->session)){
      $this->login();
    }
    $client = new Client(['base_uri' => $this->connection_info['base_uri']]);

    $response = $client->request($type,
                                 $path,
                                 [
                                   'query'=>$parameters,
                                   'headers' => [
                                     'X-ArchivesSpace-Session' => $this->session
                                     ]
                                   ]
                                 );

    return json_decode($response->getBody(),true);

  }

  protected function login(){
    $state_config = [];
    foreach(\Drupal::state()->getMultiple(['archivesspace.base_uri',
                                           'archivesspace.username',
                                           'archivesspace.password']
                                          ) as $key => $value){
      $new_key = substr($key, 14);
      $state_config[$new_key] = $value;
    }
    $this->connection_info = array_replace($state_config, $this->connection_info);

    // Setup the client
    $client = new Client([
      'base_uri' => $this->connection_info['base_uri'],
    ]);

    // Form the query string and make the call
    $login_url = '/users/' .
                 rawurlencode($this->connection_info['username']) .
                 '/login?password=' .
                 rawurlencode($this->connection_info['password']) ;

    $response = $client->post($login_url);

    // Return the Session ID from the response
    $login_response = json_decode($response->getBody(), true);
    $this->session = $login_response['session'];
  }


}
