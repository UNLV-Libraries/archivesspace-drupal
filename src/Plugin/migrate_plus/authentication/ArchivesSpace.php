<?php

namespace Drupal\archivesspace\Plugin\migrate_plus\authentication;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\archivesspace\ArchivesSpaceSession;
use Drupal\migrate\MigrateException;
use Drupal\migrate_plus\AuthenticationPluginBase;


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
    // Create the session
    // Send migration config auth options to the Session object
    if( isset($this->configuration['base_uri']) ||
        isset($this->configuration['username']) ||
        isset($this->configuration['password']) ){
      // Get Config Settings
      $base_uri = ( isset($this->configuration['base_uri']) ? $this->configuration['base_uri'] : '' );
      $username = ( isset($this->configuration['username']) ? $this->configuration['username'] : '' );
      $password = ( isset($this->configuration['password']) ? $this->configuration['password'] : '' );

      $session = ArchivesSpaceSession::withConnectionInfo(
          $base_uri, $username, $password
        );

    } else { // No login info provided by the migration config
      $session = new ArchivesSpaceSession();
    }

    // Login and return the session id to the Auth plugin
    return [
      'headers' => [
        'X-ArchivesSpace-Session' => $session->getSession(),
      ],
    ];
  }

}
