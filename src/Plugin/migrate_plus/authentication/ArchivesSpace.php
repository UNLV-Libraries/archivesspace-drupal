<?php

namespace Drupal\archivesspace\Plugin\migrate_plus\authentication;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate_plus\AuthenticationPluginBase;
use GuzzleHttp\Client;


/**
 * Provides ArchivesSpace authentication for the HTTP resource.
 *
 * @Authentication(
 *   id = "archivesspace",
 *   title = @Translation("ArchivesSpace")
 * )
 */
class ArchivesSpace extends AuthenticationPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getAuthenticationOptions() {
    // Override Module Settings if set in migration config
    $state_config = [];
    foreach(\Drupal::state()->getMultiple(['archivesspace.base_uri',
                                           'archivesspace.username',
                                           'archivesspace.password']
                                          ) as $key => $value){
      $new_key = substr($key, 14);
      $state_config[$new_key] = $value;
    }
    $config_merge = array_replace($state_config, $this->configuration);

    // Setup the client
    $client = new Client([
      'base_uri' => $config_merge['base_uri'],
    ]);

    // Form the query string and make the call
    $login_url = '/users/' .
                 rawurlencode($config_merge['username']) .
                 '/login?password=' .
                 rawurlencode($config_merge['password']) ;
    $response = $client->post($login_url);

    // Return the Session ID from the response
    $login_response = json_decode($response->getBody(), true);
    $session_id = $login_response['session'];
    return [
      'headers' => [
        'X-ArchivesSpace-Session' => $session_id,
      ],
    ];
  }

}
