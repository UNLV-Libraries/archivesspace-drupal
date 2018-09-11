<?php

namespace Drupal\archivesspace;

use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * An ArchivesSpace authenticated session object.
 */
class ArchivesSpaceSession {

  protected $connectionInfo = [];

  protected $session = '';

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * Create a session with connection information.
   *
   * @param string $base_uri
   *   The base URI for the ArchivesSpace API.
   * @param string $username
   *   The username to use for authentication.
   * @param string $password
   *   The password to use for authentication.
   */
  public static function withConnectionInfo($base_uri, $username, $password) {
    if (!preg_match("@^https?://@", $base_uri)) {
      throw new InvalidArgumentException('Could not connect with invalid base URI: ' . $base_uri);
    }
    if (empty($username) || empty($password)) {
      throw new InvalidArgumentException('Could not connect. Either the username or password was missing.');
    }
    $instance = new self();
    $instance->connectionInfo = [
      'base_uri' => $base_uri,
      'username' => $username,
      'password' => $password,
    ];

    return $instance;
  }

  /**
   * Either logs in or returns the current session.
   *
   * @return ArchivesSpaceSession
   *   The ArchivesSpace session object
   */
  public function getSession() {
    if (empty($this->session)) {
      $this->login();
    }
    return $this->session;
  }

  /**
   * Issues an ArchivesSpace request.
   *
   * @param string $type
   *   The type of Request to issue (usually GET or POST)
   * @param string $path
   *   The API path to use for the request.
   * @param array $parameters
   *   Parameters to be passed with the request.
   */
  public function request(string $type, string $path, array $parameters = []) {
    if (!in_array($type, ['GET', 'POST'])) {
      throw new InvalidArgumentException('Cant\'t make an ArchivesSpace request with type: ' . $type);
    }
    if (empty($this->session)) {
      $this->login();
    }
    $client = new Client(['base_uri' => $this->connectionInfo['base_uri']]);

    $response = $client->request($type,
                                 $path,
                                 [
                                   'query' => $parameters,
                                   'headers' => [
                                     'X-ArchivesSpace-Session' => $this->session,
                                   ],
                                 ]
                                 );

    return json_decode($response->getBody(), TRUE);

  }

  /**
   * Login to ArchivesSpace.
   */
  protected function login() {
    $state_config = [];
    foreach (\Drupal::state()->getMultiple(['archivesspace.base_uri',
      'archivesspace.username',
      'archivesspace.password',
    ]
                                          ) as $key => $value) {
      $new_key = substr($key, 14);
      $state_config[$new_key] = $value;
    }
    $this->connectionInfo = array_replace($state_config, $this->connectionInfo);

    // Setup the client.
    $client = new Client([
      'base_uri' => $this->connectionInfo['base_uri'],
    ]);

    // Form the query string and make the call.
    $login_url = '/users/' .
                 rawurlencode($this->connectionInfo['username']) .
                 '/login?password=' .
                 rawurlencode($this->connectionInfo['password']);

    $response = $client->post($login_url);

    // Return the Session ID from the response.
    $login_response = json_decode($response->getBody(), TRUE);
    $this->session = $login_response['session'];
  }

}
