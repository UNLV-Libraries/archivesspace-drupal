<?php

namespace Drupal\archivesspace\Plugin\migrate\source;

use DateTime;
use Drupal\archivesspace\ArchivesSpaceIterator;
use Drupal\archivesspace\ArchivesSpaceSession;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;

/**
 * Source plugin for retrieving data from ArchivesSpace.
 *
 * @MigrateSource(
 *   id = "archivesspace"
 * )
 */
class ArchivesSpaceSource extends SourcePluginBase {

  protected $session;
  protected $object_type;
  protected $last_update;
  protected $object_types = [
    'repository',
    'resource',
    'archival_object',
    'digital_object'
  ];
  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    
    $this->object_type = $configuration['object_type'];
    if( isset($configuration['last_updated']) ){
      $this->last_update = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $configuration['last_updated']);
    } else {
      $this->last_update = new DateTime();
      $this->last_update->setTimestamp(0);
    }

    // Create the session
    // Send migration config auth options to the Session object
    if( isset($this->configuration['base_uri']) ||
        isset($this->configuration['username']) ||
        isset($this->configuration['password']) ){
      // Get Config Settings
      $base_uri = ( isset($this->configuration['base_uri']) ? $this->configuration['base_uri'] : '' );
      $username = ( isset($this->configuration['username']) ? $this->configuration['username'] : '' );
      $password = ( isset($this->configuration['password']) ? $this->configuration['password'] : '' );

      $this->session = ArchivesSpaceSession::withConnectionInfo(
          $base_uri, $username, $password
        );

    } else { // No login info provided by the migration config
      $this->session = new ArchivesSpaceSession();
    }

  }

  /**
   * Initializes the iterator with the source data.
   * @return \Iterator
   *   An iterator containing the data for this source.
   */
  protected function initializeIterator() {

    return new ArchivesSpaceIterator($this->object_type, $this->last_update, $this->session);

  }

  public function getIds() {
    $ids = [
      'uri' => [
        'type' => 'string'
      ]
    ];
    return $ids;
  }

  public function fields() {
    return array(
      'uri' => $this->t('URI'),
      'name' => $this->t('Name'),
      'repo_code' => $this->t('Repository Code')
    );
  }

  public function __toString() {
    return "ArchivesSpace data";
  }

  // /**
  //  * Returns the initialized data parser plugin.
  //  * We use the JSON parser.
  //  *
  //  * @return \Drupal\migrate_plus\DataParserPluginInterface
  //  *   The data parser plugin.
  //  */
  // public function getDataParserPlugin() {
  //   if (!isset($this->dataParserPlugin)) {
  //     $this->dataParserPlugin = \Drupal::service('plugin.manager.migrate_plus.data_parser')->createInstance('json', $this->configuration);
  //   }
  //   return $this->dataParserPlugin;
  // }

}
