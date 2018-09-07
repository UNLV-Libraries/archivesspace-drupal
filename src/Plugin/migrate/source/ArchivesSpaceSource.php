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
    'digital_object',
    'agent'
  ];
  protected $fields = [];
  protected $repository = '';

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    $this->object_type = $configuration['object_type'];

    switch ($this->object_type) {
      // case 'repository':
      //   $this->fields = [
      //     'uri' => $this->t('URI'),
      //     'name' => $this->t('Name'),
      //     'repo_code' => $this->t('Repository Code')
      //   ];
      //   break;
      case 'resource':
        $this->fields = [
          'uri' => $this->t('URI'),
          'title' => $this->t('Title'),
          'repository' => $this->t('Repository'),
          'dates' => $this->t('Dates'),
          'classifications' => $this->t('Classifications'),
          'deaccessions' => $this->t('Deaccessions'),
          'ead_id' => $this->t('EAD ID'),
          'ead_location' => $this->t('EAD Location'),
          'extents' => $this->t('Extents'),
          'finding_aid_filing_title' => $this->t('Filing Title'),
          'finding_aid_language' => $this->t('Finding Aid Language'),
          'finding_aid_status' => $this->t('Finding Aid Status'),
          'id_0' => $this->t('ID Position 0'),
          'id_1' => $this->t('ID Position 1'),
          'id_2' => $this->t('ID Position 2'),
          'id_3' => $this->t('ID Position 3'),
          'language' => $this->t('Language Code'),
          'level' => $this->t('Level'),
          'linked_agents' => $this->t('Linked Agents'),
          'notes' => $this->t('Notes'),
          'publish' => $this->t('Publish'),
          'restrictions' => $this->t('Restrictions'),
          'subjects' => $this->t('Subjects'),
          'suppressed' => $this->t('Suppressed')
        ];
        break;
      default:
        break;
    }

    if( isset($configuration['last_updated']) ){
      $this->last_update = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $configuration['last_updated']);
    } else {
      $this->last_update = new DateTime();
      $this->last_update->setTimestamp(0);
    }

    if( isset($configuration['repository'])){
      if(is_int($configuration['repository'])){
        $this->repository = '/repositories/'.$configuration['repository'];
      } elseif (preg_match('#^/repositories/[0-9]+$#',$configuration['repository'])) {
        $this->repository = $configuration['repository'];
      }
    }

    // Create the session
    // Send migration config auth options to the Session object
    if( isset($configuration['base_uri']) ||
        isset($configuration['username']) ||
        isset($configuration['password']) ){
      // Get Config Settings
      $base_uri = ( isset($configuration['base_uri']) ? $configuration['base_uri'] : '' );
      $username = ( isset($configuration['username']) ? $configuration['username'] : '' );
      $password = ( isset($configuration['password']) ? $configuration['password'] : '' );

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

    return new ArchivesSpaceIterator($this->object_type, $this->last_update, $this->session, $this->repository);

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
    return $this->fields;
  }

  public function __toString() {
    return "ArchivesSpace data";
  }

}
