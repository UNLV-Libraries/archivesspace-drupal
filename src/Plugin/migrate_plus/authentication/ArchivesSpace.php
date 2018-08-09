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
    $client = new Client([
      'handler' => $handlerStack,
      'base_uri' => $this->configuration['base_uri'],
    ]);
    $login_url = '/users/' .
                 rawurlencode($this->configuration['username']) .
                 '/login?password=' .
                 rawurlencode($this->configuration['password']) ;
    $response = $client->post($login_url);
    $login_response = json_decode($response->getBody(), true);
    $session_id = $login_response['session'];
    return [
      'headers' => [
        'X-ArchivesSpace-Session' => $session_id,
      ],
    ];
  }

}
