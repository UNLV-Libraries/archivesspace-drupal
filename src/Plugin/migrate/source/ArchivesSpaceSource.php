<?php

namespace Drupal\archivesspace\Plugin\migrate\source;

use DateTime;
use Drupal\archivesspace\ArchivesSpaceIterator;
use Drupal\archivesspace\ArchivesSpaceSession;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;

/**
 * Source plugin for retrieving data from ArchivesSpace.
 *
 * @MigrateSource(
 *   id = "archivesspace"
 * )
 */
class ArchivesSpaceSource extends SourcePluginBase {

  protected $session;
  protected $objectType;
  protected $lastUpdate;
  protected $objectTypes = [
    'repositories',
    'resources',
    'archival_objects',
    'digital_objects',
    'agents/people',
    'agents/corporate_entities',
    'agents/families',
    'subjects',
  ];
  protected $fields = [];
  protected $repository = '';

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    $this->objectType = $configuration['object_type'];

    switch ($this->objectType) {
      case 'resources':
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
          'finding_aid_description_rules' => $this->t('Description Rules'),
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
          'suppressed' => $this->t('Suppressed'),
        ];
        break;

      case 'archival_objects':
        $this->fields = [
          'ancestors' => $this->t('Ancestors'),
          'component_id' => $this->t('Component Unique Identifier'),
          'dates' => $this->t('Dates'),
          'display_string' => $this->t('Display String'),
          'extents' => $this->t('Extents'),
          'has_unpublished_ancestor' => $this->t('Has Unpublished Ancestor'),
          'instances' => $this->t('Instances'),
          'level' => $this->t('Level'),
          'linked_agents' => $this->t('Linked Agents'),
          'parent' => $this->t('Parent Object'),
          'position' => $this->t('Position (weight)'),
          'publish' => $this->t('Publish'),
          'ref_id' => $this->t('Reference Identifier'),
          'repository' => $this->t('Repository URI'),
          'resource' => $this->t('Resource URI'),
          'restrictions_apply' => $this->t('Restrictions Apply'),
          'rights_statements' => $this->t('Rights Statements'),
          'subjects' => $this->t('Subjects'),
          'suppressed' => $this->t('Suppressed'),
          'title' => $this->t('Title'),
          'uri' => $this->t('URI'),
        ];
        break;

      case 'agents/people':
      case 'agents/families':
        // The only field person and family has that corp doesn't is publish,
        // but we don't use it anyway, so all agent cases use the same fieldset.
      case 'agents/corporate_entities':
        $this->fields = [
          'dates_of_existence' => $this->t('Dates of Existence'),
          'display_name' => $this->t('Display Name'),
          'is_linked_to_published_record' => $this->t('Is Linked to a Published Record'),
          'linked_agent_roles' => $this->t('Linked Agent Roles'),
          'names' => $this->t('Names'),
          'notes' => $this->t('Notes'),
          'related_agents' => $this->t('Related Agents'),
          'title' => $this->t('Title'),
          'agent_type' => $this->t('Agent Type'),
          'uri' => $this->t('URI'),
        ];
        break;

      case 'subject':
        $this->fields = [
          'uri' => $this->t('URI'),
          'authority_id' => $this->t('Authority ID'),
          'source' => $this->t('Authority Source'),
          'title' => $this->t('Title'),
          'external_ids' => $this->t('External IDs'),
          'terms' => $this->t('Terms'),
          'is_linked_to_published_record' => $this->t('Is Linked to a Published Record'),
        ];
        break;

      case 'repositories':
        $this->fields = [
          'uri' => $this->t('URI'),
          'name' => $this->t('Name'),
          'repo_code' => $this->t('Repository Code'),
          'publish' => $this->t('Publish?'),
          'agent_representation' => $this->t('Agent Representation'),
        ];
        break;

      case 'top_containers':
        $this->fields = [
          'indicator' => $this->t('Indicator'),
          'type' => $this->t('Type'),
          'collection' => $this->t('Collection'),
          'uri' => $this->t('URI'),
          'restricted' => $this->t('Restricted'),
          'is_linked_to_published_record' => $this->t('Is Linked to a Published Record'),
          'display_string' => $this->t('Display String'),
          'long_display_string' => $this->t('Long Display String'),
          'repository' => $this->t('Repository'),
        ];

      default:
        break;
    }

    if (isset($configuration['last_updated'])) {
      $this->lastUpdate = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $configuration['last_updated']);
    }
    else {
      $this->lastUpdate = new DateTime();
      $this->lastUpdate->setTimestamp(0);
    }

    if (isset($configuration['repository'])) {
      if (is_int($configuration['repository'])) {
        $this->repository = '/repositories/' . $configuration['repository'];
      }
      elseif (preg_match('#^/repositories/[0-9]+$#', $configuration['repository'])) {
        $this->repository = $configuration['repository'];
      }
    }

    // Create the session
    // Send migration config auth options to the Session object.
    if (isset($configuration['base_uri']) ||
        isset($configuration['username']) ||
        isset($configuration['password'])) {
      // Get Config Settings.
      $base_uri = (isset($configuration['base_uri']) ? $configuration['base_uri'] : '');
      $username = (isset($configuration['username']) ? $configuration['username'] : '');
      $password = (isset($configuration['password']) ? $configuration['password'] : '');

      $this->session = ArchivesSpaceSession::withConnectionInfo(
          $base_uri, $username, $password
        );

      // No login info provided by the migration config.
    }
    else {
      $this->session = new ArchivesSpaceSession();
    }

  }

  /**
   * Initializes the iterator with the source data.
   *
   * @return \Iterator
   *   An iterator containing the data for this source.
   */
  protected function initializeIterator() {

    return new ArchivesSpaceIterator($this->objectType, $this->lastUpdate, $this->session, $this->repository);

  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids = [
      'uri' => [
        'type' => 'string',
      ],
    ];
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return $this->fields;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return "ArchivesSpace data";
  }

}
